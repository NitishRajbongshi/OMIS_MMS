<?php

namespace App\Http\Controllers\finalize_data;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinalizedDataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function freezeSingleRoadData(Request $request)
    {
        DB::beginTransaction();
        try {
            $roadID = $request->id;
            $approvedBy = Auth::user()->id;
            $currentTime = now();
            $division_cd = null;
            $roadinfos = DB::table('asset_road_details_draft')
                ->select(
                    // 'division_cd',
                    // 'asset_plan_id' //Saiful --20-04-2026
                    'asset_road_details_draft.*'
                )
                ->where('rd_system_id', '=', $roadID)
                ->get()->first();
            if ($roadinfos)
                $division_cd = $roadinfos->division_cd;
            //saiful --24-04-2026 -- Start
            //check if the road is associated with any project, and Same road is exist in the main table, then it means it is redifine 
            //first we take a back up of the road data into asset_road_details_hist table
            // next we Update the Draft Table data with main table data
            //then only we move the data from draft to main table, otherwise if the road is not associated with any project, then we directly move the data from draft to main table
            $roadinfosFromMainTable = DB::table('asset_road_details')
                ->select(
                    'rd_system_id',
                    'division_cd',
                    'asset_plan_id'
                )
                ->where('rd_system_id', '=', $roadID)
                ->get()->first();
            if ($roadinfos && $roadinfos->asset_plan_id && $roadinfosFromMainTable) {
                $statusCopyToHist = DB::table('public.asset_road_details_hist')->insertUsing([
                    'rd_system_id',
                    'rd_category_cd',
                    'rd_number',
                    'rd_name',
                    'rd_type_cd',
                    'road_length',
                    'rd_owner_cd',
                    'road_created_at_office_type',
                    'road_created_at_office_cd',
                    'road_type',
                    'district_name',
                    'block_name',
                    'lng',
                    'lat',
                    'division_name',
                    'division_cd',
                    'block_cd',
                    'district_cd',
                    'included_in_core_network',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'approved_by',
                    'approved_at',
                    'is_road_data_merged_to_all_state_file',
                    'is_road_data_merged_to_division_file',
                    'state_data_merged_on',
                    'division_data_merged_on',
                    'hist_created_by',
                    'hist_created_at',
                    'asset_plan_id' //Saiful --20-04-2026
                ], function ($query) use ($roadID, $currentTime, $approvedBy) {
                    $query->from('public.asset_road_details')
                        ->where('rd_system_id', '=', $roadID)
                        ->select(
                            'rd_system_id',
                            'rd_category_cd',
                            'rd_number',
                            'rd_name',
                            'rd_type_cd',
                            'road_length',
                            'rd_owner_cd',
                            'road_created_at_office_type',
                            'road_created_at_office_cd',
                            'road_type',
                            'district_name',
                            'block_name',
                            'lng',
                            'lat',
                            'division_name',
                            'division_cd',
                            'block_cd',
                            'district_cd',
                            'included_in_core_network',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'approved_by',
                            'approved_at',
                            'is_road_data_merged_to_all_state_file',
                            'is_road_data_merged_to_division_file',
                            'state_data_merged_on',
                            'division_data_merged_on',
                            DB::raw("'$approvedBy' as hist_created_by"),
                            DB::raw("'$currentTime' as hist_created_at"),
                            'asset_plan_id'
                        );
                });
                if ($roadinfos && $statusCopyToHist) {
                    $status = DB::table('public.asset_road_details')
                        ->where('rd_system_id', '=', $roadID)
                        ->update([
                            'rd_category_cd' => $roadinfos->rd_category_cd,
                            'rd_name' => $roadinfos->rd_name,
                            'rd_type_cd' => $roadinfos->rd_type_cd,
                            'road_length' => $roadinfos->road_length,
                            'rd_owner_cd' => $roadinfos->rd_owner_cd,
                            'road_type' => $roadinfos->road_type,
                            'updated_at' => now(),
                            'updated_by' => auth()->id(),
                            'approved_by' => $approvedBy,
                            'approved_at' => $currentTime,
                            'asset_plan_id' => $roadinfos->asset_plan_id
                        ]);
                }

            } else {

                //saiful --24-04-2026 -- End
                $status = DB::table('public.asset_road_details')->insertUsing([
                    'rd_system_id',
                    'rd_category_cd',
                    'rd_number',
                    'rd_name',
                    'rd_type_cd',
                    'road_length',
                    'rd_owner_cd',
                    'road_created_at_office_type',
                    'road_created_at_office_cd',
                    'road_type',
                    'lng',
                    'lat',
                    'division_name',
                    'division_cd',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'approved_by',
                    'approved_at',
                    'asset_plan_id' //Saiful --20-04-2026
                ], function ($query) use ($roadID, $currentTime, $approvedBy) {
                    $query->from('public.asset_road_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_system_id',
                            'rd_category_cd',
                            'rd_number',
                            'rd_name',
                            'rd_type_cd',
                            'road_length',
                            'rd_owner_cd',
                            'road_created_at_office_type',
                            'road_created_at_office_cd',
                            'road_type',
                            'lng',
                            'lat',
                            'division_name',
                            'division_cd',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at"),
                            'asset_plan_id' //Saiful --20-04-2026
                        );
                });
            }
            if ($status > 0) {
                Log::info("Move successfully the Draft Data of Road : " . $roadID . " into Main Table");
                try {
                    DB::table('public.asset_road_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    DB::commit();
                    Log::info("Deleted the Draft Road Data : " . $roadID);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Selected Road ' . $roadID . ' is Successfully Finalized/Approved!!!'
                    ]);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error("DB Rolled Back!");
                    Log::error("message: " . $e->getMessage());
                    return response()->json([
                        'status' => 500,
                        'message' => 'Internal server error!'
                    ]);
                }
            } else {
                Log::info("Could Not Move the Draft Data of Road : " . $roadID . " into Main Table");
                DB::rollBack();
                return response()->json([
                    'status' => 204,
                    'message' => 'Data not available to finalize!'
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function moveApprovedRoadToDraftRoad(Request $request)
    {
        $roadId = $request->road_id;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            Log::info("Moving approved Data to Draft table");
            $status = DB::table('public.asset_road_details_draft')->insertUsing([
                'rd_system_id',
                'rd_category_cd',
                'rd_number',
                'rd_name',
                'rd_type_cd',
                'road_length',
                'rd_owner_cd',
                'road_created_at_office_type',
                'road_created_at_office_cd',
                'road_type',
                'lng',
                'lat',
                'division_name',
                'division_cd',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'sent_for_finalize',
                'sent_for_finalize_on',
                'sent_for_finalize_by',
                'is_rejected'
            ], function ($query) use ($roadId) {
                $query->from('public.asset_road_details')
                    ->where('rd_system_id', '=', $roadId)
                    ->select(
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        DB::raw("'Y' as sent_for_finalize"),
                        'created_at',
                        'created_by',
                        DB::raw("'N' as is_rejected"),
                    );

                $query = DB::getQueryLog();
                Log::info($query);
            });

            if ($status > 0) {
                Log::info("Moved Aprroved Road to Draft table");
                DB::table('public.asset_road_details')
                    ->where('rd_system_id', '=', $roadId)
                    ->delete();
                DB::commit();
                Log::info("Aprroved Road Deleted from Main table");
                return response()->json([
                    'status' => True,
                    'message' => 'Road cannot be Approved at this moment for Road Id: ' . $roadId . ', Please Try Again After Some Time!!!'
                ]);
            }
            DB::rollBack();
            return response()->json([
                'status' => False,
                'message' => 'Could Not Revert Back the Finalised Data, Sorry!!!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }
    public function rejectSingleRoadData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->id;
                $reason_of_reject = $request->reason;
                $status = DB::table('public.asset_road_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('rd_system_id', $roadID)
                    ->update([
                        'is_rejected' => 'Y',
                        'reason_of_rejection' => $request->reason,
                        'date_of_rejection' => Carbon::now(),
                        'rejected_by' => Auth::user()->id,
                        'sent_for_finalize' => 'N',
                        'updated_at' => Carbon::now(),
                    ]);

                if ($status) {

                    return response()->json([
                        'status' => 200,
                        'message' => 'Selected road rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject road data!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }
}
