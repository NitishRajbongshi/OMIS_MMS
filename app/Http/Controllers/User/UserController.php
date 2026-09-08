<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\DesgDetail;
use App\Models\PostDetail;
use App\Models\RoleDetail;
use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\UserRoleDetail;
use App\Models\AssetUserMapping;
use App\Models\DepartmentDetail;
use App\Models\UserRoleDetailHist;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterLgdState;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\AssetMasterOfficeType;
use App\Models\AssetMasterUserStatusChangeReason;
use App\Models\Building\AssetBuildingDetail;
use App\Models\Mechanical\AssetMasterFuelType;
use App\Models\Mechanical\AssetMasterVehicleType;
use App\Models\Mechanical\Master\AssetMasterVehicleMaker;
use App\Models\MenuDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Road\Master\AssetMasterQualification;
use App\Models\User\AssetUserQualificationsDtl;
use App\Models\UserMovement;
use App\Models\UserVehicleMappingDetail;
use App\Models\AssetMasterZone;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterSubDivision;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except('checkUserActive');
        // $this->middleware('auth')->except(['checkUserActive', 'registration']);
    }

    public function index(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('User controller Loding Page: ');
            $user = Auth::user();
            $baseQuery = DB::table('users')
                ->select(
                    'users.id',
                    'users.email',
                    'users.name',
                    'users.user_role_id',
                    'users.activity_status',
                    'users.phoneno',
                    'users.gender',
                    'users.address1',
                    'users.address2',
                    'users.pin',
                    'users.country',
                    'users.district',
                    'users.department',
                    'users.office',
                    'users.designation',
                    'users.office_type_cd',
                    'department_details.department_name',
                    'office_details.office_name',
                    'asset_master_zones.zone_name',
                    'asset_master_circles.circle_name',
                    'asset_master_divisions.division_name',
                    'asset_master_sub_divisions.sub_div_name',
                    'desg_details.desg_name',
                    'post_details.post_name',
                    'asset_master_office_types.office_type_desc',
                    'asset_master_lgd_district.dist_name',
                    DB::raw("COALESCE(array_agg(DISTINCT user_role_details.role_id) FILTER (WHERE user_role_details.role_id IS NOT NULL), '{}') as role_ids"),
                    DB::raw("array_agg(DISTINCT role_details.rolename) FILTER (WHERE role_details.rolename IS NOT NULL) as rolename"),
                    DB::raw("COALESCE(array_agg(DISTINCT user_menu_details.menuid) FILTER (WHERE user_menu_details.menuid IS NOT NULL), '{}') as menu_ids"),
                    DB::raw("array_agg(DISTINCT menu_details.menu_name) FILTER (WHERE menu_details.menu_name IS NOT NULL) as menu_name"),
                    'asset_master_lgd_state.state_name'
                )
                ->leftJoin('department_details', 'users.department', '=', 'department_details.id')
                ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
                ->leftJoin('asset_master_zones', 'office_details.zone_cd', '=', 'asset_master_zones.zone_cd')
                ->leftJoin('asset_master_circles', 'office_details.circle_cd', '=', 'asset_master_circles.circle_cd')
                ->leftJoin('asset_master_divisions', 'office_details.division_cd', '=', 'asset_master_divisions.division_cd')
                ->leftJoin('asset_master_sub_divisions', 'office_details.sub_division_cd', '=', 'asset_master_sub_divisions.sub_div_cd')
                ->leftJoin('desg_details', 'users.designation', '=', 'desg_details.id')
                ->leftJoin('post_details', 'users.post', '=', 'post_details.id')
                ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->leftJoin('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
                ->leftJoin('asset_master_lgd_state', 'users.state', '=', 'asset_master_lgd_state.state_code')
                ->leftJoin('user_role_details', 'users.id', '=', 'user_role_details.user_id')
                ->leftJoin('role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->leftJoin('user_menu_details', 'users.id', '=', 'user_menu_details.userid')
                ->leftJoin('menu_details', 'menu_details.id', '=', 'user_menu_details.menuid')
                ->where('users.id', '<>', Auth::user()->id)
                ->whereNotIn('users.user_role_id', ['1', '2'])
                ->orderBy('users.updated_at', 'desc')
                ->groupBy("users.id")
                ->groupBy("department_details.department_name")
                ->groupBy("office_details.office_name")
                ->groupBy("asset_master_zones.zone_name")
                ->groupBy("asset_master_circles.circle_name")
                ->groupBy("asset_master_divisions.division_name")
                ->groupBy("asset_master_sub_divisions.sub_div_name")
                ->groupBy("desg_details.desg_name")
                ->groupBy("post_details.post_name")
                ->groupBy("asset_master_office_types.office_type_desc")
                ->groupBy("asset_master_lgd_district.dist_name")
                ->groupBy("asset_master_lgd_state.state_name");

            // ->distinct();
            // ->get()->first();
            $filtersApplied = $request->boolean('filter_applied');
            $userdetails = collect();

            if ($filtersApplied) {
                if ($user->user_role_id != '1') {
                    $baseQuery->where('users.department', $user->department);
                }

                $baseQuery
                    ->when($request->filled('filter_department'), function ($query) use ($request) {
                        $query->where('users.department', $request->filter_department);
                    })
                    ->when($request->filled('filter_status'), function ($query) use ($request) {
                        $query->where('users.activity_status', $request->filter_status);
                    })
                    ->when($request->filled('filter_office_type'), function ($query) use ($request) {
                        $query->where('users.office_type_cd', $request->filter_office_type);
                    })
                    ->when($request->filled('filter_office'), function ($query) use ($request) {
                        $query->where('users.office', $request->filter_office);
                    })
                    ->when($request->filled('filter_designation'), function ($query) use ($request) {
                        $query->where('users.designation', $request->filter_designation);
                    })
                    ->when($request->filled('filter_user_search'), function ($query) use ($request) {
                        $search = trim((string) $request->filter_user_search);

                        $query->where(function ($userQuery) use ($search) {
                            $userQuery->where('users.name', 'ILIKE', '%' . $search . '%')
                                ->orWhere('users.email', 'ILIKE', '%' . $search . '%');
                        });
                    });

                $userdetails = $baseQuery->get();
            }
            $desgdetails = DesgDetail::orderBy('desg_name')->get();
            $officedetails = OfficeDetail::orderBy('office_name')->get();
            // $postdetails = PostDetail::all();
            $deptdetails = DepartmentDetail::orderBy('department_name')->get();
            $roledetails = RoleDetail::all();
            $menudetails = MenuDetail::orderBy('portal_catg_cd')
                ->orderBy('menu_name')
                ->get();
            $officeTypeDetails = AssetMasterOfficeType::orderBy('office_type_desc')->get();
            $degnOfficeTypeMappings = DB::table('asset_master_designation_office_type_mappings')
                ->select('asset_master_designation_office_type_mappings.desg_cd', 'asset_master_designation_office_type_mappings.office_type_cd', 'asset_master_office_types.office_type_desc')
                ->leftJoin('asset_master_office_types', 'asset_master_designation_office_type_mappings.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->get();
            $districtDetails = DB::table('asset_master_lgd_district')
                ->select('*')
                ->get();
            $states = AssetMasterLgdState::all();
            $reasons = AssetMasterUserStatusChangeReason::all();
            $circles = AssetMasterCircle::all();
            $divisions = AssetMasterDivision::all();
            $subDivisions = AssetMasterSubDivision::all();
            $currentUserRoles = UserRoleDetail::select('role_id')->where('user_id', Auth::user()->id)->get();
            $arrCurrenUserRoles = $currentUserRoles->pluck('role_id')->toArray();

            $query = DB::getQueryLog();
            Log::info($query);

            return view(
                'user.index',
                compact(
                    'user',
                    'userdetails',
                    'desgdetails',
                    'officedetails',
                    // 'postdetails',
                    'deptdetails',
                    'districtDetails',
                    'states',
                    'roledetails',
                    'menudetails',
                    'officeTypeDetails',
                    'degnOfficeTypeMappings',
                    'reasons',
                    'circles',
                    'divisions',
                    'subDivisions',
                    'arrCurrenUserRoles',
                    'filtersApplied'
                )
            );
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }


    public function create()
    {
        try {
            DB::enableQueryLog();
            Log::info('User controller Create Function: ');
            $user = Auth::user();
            $userdetails = DB::table('users')
                ->select(
                    'users.*',
                    'department_details.department_name',
                    'office_details.office_name',
                    'desg_details.desg_name',
                    'post_details.post_name',
                    'asset_master_office_types.office_type_desc',
                    'asset_master_lgd_district.dist_name',
                    'user_role_details.role_id',
                    'role_details.rolename',
                    'asset_master_lgd_state.state_name'
                )
                ->leftJoin('department_details', 'users.department', '=', 'department_details.id')
                ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
                ->leftJoin('desg_details', 'users.designation', '=', 'desg_details.id')
                ->leftJoin('post_details', 'users.post', '=', 'post_details.id')
                ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->leftJoin('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
                ->leftJoin('asset_master_lgd_state', 'users.state', '=', 'asset_master_lgd_state.state_code')
                ->leftJoin('user_role_details', 'users.id', '=', 'user_role_details.user_id')
                ->leftJoin('role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->where('users.id', $user->id)
                ->get()->first();

            $deptdetails = DepartmentDetail::all();
            $desgdetails = DesgDetail::all();
            $officedetails = OfficeDetail::all();
            $postdetails = PostDetail::all();
            $roledetails = RoleDetail::all();
            $officeTypes = AssetMasterOfficeType::all();
            $qualificationDetails = AssetMasterQualification::all();
            $menuDetails = MenuDetail::all();
            $circles = AssetMasterCircle::all();
            $divisions = AssetMasterDivision::all();
            $subDivisions = AssetMasterSubDivision::all();
            // $vehicleDetails = UserVehicleMappingDetail::all();
            $vehMakers = AssetMasterVehicleMaker::all();
            $fuelTypes = AssetMasterFuelType::all();
            $vehTypes = AssetMasterVehicleType::all();
            $quarterDetails = DB::table('buildings.asset_building_details')->select('qtr_no')->whereNotNull('qtr_no')->get();
            $degnOfficeTypeMappings = DB::table('asset_master_designation_office_type_mappings')
                ->select('asset_master_designation_office_type_mappings.desg_cd', 'asset_master_designation_office_type_mappings.office_type_cd', 'asset_master_office_types.office_type_desc')
                ->leftJoin('asset_master_office_types', 'asset_master_designation_office_type_mappings.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->get();
            $districtDetails = DB::table('asset_master_lgd_district')
                ->select('*')
                ->get();
            $states = AssetMasterLgdState::all();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('user.create', compact(
                'user',
                'userdetails',
                'deptdetails',
                'desgdetails',
                'officedetails',
                'postdetails',
                'officeTypes',
                'menuDetails',
                'districtDetails',
                'states',
                'qualificationDetails',
                'degnOfficeTypeMappings',
                'quarterDetails',
                'vehMakers',
                'fuelTypes',
                'vehTypes',
                'circles',
                'divisions',
                'subDivisions'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $fieldInput = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'phoneno' => 'required|digits:10',
            'address1' => 'required|string|max:255',
            'address2' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'pin' => 'required|numeric|digits:6',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'office_list' => 'required',
            'designation' => 'required',
            'office_type' => 'required',
            'qualification' => 'required',
            'assign_date' => 'required',
            'data_entry' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $officeDetails = [];
            // convert email into lowercase
            $preferedEmail = Str::lower($request->email);
            $data = [
                'name' => $request->name,
                'email' => $preferedEmail,
                'password' => Hash::make('12345678'),
                'phoneno' => $request->phoneno,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'district' => $request->district,
                'pin' => $request->pin,
                'state' => $request->state,
                'country' => $request->country,
                'gender' => $request->gender,
                'user_role_id' => '0',
                'department' => $request->department,
                'office' => $request->office_list,
                'designation' => $request->designation,
                'post' => $request->post,
                'activity_status' => 'A',
                'office_type_cd' => $request->office_type,
                'since_current_position' => $request->assign_date,
                // 'qtr_no' => $request->qtr_no
            ];

            // if ($request->designation == '3') {
            //     // get all office details
            //     $departmentName = DB::table('department_details')
            //         ->select('department_name')
            //         ->where('id', $request->department)
            //         ->get()->first();
            //     $designationName = DB::table('desg_details')
            //         ->select('desg_name')
            //         ->where('id', $request->designation)
            //         ->get()->first();
            //     $officeTypeName = DB::table('asset_master_office_types')
            //         ->select('office_type_desc')
            //         ->where('office_type_cd', $request->office_type)
            //         ->get()->first();
            //     $officeName = DB::table('office_details')
            //         ->select('office_name')
            //         ->where('id', $request->other_office)
            //         ->get()->first();
            //     $officeDetails[] = [
            //         'department' => $request->department,
            //         'department_name' => $departmentName->department_name,
            //         'designation' => $request->designation,
            //         'designation_name' => $designationName->desg_name,
            //         'office_type_cd' => "HQ",
            //         'office_type_name' => $officeTypeName->office_type_desc,
            //         'office' => $request->other_office,
            //         'office_name' => $officeName->office_name,
            //         'from' => $request->assign_date,
            //         'to' => null,
            //         'remarks' => null,
            //         'appointment_type_cd' => 'P',
            //         'appointment_type' => 'Permanent',
            //         'status' => 'A',
            //         'activity_status' => 'Active',
            //         'data_entry' => 'N',
            //         'created_by' => auth::user()->id,
            //         'created_user_name' => auth::user()->name,
            //     ];
            // }
            if ($request->has_additional_office == 'Y') {
                $AddDepartmentName = DB::table('department_details')
                    ->select('department_name')
                    ->where('id', $request->additional_department)
                    ->get()->first();
                $addDesignationName = DB::table('desg_details')
                    ->select('desg_name')
                    ->where('id', $request->additional_designation)
                    ->get()->first();
                $addOfficeTypeName = DB::table('asset_master_office_types')
                    ->select('office_type_desc')
                    ->where('office_type_cd', $request->additional_office_type)
                    ->get()->first();
                $addOfficeName = DB::table('office_details')
                    ->select('office_name')
                    ->where('id', $request->additional_office_list)
                    ->get()->first();
                $officeDetails[] = [
                    'department' => $request->additional_department,
                    'department_name' => $AddDepartmentName->department_name,
                    'designation' => $request->additional_designation,
                    'designation_name' => $addDesignationName->desg_name,
                    'office_type_cd' => $request->additional_office_type,
                    'office_type_name' => $addOfficeTypeName->office_type_desc,
                    'office' => $request->additional_office_list,
                    'office_name' => $addOfficeName->office_name,
                    'from' => $request->additional_assign_date,
                    'to' => null,
                    'remarks' => null,
                    'appointment_type_cd' => 'T',
                    'appointment_type' => 'Temporary',
                    'status' => 'A',
                    'activity_status' => 'Active',
                    'data_entry' => $request->add_data_entry,
                    'created_by' => auth::user()->id,
                    'created_user_name' => auth::user()->name,
                ];
            }
            $otherAdditionalOffices = $request->todos_labels;
            if ((isset($otherAdditionalOffices)) && (count($otherAdditionalOffices) > 0)) {
                foreach ($otherAdditionalOffices as $element) {
                    $AddDepartmentName = DB::table('department_details')
                        ->select('department_name')
                        ->where('id', $element['additional_department'])
                        ->get()->first();
                    $addDesignationName = DB::table('desg_details')
                        ->select('desg_name')
                        ->where('id', $element['additional_designation'])
                        ->get()->first();
                    $addOfficeTypeName = DB::table('asset_master_office_types')
                        ->select('office_type_desc')
                        ->where('office_type_cd', $element['additional_office_type'])
                        ->get()->first();
                    $addOfficeName = DB::table('office_details')
                        ->select('office_name')
                        ->where('id', $element['additional_office_list'])
                        ->get()->first();
                    $officeDetails[] = [
                        'department' => $element['additional_department'],
                        'department_name' => $AddDepartmentName->department_name,
                        'designation' => $element['additional_designation'],
                        'designation_name' => $addDesignationName->desg_name,
                        'office_type_cd' => $element['additional_office_type'],
                        'office_type_name' => $addOfficeTypeName->office_type_desc,
                        'office' => $element['additional_office_list'],
                        'office_name' => $addOfficeName->office_name,
                        'from' => $element['additional_assign_date'],
                        'to' => null,
                        'remarks' => null,
                        'appointment_type_cd' => 'T',
                        'appointment_type' => 'Temporary',
                        'status' => 'A',
                        'activity_status' => 'Active',
                        'data_entry' => $element['additional_data_entry'],
                        'created_by' => auth::user()->id,
                        'created_user_name' => auth::user()->name,
                    ];
                }
            }
            if (count($officeDetails) > 0) {
                $additional_office_details = json_encode(['office_details' => $officeDetails]);
                $data['additional_office_details'] = $additional_office_details;
            }

            $user = User::create($data);
            if ($user) {
                // update the housing table with new user name
                // $buildingDetail = AssetBuildingDetail::where('qtr_no', $request->qtr_no)->first();

                // // Check if the record exists
                // if ($buildingDetail) {
                //     // If the record exists, update the occupant_name and occupant_dept_cd fields
                //     $buildingDetail->occupant_name = $request->name;
                //     $buildingDetail->occupant_dept_cd = $request->department;
                //     $buildingDetail->save();
                // }

                // Add User Qualification
                $qualificationData = [
                    'qualificationid' => $request->qualification,
                    'user_id' => $user->id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'details' => null
                ];
                $qualificationStatus = AssetUserQualificationsDtl::create($qualificationData);
                if ($qualificationStatus) {
                    // Store data entry perssion as a menu item
                    if ($request->data_entry == 'Y') {
                        $userMenuDetail = new UserMenuDetail();
                        $userMenuDetail->userid = $user->id;
                        $userMenuDetail->menuid = 13;
                        $userMenuDetail->active = '1';
                        $userMenuDetail->created_at = Carbon::now();
                        $userMenuDetail->updated_at = Carbon::now();
                        $userMenuDetail->save();
                    }

                    // Create and save user menu details (if applicable)
                    $menus = ['Manage_Department', 'Manage_Users', 'Manage_Office', 'Manage_Designations', 'Manage_Posts', 'Manage_Roles', 'Manage_Roads', 'Modify_Request', 'Manage_Buildings', 'Manage_Mechanicals', 'Manage_NH', 'Data_Entry'];
                    foreach ($menus as $menu) {
                        if (isset($request->$menu)) {
                            $userMenuDetail = new UserMenuDetail();
                            $userMenuDetail->userid = $user->id;
                            $userMenuDetail->menuid = $request->$menu;
                            $userMenuDetail->active = '1';
                            $userMenuDetail->created_at = Carbon::now();
                            $userMenuDetail->updated_at = Carbon::now();
                            $userMenuDetail->save();
                        }
                    }

                    // store the asset user mapping info
                    $mappingData = $request->only([
                        'email',
                        'office_type_cd',
                        'office'
                    ]);

                    // find the user 
                    $user_id = User::find($user->id);
                    $officeDetails = DB::table('office_details')
                        ->select('*')
                        ->where('id', '=', $request->office_list)
                        ->first();
                    if ($user_id && $officeDetails) {
                        $mappingData = [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'office_type_cd' => $request->office_type,
                            'office_cd' => $request->office_list,
                            'zone_cd' => $officeDetails->zone_cd,
                            'circle_cd' => $officeDetails->circle_cd,
                            'division_cd' => $officeDetails->division_cd,
                            'sub_division_cd' => $officeDetails->sub_division_cd,
                        ];

                        $status = AssetUserMapping::create($mappingData);
                        if ($status) {
                            $movmentData = [
                                'user_id' => $user->id,
                                'user_name' => $user->name,
                                'user_email' => $user->email,
                                'user_dept' => $user->department,
                                'user_desg' => $user->designation,
                                'user_office_type_cd' => $user->office_type_cd,
                                'user_office' => $user->office,
                                'assign_from' => $request->assign_date,
                                'assign_to' => null,
                                'reason' => null
                            ];
                            $movementStatus = UserMovement::create($movmentData);
                            if ($movementStatus) {
                                $vehicleDetails = [
                                    'user_id' => $user->id,
                                    'regn_no' => $request->vehicle_regn_no,
                                    'chassis_no' => $request->chassis_no,
                                    'engine_no' => $request->engine_no,
                                    'vehicle_type' => $request->vehicle_type,
                                    'model' => $request->model,
                                    'maker' => $request->maker,
                                    'fuel_type' => $request->fuel_type,
                                ];
                                $vehicleStatus = UserVehicleMappingDetail::create($vehicleDetails);
                                if ($vehicleStatus) {
                                    DB::commit();
                                    return redirect()->back()
                                        ->with('success', 'User added successfully.');
                                } else {
                                    DB::rollback();
                                    return redirect()->back()
                                        ->with('error', 'Failed to add user vehicle details!')
                                        ->withInput();
                                }
                            } else {
                                DB::rollback();
                                return redirect()->back()
                                    ->with('error', 'Failed to add user due to some internal error!')
                                    ->withInput();
                            }
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with('error', 'Failed to assign the menus to the user!')
                                ->withInput();
                        }
                    }
                } else {
                    // Roll back
                    // DB::table('public.users')->where('id', $user->id)->delete();
                    DB::rollback();
                    return redirect()->back()
                        ->with('error', 'Failed to add education qualification!')
                        ->withInput();
                }
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to add user!')
                    ->withInput();
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function update(Request $request)
    {
        Log::info($request->all());
        // return $request;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phoneno' => 'required|digits:10',
            'address1' => 'required',
            'address2' => 'required',
            'district' => 'required',
            'pin' => 'required|numeric|digits:6',
            'gender' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'office_type_cd' => 'required',
            'office' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '403',
                'response' => 'failed',
                'message' => 'Validation failed!'
            ]);
        }
        DB::beginTransaction();
        try {
            $userdata = User::findOrFail($request->id);
            $changes = [
                'department' => false,
                'designation' => false,
                'status' => false,
                'office_type_cd' => false,
                'office' => false,
            ];

            // check for changes
            if ($userdata->activity_status != $request->status) {
                $changes['status'] = true;
            }
            if ($userdata->department != $request->department) {
                $changes['department'] = true;
            }
            if ($userdata->designation != $request->designation) {
                $changes['designation'] = true;
            }
            if ($userdata->office_type_cd != $request->office_type_cd) {
                $changes['office_type_cd'] = true;
            }
            if ($userdata->office != $request->office) {
                $changes['office'] = true;
            }

            if ($userdata) {
                $userdata->name = $request->name;
                $userdata->phoneno = $request->phoneno;
                $userdata->address1 = $request->address1;
                $userdata->address2 = $request->address2;
                $userdata->district = $request->district;
                $userdata->pin = $request->pin;
                $userdata->gender = $request->gender;
                $userdata->department = $request->department;
                $userdata->designation = $request->designation;
                $userdata->office_type_cd = $request->office_type_cd;
                $userdata->office = $request->office;
                $userdata->activity_status = $request->status;
                $userdata->updated_at = Carbon::now();
                $status = $userdata->save();
                if ($status) {
                    // handle deactive status
                    if ($request->status == 'D' && $changes['status']) {
                        $lastUserMovement = DB::table('user_movements')
                            ->select('id')
                            ->where('user_id', $request->id)
                            ->latest()
                            ->first();
                        if ($lastUserMovement) {
                            $movementStatus = DB::table('user_movements')
                                ->where('id', $lastUserMovement->id)
                                ->update([
                                    'assign_to' => $request->assign_to,
                                    'reason' => $request->reason,
                                    'updated_at' => Carbon::now(),
                                ]);
                            if ($movementStatus) {
                                DB::commit();
                                return response()->json([
                                    'status' => '200',
                                    'response' => 'success',
                                    'message' => 'User details updated successfully.'
                                ]);
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'status' => '400',
                                    'response' => 'failed',
                                    'message' => 'Failed to trace user modification!'
                                ]);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'status' => '400',
                                'response' => 'failed',
                                'message' => 'User have no previous record!'
                            ]);
                        }
                    }
                    if (($request->status == 'A') && ($changes['status'] || $changes['department'] || $changes['designation'] || $changes['office_type_cd'] || $changes['office'])) {
                        $lastUserMovement = DB::table('user_movements')
                            ->select('id', 'assign_to', 'reason')
                            ->where('user_id', $request->id)
                            ->latest()
                            ->first();
                        if ($lastUserMovement) {
                            if ($lastUserMovement->assign_to == null || $lastUserMovement->reason == null) {
                                DB::table('user_movements')
                                    ->where('id', $lastUserMovement->id)
                                    ->update([
                                        'assign_to' => $request->assign_from,
                                        'reason' => $request->remarks,
                                        'updated_at' => Carbon::now(),
                                    ]);
                            }

                            $movmentData = [
                                'user_id' => $request->id,
                                'user_name' => $userdata->name,
                                'user_email' => $userdata->email,
                                'user_dept' => $request->department,
                                'user_desg' => $request->designation,
                                'user_office_type_cd' => $request->office_type_cd,
                                'user_office' => $request->office,
                                'assign_from' => $request->assign_from,
                                'assign_to' => null,
                                'reason' => null
                            ];
                            $movementStatus = UserMovement::create($movmentData);
                            if ($movementStatus) {
                                DB::commit();
                                return response()->json([
                                    'status' => '200',
                                    'response' => 'success',
                                    'message' => 'User details updated successfully.'
                                ]);
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'status' => '400',
                                    'response' => 'failed',
                                    'message' => 'Failed to trace user modification!'
                                ]);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'status' => '400',
                                'response' => 'failed',
                                'message' => 'User have no previous record!'
                            ]);
                        }
                    }
                    // default user update modify status
                    DB::commit();
                    return response()->json([
                        'status' => '200',
                        'response' => 'success',
                        'message' => 'User details updated successfully.'
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => '500',
                        'response' => 'failed',
                        'message' => 'Internal server error!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => '404',
                    'response' => 'failed',
                    'message' => 'User not found!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error in user updatation: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => '500',
                'response' => 'failed',
                'message' => 'An error occurred while updating the user.',
            ], 500);
        }
    }

    public function updateUser(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phoneno' => 'required|digits:10',
            'address1' => 'required',
            'address2' => 'required',
            'district' => 'required',
            'pin' => 'required|numeric|digits:6',
            'state' => 'required',
            'country' => 'required',
            'gender' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'post' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'validationFailed']);
        }

        $userdata = User::find($request->id);
        if ($userdata) {
            $userdata->name = $request->name;
            $userdata->email = $request->email;
            $userdata->phoneno = $request->phoneno;
            $userdata->address1 = $request->address1;
            $userdata->address2 = $request->address2;
            $userdata->district = $request->district;
            $userdata->pin = $request->pin;
            $userdata->state = $request->state;
            $userdata->country = $request->country;
            $userdata->gender = $request->gender;
            $userdata->department = $request->department;
            $userdata->office = $request->office;
            $userdata->designation = $request->designation;
            $userdata->post = $request->post;
            $userdata->updated_at = Carbon::now();
            $status = $userdata->save();
            if ($status) {
                return response()->json(['message' => 'success']);
            } else {
                return response()->json(['message' => 'success']);
            }
        } else {
            return response()->json(['message' => 'success']);
        }
    }

    public function view(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('User controller inside View Function: ');
            $user = Auth::user();
            $departmentDetails = DepartmentDetail::orderBy('department_name')->get();
            $officeTypes = AssetMasterOfficeType::orderBy('office_type_desc')->get();
            $officeDetails = OfficeDetail::orderBy('office_name')->get();
            $statuChangeResons = AssetMasterUserStatusChangeReason::all();
            $qualificationDetails = AssetMasterQualification::all();
            $designationDetails = DB::table("desg_details")
                ->select("desg_details.*")
                ->orderBy("dept_cd")
                ->orderBy("desg_name")->get();
            $baseQuery = DB::table('users')
                ->select(
                    'users.*',
                    'department_details.department_name',
                    'office_details.office_name',
                    'desg_details.desg_name',
                    'post_details.post_name',
                    'asset_master_office_types.office_type_desc',
                    'asset_master_lgd_district.dist_name',
                    'user_role_details.role_id',
                    'role_details.rolename',
                    'asset_master_lgd_state.state_name',
                    'qualification_details.qualification_name',
                    'user_movements.reason'
                )
                ->leftJoin('department_details', 'users.department', '=', 'department_details.id')
                ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
                ->leftJoin('desg_details', 'users.designation', '=', 'desg_details.id')
                ->leftJoin('post_details', 'users.post', '=', 'post_details.id')
                ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->leftJoin('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
                ->leftJoin('asset_master_lgd_state', 'users.state', '=', 'asset_master_lgd_state.state_code')
                ->leftJoin(
                    'user_role_details',
                    function ($join) {
                        $join->on('users.id', '=', 'user_role_details.user_id')
                            ->on('users.user_role_id', '=', 'user_role_details.role_id');
                    }

                    //'users.id', '=', 'user_role_details.user_id'
                )
                ->leftJoin('role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->leftJoin(
                    DB::raw('(SELECT u.user_id, q.details AS qualification_name FROM 
                        asset_user_qualifications_dtls u JOIN asset_master_qualifications q
                        ON u.qualificationid = q.qualificationid) AS qualification_details'),
                    'users.id',
                    '=',
                    'qualification_details.user_id'
                )
                ->leftJoin(DB::raw('(select user_id,reason from user_movements where id in (select max(id) 
                from user_movements group by user_id)) as user_movements'), 'user_movements.user_id', '=', 'users.id')
                ->where('users.id', '<>', $user->id)
                ->where('users.user_role_id', '<>', '1')
                //->where('user_role_details.role_id', '<>', '31')
                //->where('users.user_role_id', 'user_role_details.role_id')
                ->orderBy('users.department', 'desc')
                ->orderBy('users.designation', 'desc')
                ->orderBy('users.office', 'desc')
                ->orderBy('users.updated_at', 'desc')
                ->distinct();
            $filtersApplied = $request->boolean('filter_applied');
            $userdetails = collect();

            if ($filtersApplied) {
                if ($user->office_type_cd === 'DA') {
                    $baseQuery->where('users.department', $user->department);
                }

                $baseQuery
                    ->when($request->filled('filter_department'), function ($query) use ($request) {
                        $query->where('users.department', $request->filter_department);
                    })
                    ->when($request->filled('filter_status'), function ($query) use ($request) {
                        $query->where('users.activity_status', $request->filter_status);
                    })
                    ->when($request->filled('filter_office_type'), function ($query) use ($request) {
                        $query->where('users.office_type_cd', $request->filter_office_type);
                    })
                    ->when($request->filled('filter_office'), function ($query) use ($request) {
                        $query->where('users.office', $request->filter_office);
                    })
                    ->when($request->filled('filter_designation'), function ($query) use ($request) {
                        $query->where('users.designation', $request->filter_designation);
                    })
                    ->when($request->filled('filter_user_search'), function ($query) use ($request) {
                        $search = trim((string) $request->filter_user_search);

                        $query->where(function ($userQuery) use ($search) {
                            $userQuery->where('users.name', 'ILIKE', '%' . $search . '%')
                                ->orWhere('users.email', 'ILIKE', '%' . $search . '%');
                        });
                    });

                $userdetails = $baseQuery->get();
            }
            $query = DB::getQueryLog();
            Log::info($query);

            return view(
                'user.view',
                compact(
                    'userdetails',
                    'departmentDetails',
                    'officeDetails',
                    'designationDetails',
                    'officeTypes',
                    'statuChangeResons',
                    'qualificationDetails',
                    'filtersApplied'
                )
            );
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    // public function destroy($id)
    // {
    //     $data = User::find($id);
    //     $data->delete();
    //     alert()->success('Record Deleted successfully')->persistent('Close')->autoclose(3000);
    //     return redirect('manage-user');
    // }

    public function assignUserRole(Request $request)
    {
        try {
            DB::enableQueryLog();
            $selectedRoleIds = $request->input('role_id');
            LOG::info("pothua hol: ");
            LOG::info($selectedRoleIds);
            $role_assigning_to = $request->user_id;
            if (in_array(2, $selectedRoleIds)) // ie if requested Role is Departmental Admin
            //DA role can assigned to only one user within a Department
            {
                $checkSelectedUsersDept = DB::table('users')
                    ->select("users.department")
                    ->where('users.id', $request->user_id)
                    ->get()->first();
                $usersDeptCode = $checkSelectedUsersDept->department;
                $checkIfDARoleExistWithinSameDept = DB::table('user_role_details')
                    ->select("user_role_details.id", "users.department", "users.name")
                    ->join("users", "users.id", "=", "user_role_details.user_id")
                    ->where('user_id', '!=', $request->user_id)
                    ->where('role_id', '=', 2)
                    ->where('department', '=', $usersDeptCode)
                    ->get()->first();
                if ($checkIfDARoleExistWithinSameDept) {
                    $userName = $checkIfDARoleExistWithinSameDept->name;
                    return response()->json([
                        'status' => False,
                        'message' => "Could Not Assign!!!\nDepartmental Admin Role is Already Assigned to user: " . $userName
                    ]);
                }
            }

            //Move Old Assigned Roles to Hist table-- Start
            $oldAssignedRoles = DB::table('user_role_details')
                ->select("user_role_details.*")
                ->where("user_role_details.user_id", $request->user_id)
                ->get();

            if ($oldAssignedRoles) {
                $hist_created_by = Auth::user()->id;
                $hist_created_at = Carbon::now();
                $status = DB::table('public.user_role_details_hist')->insertUsing([
                    'user_id',
                    'role_id',
                    'inserted_by',
                    'created_at',
                    'updated_at',
                    'hist_created_at',
                    'hist_created_by'
                ], function ($query) use ($role_assigning_to, $hist_created_by, $hist_created_at) {
                    $query->from('public.user_role_details')
                        ->where('user_id', '=', $role_assigning_to)
                        ->select(
                            'user_id',
                            'role_id',
                            'inserted_by',
                            'created_at',
                            'updated_at',
                            DB::raw("'$hist_created_at' as hist_created_at"),
                            DB::raw("'$hist_created_by' as hist_created_by")
                        );
                });

                if ($status) {
                    DB::table('user_role_details')
                        ->select("user_role_details.*")
                        ->where("user_role_details.user_id", $request->user_id)
                        ->delete();
                }
                $query = DB::getQueryLog();
                Log::info($query);



                foreach ($selectedRoleIds as $option) {
                    $userNewRoleDetail = new UserRoleDetail();
                    $userNewRoleDetail->user_id = $request->user_id;
                    $userNewRoleDetail->role_id = $option;
                    $userNewRoleDetail->inserted_by = $hist_created_by;
                    $userNewRoleDetail->save();
                }

                $query = DB::getQueryLog();
                Log::info($query);
                return response()->json([
                    'status' => True,
                    'message' => "Role assigned successfully"
                ]);
            }
        } catch (Exception $e) {
            LOG::error("Error In Assigning Users Role::: ");
            LOG::error($e);
            return response()->json([
                'status' => False,
                'message' => "Some Technical Issue Raised!!Please Try After Some Or Contact Administrator!!!"
            ]);
        }


        // try {

        //     $validator = Validator::make($request->all(), [
        //         'role_id.*' => 'required'
        //     ]);
        //     if ($validator->fails()) {

        //         return response()->json([
        //             'msg' => 'validationFails',
        //             'error' => $validator->errors()
        //         ]);
        //     } else {


        //         for ($i = 0; $i < count($request->role_id); $i++) {
        //             $details[] = [
        //                 'user_id' => $request->user_id,
        //                 'role_id' => $request->role_id[$i],
        //                 'inserted_by' => Auth::user()->id,
        //                 'created_at' => Carbon::now(),
        //                 'updated_at' => Carbon::now(),
        //             ];
        //         }
        //         $details = collect($details);
        //         $chunks = $details->chunk(500);

        //         foreach ($chunks as $chunk) {
        //             UserRoleDetail::insert($chunk->toArray());
        //         }



        //         alert()->success('Data Updated successfully')->persistent('Close')->autoclose(3000);
        //         return redirect('manage-user');
        //     }
        // } catch (Exception $e) {
        //     return $e;
        // }
    }

    public function getProfile()
    {
        $userid = Auth::user()->id;
        $userDetails = DB::table('users')
            ->select('users.*', 'department_details.department_name', 'office_details.office_name', 'desg_details.desg_name', 'post_details.post_name', 'asset_master_office_types.office_type_desc', 'asset_master_lgd_district.dist_name')
            ->leftJoin('department_details', 'users.department', '=', 'department_details.id')
            ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
            ->leftJoin('desg_details', 'users.designation', '=', 'desg_details.id')
            ->leftJoin('post_details', 'users.post', '=', 'post_details.id')
            ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
            ->leftJoin('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
            ->where('users.id', '=', $userid)
            ->get();
        return view(
            'profile.viewprofile',
            compact(
                'userDetails',
            )
        );
    }

    public function editProfile()
    {
        $userid = Auth::user()->id;
        $userDetails = DB::table('users')
            ->select('users.*', 'department_details.department_name', 'office_details.office_name', 'desg_details.desg_name', 'post_details.post_name', 'asset_master_office_types.office_type_desc', 'asset_master_lgd_district.dist_name')
            ->join('department_details', 'users.department', '=', 'department_details.id')
            ->join('office_details', 'users.office', '=', 'office_details.id')
            ->join('desg_details', 'users.designation', '=', 'desg_details.id')
            ->join('post_details', 'users.post', '=', 'post_details.id')
            ->join('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
            ->join('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
            ->where('users.id', '=', $userid)
            ->get();

        return view(
            'profile.editProfile',
            compact(
                'userDetails',
            )
        );
    }

    public function checkUserActive(Request $request)
    {
        $department = $request->input('department');
        $email = $request->input('email');

        $userStatus = User::where('email', $email)
            ->where('department', $department)
            ->select('activity_status')
            ->get()
            ->first();
        if ($userStatus) {
            return response()->json([
                'status' => 200,
                'message' => $userStatus->activity_status == 'A' ? 'active' : 'deactive',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User unavailable or deactivated!',
            ]);
        }
    }

    public function activeUser(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'modal_activity_status' => 'required|string|max:255',
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $userdata = User::find($request->user_id);
                    $userdata->activity_status = $request->modal_activity_status;
                    $userdata->created_at = Carbon::now();
                    $userdata->updated_at = Carbon::now();
                    $userdata->save();

                    return response()->json([
                        'message' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getMenuAccess(Request $request)
    {
        $userId = $request->id;

        $menuDetails = $userId;

        $menuDetails = DB::table('menu_details as md')
            ->select('md.id', 'md.menu_name')
            ->whereNotIn('md.id', function ($query) use ($userId) {
                $query->select('umd.menuid')
                    ->from('user_menu_details as umd')
                    ->where('umd.userid', $userId);
            })
            ->get();


        $userMenuDetails = DB::table('user_menu_details')
            ->select('user_menu_details.id', 'user_menu_details.userid', 'user_menu_details.menuid', 'menu_details.menu_name')
            ->join('menu_details', 'user_menu_details.menuid', '=', 'menu_details.id')
            ->where('user_menu_details.userid', '=', $userId)
            ->get();

        $responseData = [
            'menuDetails' => $menuDetails,
            'userMenuDetails' => $userMenuDetails,
        ];

        return $responseData;
    }

    public function editMenuAccess(Request $request)
    {

        $userId = $request->input('userId');
        $selectedOptions = $request->input('selectedOptions');

        Log::info("Updating User Menu: " . $userId);
        // delete the previous record first
        DB::table('user_menu_details')
            ->where('userid', $userId)
            ->delete();

        foreach ($selectedOptions as $option) {
            $userMenuDetail = new UserMenuDetail();
            $userMenuDetail->userid = $userId;
            $userMenuDetail->menuid = $option;
            $userMenuDetail->active = '1';
            $userMenuDetail->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data saved successfully'
        ]);
    }

    public function mis()
    {
        DB::enableQueryLog();
        Log::info('Building controller: ');
        $userCountByDesignations = DB::table('users')
            ->select(
                'users.designation',
                DB::raw('COUNT(*) as no_of_users'),
                'desg_details.desg_name'
            )
            ->join('desg_details', 'users.designation', '=', 'desg_details.id')
            ->groupBy('users.designation', 'desg_details.desg_name', 'desg_details.desg_precedence_id')
            ->orderBy('desg_details.desg_precedence_id', 'ASC')
            ->get();
        $userCountByOffices = DB::table('users')
            ->select(
                'users.office',
                DB::raw('COUNT(*) as no_of_users'),
                'office_details.office_name'
            )
            ->join('office_details', 'users.office', '=', 'office_details.id')
            ->groupBy('users.office', 'office_details.office_name')
            ->get();

        $userInDesignations = DB::table('desg_details as dd')
            ->crossJoin('office_details as od')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.office', '=', 'od.id')
                    ->on('u.designation', '=', 'dd.id');
            })
            ->select('dd.desg_name', 'od.office_name', DB::raw('COUNT(u.id) as user_count'))
            ->groupBy('dd.desg_name', 'od.office_name')
            ->orderBy('dd.desg_name')
            ->orderBy('od.office_name')
            ->get();

        $userInOffices = DB::table('office_details as od')
            ->crossJoin('desg_details as dd')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.office', '=', 'od.id')
                    ->on('u.designation', '=', 'dd.id');
            })
            ->select('od.office_name', 'dd.desg_name', DB::raw('COUNT(u.id) as user_count'))
            ->groupBy('od.office_name', 'dd.desg_name')
            ->orderBy('od.office_name')
            ->orderBy('dd.desg_name')
            ->get();
        $query = DB::getQueryLog();
        Log::info($query);
        return view('user.mis', compact('userCountByDesignations', 'userCountByOffices', 'userInDesignations', 'userInOffices'));
    }

    public function getUserMovement(Request $request)
    {
        $userId = $request->id;
        $userMovements = DB::table('user_movements')
            ->select(
                'user_movements.*',
                'department_details.department_name',
                'desg_details.desg_name',
                'asset_master_office_types.office_type_desc',
                'office_details.office_name',
            )
            ->leftJoin('department_details', 'user_movements.user_dept', '=', 'department_details.id')
            ->leftJoin('desg_details', 'user_movements.user_desg', '=', 'desg_details.id')
            ->leftJoin('asset_master_office_types', 'user_movements.user_office_type_cd', '=', 'asset_master_office_types.office_type_cd')
            ->leftJoin('office_details', 'user_movements.user_office', '=', 'office_details.id')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();
        $userDetails = User::select('name')->where('id', $userId)->get()->first();
        $userName = $userDetails->name;
        return view('user.showUserMovement', ['userId' => $userId, 'userMovements' => $userMovements, 'userName' => $userName]);
    }

    public function getTemporaryUserMovement(Request $request)
    {
        $userId = $request->id;
        $userDetails = DB::table('users')
            ->select('id', 'name', 'additional_office_details')
            ->where('id', $userId)
            ->get()->first();
        $officeDetails = json_decode($userDetails->additional_office_details, true);
        $userDetails = User::select('name')->where('id', $userId)->get()->first();
        $userName = $userDetails->name;
        return view('user.showTempUserMovement', ['userId' => $userId, 'officeDetails' => $officeDetails, 'userName' => $userName]);
    }

    public function getUserListByDesignation(Request $request)
    {
        $desgID = $request->id;
        $userList = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.phoneno',
                'users.address1',
                'users.address2',
                'asset_master_office_types.office_type_desc',
                'office_details.office_name',
            )
            ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
            ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
            ->where('users.designation', $desgID)
            ->orderBy('id', 'asc')
            ->get();
        $degnDetails = DB::table('desg_details')
            ->select('desg_name')
            ->where('id', $desgID)
            ->get()->first();
        $desgName = $degnDetails->desg_name;
        return view('user.userListByDesignation', ['userLists' => $userList, 'desgName' => $desgName]);
    }

    public function getUserByTimePeriod(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $user = Auth::user();
                DB::enableQueryLog();
                Log::info('User controller inside getUserByTimePeriod function ');
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                $userDetails = DB::table('users')
                    ->select(
                        'users.*',
                        'department_details.department_name',
                        'office_details.office_name',
                        'desg_details.desg_name',
                        'post_details.post_name',
                        'asset_master_office_types.office_type_desc',
                        'asset_master_lgd_district.dist_name',
                        'user_role_details.role_id',
                        'role_details.rolename',
                        'asset_master_lgd_state.state_name',
                        'qualification_details.qualification_name',
                        'user_movements.reason'
                    )
                    ->leftJoin('department_details', 'users.department', '=', 'department_details.id')
                    ->leftJoin('office_details', 'users.office', '=', 'office_details.id')
                    ->leftJoin('desg_details', 'users.designation', '=', 'desg_details.id')
                    ->leftJoin('post_details', 'users.post', '=', 'post_details.id')
                    ->leftJoin('asset_master_office_types', 'users.office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                    ->leftJoin('asset_master_lgd_district', 'users.district', '=', 'asset_master_lgd_district.dist_code')
                    ->leftJoin('asset_master_lgd_state', 'users.state', '=', 'asset_master_lgd_state.state_code')
                    ->leftJoin('user_role_details', 'users.id', '=', 'user_role_details.user_id')
                    ->leftJoin('role_details', 'role_details.id', '=', 'user_role_details.role_id')
                    ->leftJoin(
                        DB::raw('(SELECT u.user_id, q.details AS qualification_name FROM 
                        asset_user_qualifications_dtls u JOIN asset_master_qualifications q
                        ON u.qualificationid = q.qualificationid) AS qualification_details'),
                        'users.id',
                        '=',
                        'qualification_details.user_id'
                    )
                    ->leftJoin(DB::raw('(select user_id,reason from user_movements where id in (select max(id) 
                from user_movements group by user_id)) as user_movements'), 'user_movements.user_id', '=', 'users.id')
                    ->where('users.id', '<>', $user->id)
                    ->where('users.user_role_id', '<>', '1')
                    ->whereBetween('since_current_position', [$startDate, $endDate])
                    ->orderBy('users.department', 'desc')
                    ->orderBy('users.designation', 'desc')
                    ->orderBy('users.office', 'desc')
                    ->orderBy('users.updated_at', 'desc')
                    ->distinct()->get();

                if ($userDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Users data fetched successfully!',
                        'result' => $userDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Users details not available!',
                        'result' => null
                    ]);
                }
                $query = DB::getQueryLog();
                Log::info($query);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function additionalOfficeInfo(Request $request)
    {
        $userID = $request->id;
        session(['searchUserId' => $userID]);
        return redirect()->route('manageUserAddOffice');
    }

    public function manageAdditionalOffice(Request $request)
    {
        $searchUserId = session('searchUserId');
        $searchUserName = User::select('name')->where('id', $searchUserId)->get()->first();
        $user = Auth::user();
        $deptdetails = DepartmentDetail::all();
        $desgdetails = DesgDetail::all();
        $officedetails = OfficeDetail::all();
        $officeTypes = AssetMasterOfficeType::all();
        if ($searchUserId) {
            $userDetails = DB::table('users')
                ->select('id', 'name', 'additional_office_details')
                ->where('id', $searchUserId)
                ->get()->first();
            $officeDetails = json_decode($userDetails->additional_office_details, true);
            return view('user.manageAdditionalOffice', compact(
                'officeDetails',
                'searchUserId',
                'user',
                'deptdetails',
                'desgdetails',
                'officedetails',
                'officeTypes',
                'searchUserName'
            ));
        } else {
            // redirect to home
            $secreteCode = Auth::user()->secret_code;
            return view('home', compact(
                'secreteCode',
            ));
        }
    }

    public function storeAdditionalOffice(Request $request)
    {
        $fieldInput = $request->validate([
            'department' => 'required',
            'designation' => 'required',
            'office_type' => 'required',
            'office_list' => 'required',
            'assign_date' => 'required',
            'data_entry' => 'required',
        ]);

        try {
            $userId = $request->userid;
            $user = User::find($userId);
            if ($user) {
                $userAddUserDetails = json_decode($user->additional_office_details, true);

                $departmentName = DB::table('department_details')
                    ->select('department_name')
                    ->where('id', $request->department)
                    ->get()->first();
                $designationName = DB::table('desg_details')
                    ->select('desg_name')
                    ->where('id', $request->designation)
                    ->get()->first();
                $officeTypeName = DB::table('asset_master_office_types')
                    ->select('office_type_desc')
                    ->where('office_type_cd', $request->office_type)
                    ->get()->first();
                $officeName = DB::table('office_details')
                    ->select('office_name')
                    ->where('id', $request->office_list)
                    ->get()->first();
                $newOfficeDetail = [
                    'department' => $request->department,
                    'department_name' => $departmentName->department_name,
                    'designation' => $request->designation,
                    'designation_name' => $designationName->desg_name,
                    'office_type_cd' => $request->office_type,
                    'office_type_name' => $officeTypeName->office_type_desc,
                    'office' => $request->office_list,
                    'office_name' => $officeName->office_name,
                    'from' => $request->assign_date,
                    'to' => null,
                    'remarks' => null,
                    'appointment_type_cd' => 'T',
                    'appointment_type' => 'Temporary',
                    'status' => 'A',
                    'activity_status' => 'Active',
                    'data_entry' => $request->data_entry,
                    'created_by' => auth::user()->id,
                    'created_user_name' => auth::user()->name,
                ];

                $userAddUserDetails['office_details'][] = $newOfficeDetail;
                $newJsonData = json_encode($userAddUserDetails);
                $status = $user->update(['additional_office_details' => $newJsonData]);
                if ($status) {
                    return redirect()->back()
                        ->with('success', 'Additional office details added successfully.');
                } else {
                    return redirect()->back()
                        ->with('error', 'Failed to add education qualification!')
                        ->withInput();
                }
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
        }
    }

    public function changeAdditionalOfficeStatus(Request $request)
    {
        try {
            $indexValue = $request->index;
            $userId = $request->userid;
            $user = User::find($userId);
            if ($user) {
                $userAdditionalOfficeDetails = json_decode($user->additional_office_details, true);
                foreach ($userAdditionalOfficeDetails['office_details'] as $index => $item) {
                    if ($index == $indexValue) {
                        // Update the JSON data
                        $userAdditionalOfficeDetails['office_details'][$index]['remarks'] = 'Deactivated by the admin';
                        $userAdditionalOfficeDetails['office_details'][$index]['status'] = 'D';
                        $userAdditionalOfficeDetails['office_details'][$index]['activity_status'] = 'Deactive';
                        $userAdditionalOfficeDetails['office_details'][$index]['to'] = Carbon::now();
                        // Update the database
                        $user->additional_office_details = json_encode($userAdditionalOfficeDetails);
                        if ($user->save()) {
                            return response()->json([
                                'status' => 'success',
                                'message' => 'User deactivated succefully!',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Failed to deactivate the user!',
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User data not available due to some error!',
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }


    // List of Offices with no User Assigned -- Saiful -- Start
    public function misOfficesWithoutOfficer()
    {
        try {
            Log::info('Inside Function misOfficesWithoutOfficer()');
            DB::enableQueryLog();
            $user = Auth::user();
            $deptdetails = DepartmentDetail::all();
            $officeTypeDetails = AssetMasterOfficeType::all();
            $zoneDetails = AssetMasterZone::all();
            $circleDetails = AssetMasterCircle::all();
            $divisionDetails = AssetMasterDivision::all();
            $subDivisionDetails = AssetMasterSubDivision::all();
            $listOffices = DB::table('office_details as o')
                ->select(
                    "o.id",
                    "o.office_name",
                    "d.department_name",
                    "ot.office_type_desc",
                    "z.zone_name",
                    "c.circle_name",
                    "dv.division_name",
                    "sdv.sub_div_name"
                )
                ->leftJoin("department_details as d", "d.id", "=", "o.department_id")
                ->leftJoin("asset_master_office_types as ot", "ot.office_type_cd", "=", "o.office_type_cd")
                ->leftJoin("asset_master_zones as z", "z.zone_cd", "=", "o.zone_cd")
                ->leftJoin("asset_master_circles as c", "c.circle_cd", "=", "o.circle_cd")
                ->leftJoin("asset_master_divisions as dv", "dv.division_cd", "=", "o.division_cd")
                ->leftJoin("asset_master_sub_divisions as sdv", "sdv.sub_div_cd", "=", "o.sub_division_cd")
                ->whereNotIn('o.id', function ($query) {
                    $query->select(DB::raw('office'))
                        ->from('users');
                })
                ->orderBy("o.department_id")
                ->orderBy("o.office_type_cd")
                ->orderBy("o.zone_cd")
                ->orderBy("o.circle_cd")
                ->orderBy("o.division_cd")
                ->orderBy("o.sub_division_cd")
                ->get();

            $listOfUserWithAdditionalCharge = DB::table('office_details as o')
                ->select("o.id", "o.office_name", "u.additional_office_details")
                ->leftJoin("department_details as d", "d.id", "=", "o.department_id")
                ->leftJoin("asset_master_office_types as ot", "ot.office_type_cd", "=", "o.office_type_cd")
                ->leftJoin("users as u", "u.office", "=", "o.id")
                ->whereNotNull('u.additional_office_details')
                ->orderBy("o.department_id")
                ->orderBy("o.office_type_cd")
                ->orderBy("o.zone_cd")
                ->orderBy("o.circle_cd")
                ->orderBy("o.division_cd")
                ->orderBy("o.sub_division_cd")
                ->get();
            $query = DB::getQueryLog();
            Log::info($listOfUserWithAdditionalCharge);
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
        }

        return view(
            'mis.misOfficesWithoutOfficer',
            compact(
                'listOffices',
                'listOfUserWithAdditionalCharge',
                'deptdetails',
                'zoneDetails',
                'circleDetails',
                'divisionDetails',
                'subDivisionDetails'
            )
        );
    }
    // List of Offices with no User Assigned -- Saiful -- End
}
