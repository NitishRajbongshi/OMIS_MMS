<?php

namespace App\Http\Controllers\PMS\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProjectPhysicalProgressController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $userDeptCd = $user->department;

            $userMapping = session('userMapping');
            $array = json_decode(json_encode($userMapping), true);
            $user_zone_cd = $array["zone_cd"];
            $user_circle_cd = $array["circle_cd"];
            $user_division_cd = $array["division_cd"];
            $user_sub_division_cd = $array["sub_division_cd"];
            $user_office_type_cd = $array["office_type_cd"];
            $user_office_cd = $array["office_cd"];

            $itemProgress = DB::table('prt_project_details as p')
                ->leftJoin('prt_project_work_items_details as w', 'w.project_cd', '=', 'p.project_cd')

                ->leftJoin('prt_project_progress_details_work_item_wise as pr', function ($join) {
                    $join->on('pr.item_id', '=', 'w.id')
                        ->where('pr.status', '=', 'A');
                })
                ->select(
                    'p.project_cd',
                    DB::raw('
                            CASE 
                                WHEN w.quantity > 0 
                                THEN (COALESCE(SUM(pr.quantity_done),0)/w.quantity)*100
                                ELSE 0
                            END as item_progress
                        ')
                )
                ->where('p.is_published', "Y")
                ->groupBy('p.project_cd', 'p.project_name', 'w.id', 'w.quantity');

            $query = DB::query()
                ->fromSub($itemProgress, 't')
                ->join('prt_project_details as p', 'p.project_cd', '=', 't.project_cd')
                ->leftjoin('prm_project_types as pt', 'p.project_type_cd', '=', 'pt.proj_type_cd')
                ->leftjoin('asset_master_divisions as d', 'p.division_cd', '=', 'd.division_cd')
                ->leftjoin('asset_master_sub_divisions as sd', 'p.sub_division_cd', '=', 'sd.sub_div_cd')
                ->select(
                    'p.project_cd',
                    'p.project_name',
                    'p.project_name',
                    'p.project_start_date',
                    'p.project_end_date',
                    'd.division_name',
                    'sd.sub_div_name',
                    'pt.proj_type_descr',
                    DB::raw('
                                CASE 
                                    WHEN MIN(t.item_progress) = 100 THEN 100
                                    ELSE ROUND(AVG(t.item_progress),2)
                                END as progress_percent
                            ')
                )
                ->groupBy('p.project_cd', 'p.project_name', 'pt.proj_type_descr', 'd.division_name', 'sd.sub_div_name', 'project_start_date', 'project_end_date');


            # Apply office hierarchy filter
            // if ($user_office_type_cd == "SDO") {
            //     $query->where('p.sub_division_cd', $user_sub_division_cd);
            // }

            // if ($user_office_type_cd == "DO") {
            //     $query->where('p.division_cd', $user_division_cd);
            // }

            // if ($user_office_type_cd == "CO") {
            //     $query->where('div.circle_cd', $user_circle_cd);
            // }

            // if ($user_office_type_cd == "ZO") {
            //     $query->where('div.zone_cd', $user_zone_cd);
            // }

            $project_list = $query->get();

            return view("pms.progress.reportPhysicalProgress", compact('project_list'));
        } catch (Exception $e) {
            Log::error("Error: ", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function getProgressDetailsItemWise($project_cd)
    {
        try {
            $data = DB::table('prt_project_work_items_details as w')
                ->leftJoin('prm_item_of_work as i', 'i.item_cd', '=', 'w.item_cd')
                ->leftJoin('prt_project_iow_boq_mapping as b', 'b.iow_id', '=', 'w.id')
                ->leftJoin('prm_boq_items as q', 'q.boq_item_id', '=', 'b.boq_item_id')
                ->leftJoin('prt_project_progress_details_work_item_wise as pr', 'pr.item_id', '=', 'w.id')
                ->leftJoin('prm_item_units as u1', 'u1.unit_cd', '=', 'i.unit_cd')
                ->leftJoin('prm_item_units as u2', 'u2.unit_cd', '=', 'q.unit_cd')
                ->select(
                    'i.item_name',
                    'q.boq_item_name',
                    'w.quantity',
                    'u1.unit_cd as iow_unit',
                    'u2.unit_cd as boq_unit',
                    DB::raw("
                                COALESCE(SUM(
                                    CASE 
                                        WHEN pr.status = 'A' THEN pr.quantity_done 
                                        ELSE 0 
                                    END
                                ),0) as quantity_done
                            "),

                    DB::raw("
                                COALESCE(SUM(
                                    CASE 
                                        WHEN pr.status = 'A' THEN pr.boq_quantity_done 
                                        ELSE 0 
                                    END
                                ),0) as boq_quantity_done
                            "),

                    DB::raw("
                                CASE 
                                WHEN w.quantity > 0 
                                THEN ROUND((
                                    COALESCE(SUM(
                                        CASE 
                                            WHEN pr.status = 'A' THEN pr.quantity_done 
                                            ELSE 0 
                                        END
                                    ),0) / w.quantity
                                ) * 100, 2)
                                ELSE 0
                            END as progress_percent
                        ")
                )

                ->where('w.project_cd', $project_cd)
                ->groupBy(
                    'i.item_name',
                    'q.boq_item_name',
                    'w.quantity',
                    'u1.unit_cd',
                    'u2.unit_cd'
                )
                ->get();
        } catch (Exception $e) {
            Log::error("Error: ", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
        return response()->json($data);

    }

    public function getProjectGantt($project_cd)
    {
        try {
            $items = DB::table('projects.prt_project_work_items_details as work_item')
                ->leftJoin('projects.prm_item_of_work as item', 'item.item_cd', '=', 'work_item.item_cd')
                ->leftJoin('projects.prm_item_units as unit', 'unit.unit_cd', '=', 'item.unit_cd')
                ->select(
                    'work_item.id',
                    'item.item_name',
                    'work_item.est_start_date as start_date',
                    'work_item.est_end_date as end_date',
                    'work_item.quantity',
                    'unit.unit_cd as unit'
                )
                ->where('work_item.project_cd', $project_cd)
                ->whereNotNull('work_item.est_start_date')
                ->whereNotNull('work_item.est_end_date')
                ->orderBy('work_item.est_start_date')
                ->orderBy('work_item.id')
                ->get();

            return response()->json($items);
        } catch (Exception $e) {
            Log::error('Unable to load project Gantt data: '.$e->getMessage(), [
                'project_cd' => $project_cd,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['message' => 'Unable to load the project schedule.'], 500);
        }
    }
}
