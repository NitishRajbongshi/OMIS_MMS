<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DepartmentDetail;
use App\Helpers\MyHelper;
use App\Models\OfficeDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Road\AssetModificationRequestRoadAssets;
use App\Models\UserMenuDetail;
use App\Models\UserRoleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $deptname = DepartmentDetail::orderBy(DB::raw('SUBSTRING(department_name, 1, 1)'))->get();
        return view('auth.login', compact('deptname'));
    }

    /**
     * Generate dynamic CAPTCHA image and store the code in session.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateCaptcha()
    {
        $captchaValue = '';

        for ($i = 0; $i < 3; $i++) {
            // Generate a random digit (1 to 9)
            $digit = rand(1, 9);

            // Generate a random uppercase character
            $char = chr(rand(65, 90));

            // Concatenate the digit and character to the captchaValue
            $captchaValue .= $digit . $char;
        }

        session(['captcha' => $captchaValue]);

        $width = 130;
        $height = 46;
        $image = imagecreatetruecolor($width, $height);

        // Fill background with light gray/off-white color
        $bgColor = imagecolorallocate($image, 245, 247, 250);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Draw some grid/lines pattern for noise
        for ($i = 0; $i < 4; $i++) {
            $lineColor = imagecolorallocate($image, rand(160, 210), rand(160, 210), rand(160, 230));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Draw some random dots
        for ($i = 0; $i < 100; $i++) {
            $dotColor = imagecolorallocate($image, rand(120, 190), rand(120, 190), rand(120, 210));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
        }

        // Draw the text characters
        $len = strlen($captchaValue);
        for ($i = 0; $i < $len; $i++) {
            $char = $captchaValue[$i];
            // Dark color for text
            $textColor = imagecolorallocate($image, rand(10, 80), rand(10, 80), rand(10, 80));

            // Random positioning slightly
            $x = 15 + ($i * 18);
            $y = rand(12, 18);

            // imagestring (resource, font, x, y, string, color)
            // font = 5 is the largest built-in font
            imagestring($image, 5, $x, $y, $char, $textColor);
        }

        // Add a subtle border
        $borderColor = imagecolorallocate($image, 220, 224, 230);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

        // Clean output buffer and stream the image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->validate([
            'captcha' => [
                'required',
                'alpha_num',
                function ($attribute, $value, $fail) {
                    if (strtoupper($value) !== strtoupper(session('captcha'))) {
                        $fail('The security code is incorrect.');
                    }
                },
            ],
        ]);

        session()->forget('captcha');

        $request->authenticate();
        $request->session()->regenerate();
        $ipAddress = $request->ip();
        /** @var User $user */
        $user = Auth::user();
        $userId = $user->id;
        $loginTime = now();
        MyHelper::addLoginLog($userId, $ipAddress, $loginTime);

        $userName = $user->name;
        $userRoleId = $user->user_role_id;
        $officeType = $user->office_type_cd;
        $office = $user->office;
        $userDeptCd = $user->department;
        $userDepartment = DepartmentDetail::select('department_name')->where('id', $userDeptCd)->get()->first();
        $userOffice = OfficeDetail::select('office_name')->where('id', $office)->get()->first();

        // get additional office details
        $addOfficeDetails = json_decode($user->additional_office_details, true);
        $additionalOfficeDetails = []; // array to store all additional office details
        $allUserOffices = []; // all offices list as office_cd => office_name, permament and additional 
        $allUserOffices[$user->office] = $userOffice->office_name;
        if (!empty($addOfficeDetails)) {
            foreach ($addOfficeDetails['office_details'] as $office) {
                $additionalOfficeDetails[] = [
                    'office' => $office['office'],
                    'office_name' => $office['office_name'],
                    'department' => $office['department'],
                    'department_name' => $office['department_name'],
                    'designation' => $office['designation'],
                    'designation_name' => $office['designation_name'],
                    'office_type_cd' => $office['office_type_cd'],
                    'office_type_name' => $office['office_type_name'],
                    'data_entry' => $office['data_entry'],
                ];

                $allUserOffices[$office['office']] = $office['office_name'];
            }
        }
        // get the json data for the user
        $userAdditionalOfficeDetails = json_decode($user->additional_office_details, true);
        // check for additional office details
        if (!empty($userAdditionalOfficeDetails['office_details'])) {
            foreach ($userAdditionalOfficeDetails['office_details'] as $index => $item) {
                // check for temporary office details
                if (($item['appointment_type_cd'] == 'T') && ($item['status'] == 'A')) {
                    $departmentCd = $item['department'];
                    $desgCd = $item['designation'];
                    $officeCd = $item['office'];

                    if (DB::table('users')->where('department', $departmentCd)->where('office', $officeCd)->where('designation', $desgCd)->exists()) {
                        // Update the JSON data
                        $userAdditionalOfficeDetails['office_details'][$index]['remarks'] = 'Deactivated due to the appointment of a permanent employee';
                        $userAdditionalOfficeDetails['office_details'][$index]['status'] = 'D';
                        $userAdditionalOfficeDetails['office_details'][$index]['activity_status'] = 'Deactive';
                        $userAdditionalOfficeDetails['office_details'][$index]['to'] = Carbon::now();
                        // Update the database
                        $user->additional_office_details = json_encode($userAdditionalOfficeDetails);
                        $user->save();
                    }
                }
            }
        }

        $userRoleDetails = DB::table('user_role_details')
            ->select('role_id')
            ->where('user_role_details.user_id', $userId)
            ->get();
        Log::info($userRoleDetails);
        $admin_user = false;
        foreach ($userRoleDetails as $role) {
            if ($role->role_id == 1) {
                Log::info('Admin User');
                $admin_user = true;
                break;
            }
        }

        $roleData = DB::table('role_details')
            ->leftJoin('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
            ->select('role_details.*')
            ->where('user_role_details.user_id', $userId)
            ->get();
        Log::info($roleData);
        $inserted = 0;
        $viewed = 0;
        $deleted = 0;
        $updated = 0;
        $finalised = 0;
        $uploadFile = 0;
        $can_req_modify = 0;
        $can_aprv_modify_req = 0;
        $dataEntry = 0;
        $approveRoadSubAsset = 0;
        foreach ($roleData as $item) {
            if ($item->inserted == 1)
                $inserted = 1;
            if ($item->viewed == 1)
                $viewed = 1;
            if ($item->deleted == 1)
                $deleted = 1;
            if ($item->updated == 1)
                $updated = 1;
            if ($item->freeze_data == 1)
                $finalised = 1;
            if ($item->upload_file == 1)
                $uploadFile = 1;
            if ($item->req_modify == 1)
                $can_req_modify = 1;
            if ($item->approve_req_modify == 1)
                $can_aprv_modify_req = 1;
            if ($item->id == 32)
                $approveRoadSubAsset = 1;
        }

        $roadReqForUpdate = AssetModificationRequestRoadAssets::select('requested_by')
            ->where('modification_request_status', 'N')
            ->get()
            ->count();

        $menus = UserMenuDetail::select('menuid')->where('userid', $userId)->get();
        $menu = $menus->pluck('menuid')->toArray();
        foreach ($menu as $item) {
            if ($item === 13)
                $dataEntry = 1;
        }
        $userMapping = DB::table('asset_user_mappings')
            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd', 'office_type_cd', 'office_cd')
            ->where('user_id', '=', $userId)
            ->get()->first();


        $assigedUserRoles = UserRoleDetail::select('role_id')->where('user_id', $userId)->get();
        $arrUserRoles = $assigedUserRoles->pluck('role_id')->toArray();

        session([
            'userId' => $userId,
            'userName' => $userName,
            'officeType' => $officeType,
            'inserted' => $inserted,
            'viewed' => $viewed,
            'deleted' => $deleted,
            'updated' => $updated,
            'finalised' => $finalised,
            'approveRoadSubAsset' => $approveRoadSubAsset,
            'uploadFile' => $uploadFile,
            'roadReqForUpdate' => $roadReqForUpdate,
            'menu' => $menu,
            'userMapping' => $userMapping,
            'userRoleId' => $userRoleId,
            'roleData' => $roleData,
            'user_role_ids' => $arrUserRoles,
            'can_req_modify' => $can_req_modify,
            'can_aprv_modify_req' => $can_aprv_modify_req,
            'user_dept_cd' => $userDeptCd,
            'users_office_type_cd' => $userMapping->office_type_cd,
            'department' => $userDepartment->department_name,
            'office' => $userOffice->office_name,
            'office_cd' => $user->office,
            'dataEntry' => $dataEntry,
            'active_office' => $user->office,
            'additional_office_details' => $additionalOfficeDetails,
            'office_charge_type' => 0,
            'officeList' => $allUserOffices,
            'adminUser' => $admin_user,
        ]);
        // Log::info('User Role Id: ' . session('userRoleId'));
        Log::info('Admin: ' . session('adminUser'));

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $U_id = Auth::user()->id;
        $ipAddress = $request->ip();
        $loginTime = now();
        MyHelper::addLogoutLog($U_id, $ipAddress, $loginTime);

        Auth::logout();
        Session::forget('admin');
        Session::flush();
        Session::regenerate();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
