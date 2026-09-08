<?php

namespace App\Http\Controllers\RoadChainage;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterCircle;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetRoadChainageMapping;
use GuzzleHttp\Client;
use App\Models\Road\AssetRoadDocumentKmlFileDetails;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RoadChainageController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
    }

    public function index()
    {   
        Log::info("Inside Index method in RoadChainageController to load List of pending Roads for Create Chainage");
        try{
            $user = Auth::user();
            $office = $user->office_type_cd;
            $roadDetails = [];
            $chainageOffices = [];
            // get current user asset mapping details
            $userMappingDetails = DB::table('asset_user_mappings')
                ->select('*')
                ->where('user_id', '=', $user->id)
                ->get()
                ->first();

            // if ($user->department == '14') {
            //     // show offices for HQ level
            //     if ($office == 'HQ') {
            //         $roadDetails = DB::table('asset_road_details')
            //             ->select('*')
            //             ->whereNotIn('asset_road_details.rd_system_id', (function ($query) {
            //                 $query->from('asset_road_chainage_mappings')
            //                     ->select('rd_system_id')
            //                     ->whereNotNull('asset_road_chainage_mappings.zone_cd')
            //                     ->where('asset_road_chainage_mappings.remaining_chainage_length', '=', 0);
            //             }))
            //             ->where('road_created_at_office_cd', $user->office)
            //             // ->orWhere('road_created_at_office_type', $user->office_type_cd)
            //             ->get();
                    


            //         $chainageOffices = DB::table('office_details')
            //             ->select('office_details.id', 'office_details.office_name', 'asset_master_zones.zone_cd', 'asset_master_zones.zone_name')
            //             ->join('asset_master_zones', 'office_details.zone_cd', '=', 'asset_master_zones.zone_cd')
            //             ->where('office_details.office_type_cd', '=', 'ZO')
            //             ->where('department_id', Auth::user()->department)
            //             ->get();
            //     }
            //     // show offices for ZO level
            //     if ($office == 'ZO') {
            //         $zoneCode = $userMappingDetails->zone_cd;

            //         $roadDetails = DB::table('asset_road_chainage_mappings')
            //             ->select('asset_road_chainage_mappings.*', 'asset_road_chainage_mappings.chainage_from', 
            //             'asset_road_chainage_mappings.chainage_to', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 
            //             'asset_road_details.road_length', 'asset_road_details.road_type')
            //             ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
            //             ->whereIn('asset_road_chainage_mappings.rd_system_id', function ($query) use ($zoneCode) {
            //                 $query->select(DB::raw('DISTINCT rd_system_id'))
            //                     ->from('public.asset_road_chainage_mappings')
            //                     ->where(function ($subquery) use ($zoneCode) {
            //                         $subquery->where('zone_cd', $zoneCode)
            //                             ->orWhereNotNull('zone_cd')
            //                             ->orWhere('remaining_chainage_length', 0);
            //                     })
            //                     ->whereNotExists(function ($innerQuery) use ($zoneCode) {
            //                         $innerQuery->select(DB::raw(1))
            //                             ->from('public.asset_road_chainage_mappings AS inner_mapping')
            //                             ->whereRaw('inner_mapping.rd_system_id = asset_road_chainage_mappings.rd_system_id')
            //                             ->where('inner_mapping.zone_cd', $zoneCode)
            //                             ->whereNotNull('inner_mapping.circle_cd')
            //                             ->where('inner_mapping.remaining_chainage_length', 0);
            //                     });
            //             })
            //             ->where('asset_road_chainage_mappings.zone_cd', $zoneCode)
            //             ->where('asset_road_chainage_mappings.circle_cd', null)
            //             // ->where('chainage_created_at_office_cd', 4)
            //             ->get();

            //         $chainageOffices = DB::table('office_details')
            //             ->select('office_details.id', 'office_details.office_name', 'asset_master_circles.zone_cd', 'asset_master_circles.circle_cd', 'asset_master_circles.circle_name')
            //             ->join('asset_master_circles', 'office_details.circle_cd', '=', 'asset_master_circles.circle_cd')
            //             ->where('office_details.office_type_cd', '=', 'CO')
            //             ->where('office_details.zone_cd', '=', $userMappingDetails->zone_cd)
            //             ->get();
            //     }

            //     // show offices for CO level
            //     if ($office == 'CO') {
            //         $circleCode = $userMappingDetails->circle_cd;

            //         $roadDetails = DB::table('asset_road_chainage_mappings')
            //             ->select('asset_road_chainage_mappings.*', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_road_details.road_type')
            //             ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
            //             ->whereIn('asset_road_chainage_mappings.rd_system_id', function ($query) use ($circleCode) {
            //                 $query->select(DB::raw('DISTINCT rd_system_id'))
            //                     ->from('public.asset_road_chainage_mappings')
            //                     ->where(function ($subquery) use ($circleCode) {
            //                         $subquery->where('circle_cd', $circleCode)
            //                             ->orWhereNotNull('zone_cd')
            //                             ->orWhere('remaining_chainage_length', 0);
            //                     })
            //                     ->whereNotExists(function ($innerQuery) use ($circleCode) {
            //                         $innerQuery->select(DB::raw(1))
            //                             ->from('public.asset_road_chainage_mappings AS inner_mapping')
            //                             ->whereRaw('inner_mapping.rd_system_id = asset_road_chainage_mappings.rd_system_id')
            //                             ->where('inner_mapping.circle_cd', $circleCode)
            //                             ->whereNotNull('inner_mapping.division_cd')
            //                             ->where('inner_mapping.remaining_chainage_length', 0);
            //                     });
            //             })
            //             ->where('asset_road_chainage_mappings.circle_cd', $circleCode)
            //             ->where('asset_road_chainage_mappings.division_cd', null)
            //             ->get();


            //         $chainageOffices = DB::table('office_details')
            //             ->select('office_details.id', 'office_details.office_name', 'asset_master_divisions.zone_cd', 'asset_master_divisions.circle_cd', 'asset_master_divisions.division_cd', 'asset_master_divisions.division_name')
            //             ->join('asset_master_divisions', 'office_details.division_cd', '=', 'asset_master_divisions.division_cd')
            //             ->where('office_details.office_type_cd', '=', 'DO')
            //             ->where('office_details.circle_cd', '=', $userMappingDetails->circle_cd)
            //             ->where('office_details.zone_cd', '=', $userMappingDetails->zone_cd)
            //             ->get();
            //     }
            //     // show offices for DO level
            //     if ($office == 'DO') {
            //         $chainageOffices = DB::table('office_details')
            //             ->select('office_details.id', 'office_details.office_name', 'asset_master_sub_divisions.zone_cd', 'asset_master_sub_divisions.circle_cd', 'asset_master_sub_divisions.div_cd', 'asset_master_sub_divisions.sub_div_cd', 'asset_master_sub_divisions.sub_div_name')
            //             ->join('asset_master_sub_divisions', 'office_details.sub_division_cd', '=', 'asset_master_sub_divisions.sub_div_cd')
            //             ->where('office_details.office_type_cd', '=', 'SDO')
            //             ->where('office_details.circle_cd', '=', $userMappingDetails->circle_cd)
            //             ->where('office_details.zone_cd', '=', $userMappingDetails->zone_cd)
            //             ->where('office_details.division_cd', '=', $userMappingDetails->division_cd)
            //             ->get();

            //         $divisionCode = $userMappingDetails->division_cd;

            //         $roadDetails = DB::table('asset_road_chainage_mappings')
            //             ->select('asset_road_chainage_mappings.*', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_road_details.road_type')
            //             ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
            //             ->whereIn('asset_road_chainage_mappings.rd_system_id', function ($query) use ($divisionCode) {
            //                 $query->select(DB::raw('DISTINCT rd_system_id'))
            //                     ->from('public.asset_road_chainage_mappings')
            //                     ->where(function ($subquery) use ($divisionCode) {
            //                         $subquery->where('division_cd', $divisionCode)
            //                             ->orWhereNotNull('circle_cd')
            //                             ->orWhere('remaining_chainage_length', 0);
            //                     })
            //                     ->whereNotExists(function ($innerQuery) use ($divisionCode) {
            //                         $innerQuery->select(DB::raw(1))
            //                             ->from('public.asset_road_chainage_mappings AS inner_mapping')
            //                             ->whereRaw('inner_mapping.rd_system_id = asset_road_chainage_mappings.rd_system_id')
            //                             ->where('inner_mapping.division_cd', $divisionCode)
            //                             ->whereNotNull('inner_mapping.sub_division_cd')
            //                             ->where('inner_mapping.remaining_chainage_length', 0);
            //                     });
            //             })
            //             ->where('asset_road_chainage_mappings.division_cd', $divisionCode)
            //             ->where('asset_road_chainage_mappings.sub_division_cd', null)
            //             // ->where('chainage_created_at_office_cd', '<>', $user->office)
            //             ->get();
            //     }
            // }

            // if ($user->department == '3') {
                Log::info("Chek0");
                // show offices for HQ level
                $road_type = "SR";
                if ($user->department == '3')
                    $road_type = "NH";
                Log::info("Loading " . $road_type . " Pending Roads to Create Chainage");
                $myOfficesDetails = DB::table('office_details as ofs')
                                ->select('ofs.id', 'ofs.office_name', 'ofs.zone_cd', 'ofs.circle_cd', 'ofs.division_cd', 'ofs.sub_division_cd'
                                ,'zn.zone_name', 'crl.circle_name', 'dv.division_name', 'sdv.sub_div_name')
                                ->leftJoin('asset_master_zones as zn', 'ofs.zone_cd', '=', 'zn.zone_cd')
                                ->leftJoin('asset_master_circles as crl', 'ofs.circle_cd', '=', 'crl.circle_cd')
                                ->leftJoin('asset_master_divisions as dv', 'ofs.division_cd', '=', 'dv.division_cd')
                                ->leftJoin('asset_master_sub_divisions as sdv', 'ofs.sub_division_cd', '=', 'sdv.sub_div_cd')
                                ->where('ofs.office_type_cd', '=', $office)
                                ->where('ofs.id', Auth::user()->office)
                                ->where('department_id', Auth::user()->department)
                                ->get()->first();

                    
                if ($office == 'HQ') {
                    // $roadDetails = DB::table('asset_road_details')
                    //     ->select('*')
                    //     ->where('asset_road_details.road_type', $road_type)
                    //     ->whereNotIn('asset_road_details.rd_system_id', (function ($query) {
                    //         $query->from('asset_road_chainage_mappings as chng')
                    //             ->select('rd_system_id')
                    //             ->whereNull('chng.zone_cd')
                    //             ->whereNull('chng.circle_cd')
                    //             ->whereNull('chng.division_cd')
                    //             ->whereNull('chng.sub_division_cd')
                    //             ->where('chng.remaining_chainage_length', '=', 0)
                    //             ->where('chng.is_chainage_completed_for_down_level', "Y");
                                
                    //     }))
                    //     ->where('road_created_at_office_cd', $user->office)
                    //     // ->orWhere('road_created_at_office_type', $user->office_type_cd)
                    //     ->get();
                    Log::info("Loading Pending Roads at HQ level to Create Chainage");
                    $roadDetails =  DB::table('asset_road_chainage_mappings as chng')
                                    ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                                    'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr', 'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                                    'dv.division_name', 'sdv.sub_div_name')
                                    ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                                    ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                                    ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                                    ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                                    ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                                    ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                                    ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                                    // ->whereIn('chng.rd_system_id', function ($query) use ($zoneCode) {
                                    //     $query->select(DB::raw('DISTINCT rd_system_id'))
                                    //         ->from('public.asset_road_chainage_mappings')
                                    //         ->where(function ($subquery) use ($zoneCode) {
                                    //             $subquery->where('zone_cd', $zoneCode)
                                    //                 ->orWhereNotNull('zone_cd')
                                    //                 ->orWhere('remaining_chainage_length', 0);
                                    //         })
                                    //         ;
                                    // })
                                    ->where('chng.is_chainage_completed_for_down_level', "N")
                                    ->where('chng.zone_cd', null)
                                    ->where('chng.circle_cd', null)
                                    ->where('chng.division_cd', null)
                                    ->where('chng.sub_division_cd', null)
                                    ->where('rd.road_type', $road_type)
                                    // ->where('chainage_created_at_office_cd', 4)
                                    ->get();


                    Log::info("Total Rec: " . collect($roadDetails)->count());
                    $chainageOffices = DB::table('office_details')
                        ->select('office_details.id', 'office_details.office_name', 'asset_master_zones.zone_cd', 'asset_master_zones.zone_name')
                        ->join('asset_master_zones', 'office_details.zone_cd', '=', 'asset_master_zones.zone_cd')
                        ->where('office_details.office_type_cd', '=', 'ZO')
                        ->where('department_id', Auth::user()->department)
                        ->get();
                }
                // show offices for ZO level
                if ($office == 'ZO') {
                    Log::info("Loading Pending Roads at Zone level to Create Chainage");
                    $zoneCode = $myOfficesDetails->zone_cd;

                    $roadDetails =  DB::table('asset_road_chainage_mappings as chng')
                    ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                    'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr', 'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                    'dv.division_name', 'sdv.sub_div_name')
                    ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                    ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                    ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                    ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                    ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                    ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                    ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                    ->whereIn('chng.rd_system_id', function ($query) use ($zoneCode) {
                        $query->select(DB::raw('DISTINCT rd_system_id'))
                            ->from('public.asset_road_chainage_mappings')
                            ->where(function ($subquery) use ($zoneCode) {
                                $subquery->where('zone_cd', $zoneCode)
                                    ->orWhereNotNull('zone_cd')
                                    ->orWhere('remaining_chainage_length', 0);
                            })
                            ;
                    })
                    ->where('chng.is_chainage_completed_for_down_level', "N")
                    ->where('chng.zone_cd', $zoneCode)
                    ->where('chng.circle_cd', null)
                    ->where('chng.division_cd', null)
                    ->where('chng.sub_division_cd', null)
                    ->where('rd.road_type', $road_type)
                    // ->where('chainage_created_at_office_cd', 4)
                    ->get();

                    $chainageOffices = DB::table('office_details')
                        ->select('office_details.id', 'office_details.office_name', 'asset_master_circles.zone_cd', 'asset_master_circles.circle_cd', 'asset_master_circles.circle_name')
                        ->join('asset_master_circles', 'office_details.circle_cd', '=', 'asset_master_circles.circle_cd')
                        ->where('office_details.office_type_cd', '=', 'CO')
                        ->where('office_details.zone_cd', '=', $userMappingDetails->zone_cd)
                        ->get();
                }

                // show offices for CO level
                if ($office == 'CO') {
                    Log::info("Loading Pending Roads at Circle level to Create Chainage");
                    $zoneCode = $myOfficesDetails->zone_cd;
                    $circleCode = $myOfficesDetails->circle_cd;
                    Log::info("My Circle Code :". $circleCode);
                    $roadDetails = DB::table('asset_road_chainage_mappings as chng')
                            ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                            'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr', 'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                            'dv.division_name', 'sdv.sub_div_name')
                            ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                            ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                            ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                            ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                            ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                            ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                            ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                            ->whereIn('chng.rd_system_id', function ($query) use ($circleCode) {
                                        $query->select(DB::raw('DISTINCT rd_system_id'))
                                            ->from('public.asset_road_chainage_mappings')
                                            ->where(function ($subquery) use ($circleCode) {
                                                $subquery->where('circle_cd', $circleCode)
                                                    ->orWhereNotNull('zone_cd')
                                                    ->orWhere('remaining_chainage_length', 0);
                                            })
                                        
                                            // ->whereNotExists(function ($innerQuery) use ($circleCode) {
                                            //     $innerQuery->select(DB::raw(1))
                                            //         ->from('public.asset_road_chainage_mappings AS inner_mapping')
                                            //         ->whereRaw('inner_mapping.rd_system_id = asset_road_chainage_mappings.rd_system_id')
                                            //         ->where('inner_mapping.circle_cd', $circleCode)
                                            //         ->whereNotNull('inner_mapping.division_cd')
                                            //         ->where('inner_mapping.remaining_chainage_length', 0);
                                            // })
                                            ;
                                        })
                            ->where('chng.is_chainage_completed_for_down_level', "N")
                            ->where('chng.zone_cd', $zoneCode)
                            ->where('chng.circle_cd', $circleCode)
                            ->where('chng.division_cd', null)
                            ->where('chng.sub_division_cd', null)
                            ->where('rd.road_type', $road_type)
                            ->get();


                    $chainageOffices = DB::table('office_details')
                        ->select('office_details.id', 'office_details.office_name', 'asset_master_divisions.zone_cd', 'asset_master_divisions.circle_cd', 'asset_master_divisions.division_cd', 'asset_master_divisions.division_name')
                        ->join('asset_master_divisions', 'office_details.division_cd', '=', 'asset_master_divisions.division_cd')
                        ->where('office_details.office_type_cd', '=', 'DO')
                        ->where('office_details.circle_cd', '=', $myOfficesDetails->circle_cd)
                        ->where('office_details.zone_cd', '=', $myOfficesDetails->zone_cd)
                        ->get();
                }
                // show offices for DO level
                if ($office == 'DO') {
                    Log::info("Loading Pending Roads at Division level to Create Chainage");
                    $zoneCode = $myOfficesDetails->zone_cd;
                    $circleCode = $myOfficesDetails->circle_cd;
                    $divisionCode = $myOfficesDetails->division_cd;

                    $chainageOffices = DB::table('office_details')
                        ->select('office_details.id', 'office_details.office_name', 'asset_master_sub_divisions.zone_cd', 'asset_master_sub_divisions.circle_cd', 'asset_master_sub_divisions.div_cd', 'asset_master_sub_divisions.sub_div_cd', 'asset_master_sub_divisions.sub_div_name')
                        ->join('asset_master_sub_divisions', 'office_details.sub_division_cd', '=', 'asset_master_sub_divisions.sub_div_cd')
                        ->where('office_details.office_type_cd', '=', 'SDO')
                        ->where('office_details.circle_cd', '=', $myOfficesDetails->circle_cd)
                        ->where('office_details.zone_cd', '=', $myOfficesDetails->zone_cd)
                        ->where('office_details.division_cd', '=', $myOfficesDetails->division_cd)
                        ->get();

                    

                    $roadDetails = DB::table('asset_road_chainage_mappings as chng')
                            ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                            'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr', 'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                            'dv.division_name', 'sdv.sub_div_name')
                            ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                            ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                            ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                            ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                            ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                            ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                            ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                            ->whereIn('chng.rd_system_id', function ($query) use ($divisionCode) {
                            $query->select(DB::raw('DISTINCT rd_system_id'))
                                ->from('public.asset_road_chainage_mappings')
                                ->where(function ($subquery) use ($divisionCode) {
                                    $subquery->where('division_cd', $divisionCode)
                                        ->orWhereNotNull('circle_cd')
                                        ->orWhere('remaining_chainage_length', 0);
                                })
                                // ->whereNotExists(function ($innerQuery) use ($divisionCode) {
                                //     $innerQuery->select(DB::raw(1))
                                //         ->from('public.asset_road_chainage_mappings AS inner_mapping')
                                //         ->whereRaw('inner_mapping.rd_system_id = asset_road_chainage_mappings.rd_system_id')
                                //         ->where('inner_mapping.division_cd', $divisionCode)
                                //         ->whereNotNull('inner_mapping.sub_division_cd')
                                //         ->where('inner_mapping.remaining_chainage_length', 0);
                                // })
                                ;
                        })
                        ->where('chng.is_chainage_completed_for_down_level', "N")
                        ->where('chng.zone_cd', $zoneCode)
                        ->where('chng.circle_cd', $circleCode)
                        ->where('chng.division_cd', $divisionCode)
                        ->where('chng.sub_division_cd', null)
                        ->where('rd.road_type', $road_type)
                        // ->where('chainage_created_at_office_cd', '<>', $user->office)
                        ->get();
                }
            // }
            $query = DB::getQueryLog();
            Log::info($query);
            // return view('error');
            return view('road.chainage.road-chainage', compact(
                'user',
                'userMappingDetails',
                'roadDetails',
                'chainageOffices'
            ));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => "app\Http\Controllers\RoadChainage\RoadChainageController.php",
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
        
    }

    public function show(Request $request)
    {
        try
        {
            $road_id = $request->id;
            // $sixDigitRoadId = str_pad($road_id, 6, '0', STR_PAD_LEFT);

            $userMappingDetails = DB::table('asset_user_mappings')
                ->select('*')
                ->where('user_id', '=', Auth::user()->id)
                ->get()
                ->first();

            if (Auth::user()->office_type_cd == "HQ") {
                $road = DB::table('asset_road_details')
                    ->select('*')
                    ->where('rd_system_id', '=', $road_id)
                    ->get()
                    ->first();
            }

            if (Auth::user()->office_type_cd == "ZO") {
                // select asset_road_chainage_mappings.rd_system_id,
                // asset_road_chainage_mappings.chainage_from,
                // asset_road_chainage_mappings.chainage_to,
                // asset_road_chainage_mappings.updated_at,
                // asset_road_details.rd_name,
                // asset_road_details.rd_number
                // from asset_road_chainage_mappings
                // inner join asset_road_details
                // on asset_road_chainage_mappings.rd_system_id = asset_road_details.rd_system_id
                // where asset_road_chainage_mappings.rd_system_id = '000006'
                // and zone_cd = '0'
                // and chainage_created_at_office_cd = '4'
                // order by updated_at desc;
                $road =  DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
                    ->where('asset_road_chainage_mappings.zone_cd', '=', $userMappingDetails->zone_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            if (Auth::user()->office_type_cd == "CO") {
                $road =  DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
                    ->where('asset_road_chainage_mappings.circle_cd', '=', $userMappingDetails->circle_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            if (Auth::user()->office_type_cd == "DO") {
                $road =  DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
                    ->where('asset_road_chainage_mappings.division_cd', '=', $userMappingDetails->division_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            return response()->json($road);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
    }

    // public function showNH(Request $request)
    // {
    //     try
    //     {
    //         $road_id = $request->id;
    //         Log::info('Chainage controller: ');
    //         Log::info('Rd  System id: ' . $road_id);
            

    //             Log::info('Road id: ' . $road_id);

    //             $userMappingDetails = DB::table('asset_user_mappings')
    //                 ->select('*')
    //                 ->where('user_id', '=', Auth::user()->id)
    //                 ->get()
    //                 ->first();

    //             if (Auth::user()->office_type_cd == "HQ") {
    //                 $road = DB::table('asset_road_details')
    //                     ->select('*')
    //                     ->where('rd_system_id', '=', $road_id)
    //                     ->where('id', '=', $road_id)
    //                     ->get()
    //                     ->first();
    //             }


    //             $myOfficesDetails = DB::table('office_details as ofs')
    //                             ->select('ofs.id', 'ofs.office_name', 'ofs.zone_cd', 'ofs.circle_cd', 'ofs.division_cd', 'ofs.sub_division_cd'
    //                             ,'zn.zone_name', 'crl.circle_name', 'dv.division_name', 'sdv.sub_div_name')
    //                             ->leftJoin('asset_master_zones as zn', 'ofs.zone_cd', '=', 'zn.zone_cd')
    //                             ->leftJoin('asset_master_circles as crl', 'ofs.circle_cd', '=', 'crl.circle_cd')
    //                             ->leftJoin('asset_master_divisions as dv', 'ofs.division_cd', '=', 'dv.division_cd')
    //                             ->leftJoin('asset_master_sub_divisions as sdv', 'ofs.sub_division_cd', '=', 'sdv.sub_div_cd')
    //                             ->where('ofs.office_type_cd', '=', Auth::user()->office_type_cd)
    //                             ->where('department_id', Auth::user()->department)
    //                             ->get()->first();
    //             if (Auth::user()->office_type_cd == "ZO") {
    //                 // select asset_road_chainage_mappings.rd_system_id,
    //                 // asset_road_chainage_mappings.chainage_from,
    //                 // asset_road_chainage_mappings.chainage_to,
    //                 // asset_road_chainage_mappings.updated_at,
    //                 // asset_road_details.rd_name,
    //                 // asset_road_details.rd_number
    //                 // from asset_road_chainage_mappings
    //                 // inner join asset_road_details
    //                 // on asset_road_chainage_mappings.rd_system_id = asset_road_details.rd_system_id
    //                 // where asset_road_chainage_mappings.rd_system_id = '000006'
    //                 // and zone_cd = '0'
    //                 // and chainage_created_at_office_cd = '4'
    //                 // order by updated_at desc;
    //                 // $road =  DB::table('asset_road_chainage_mappings')
    //                 //     ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
    //                 //     ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
    //                 //     ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
    //                 //     ->where('asset_road_chainage_mappings.id', '=', $road_id)
    //                 //     ->where('asset_road_chainage_mappings.zone_cd', '=', $userMappingDetails->zone_cd)
    //                 //     ->orderBy('updated_at', 'desc')
    //                 //     ->get()
    //                 //     ->first();

    //                     $road = DB::table('asset_road_chainage_mappings as chng')
    //                             ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
    //                             'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr as road_type' , 'zn.zone_name', 'crl.circle_name', 
    //                             'dv.division_name', 'sdv.sub_div_name')
    //                             ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
    //                             ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
    //                             ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
    //                             ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
    //                             ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
    //                             ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
    //                             ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
    //                             ->where ('chng.id', function($query ) use ($myOfficesDetails, $road_id)
    //                             {
    //                                 $query->select(DB::raw('MAX(id)'))->from('asset_road_chainage_mappings as chng1')
    //                                 ->where('chng1.chainage_step_id', '!=', 0)
    //                                 ->where('chng1.remaining_chainage_length', '!=', 0)
    //                                 ->where('chng1.zone_cd', '=', $myOfficesDetails->zone_cd)
    //                                 ->whereNotNull('chng1.circle_cd')
    //                                 ->whereNull('chng1.division_cd')
    //                                 ->whereNull('chng1.sub_division_cd')
    //                                 ->where('chng1.rd_system_id', $road_id);
    //                             })->get()->first();
    //             }

    //             if (Auth::user()->office_type_cd == "CO") {
    //                 $road =  DB::table('asset_road_chainage_mappings')
    //                     ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
    //                     ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
    //                     ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
    //                     ->where('asset_road_chainage_mappings.id', '=', $road_id)
    //                     ->where('asset_road_chainage_mappings.circle_cd', '=', $userMappingDetails->circle_cd)
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get()
    //                     ->first();
    //             }

    //             if (Auth::user()->office_type_cd == "DO") {
    //                 $road =  DB::table('asset_road_chainage_mappings')
    //                     ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.updated_at', 'asset_road_details.rd_name', 'asset_road_details.rd_number')
    //                     ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
    //                     ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_id)
    //                     ->where('asset_road_chainage_mappings.id', '=', $road_id)
    //                     ->where('asset_road_chainage_mappings.division_cd', '=', $userMappingDetails->division_cd)
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get()
    //                     ->first();
    //             }

    //             return response()->json($road);
            
    //     } catch (Exception $e) {
    //         Log::error("message: " . $e->getMessage(), [
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);
    //         return view('error');
    //     }
    // }

    public function getRoadChainageDetails(Request $request)
    {
        try
        {
            $user = Auth::user();
            $userMappingDetails = DB::table('asset_user_mappings')
                ->select('*')
                ->where('user_id', '=', $user->id)
                ->get()
                ->first();

            $road_id = $request->id;
            $office_cd = Auth::user()->office;
            $office_type_cd = Auth::user()->office_type_cd;
            if ($office_type_cd == 'HQ') {
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            if ($office_type_cd == 'ZO') {
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('zone_cd', '=', $userMappingDetails->zone_cd)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            if ($office_type_cd == 'CO') {
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('zone_cd', '=', $userMappingDetails->zone_cd)
                    ->where('circle_cd', '=', $userMappingDetails->circle_cd)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }

            if ($office_type_cd == 'DO') {
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('zone_cd', '=', $userMappingDetails->zone_cd)
                    ->where('circle_cd', '=', $userMappingDetails->circle_cd)
                    ->where('division_cd', '=', $userMappingDetails->division_cd)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
            }
            return response()->json($chainageValue);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
        
    }

    public function getNHChainageDetails(Request $request)
    {
        try
        {
            Log::info("Inside getNHChainageDetails method in RoadChainageController");
            
            $user = Auth::user();

            $userMappingDetails = DB::table('asset_user_mappings')
                ->select('*')
                ->where('user_id', '=', $user->id)
                ->get()
                ->first();

            $myOfficesDetails = DB::table('office_details as ofs')
                            ->select('ofs.id', 'ofs.office_name', 'ofs.zone_cd', 'ofs.circle_cd', 'ofs.division_cd', 'ofs.sub_division_cd'
                            ,'zn.zone_name', 'crl.circle_name', 'dv.division_name', 'sdv.sub_div_name')
                            ->leftJoin('asset_master_zones as zn', 'ofs.zone_cd', '=', 'zn.zone_cd')
                            ->leftJoin('asset_master_circles as crl', 'ofs.circle_cd', '=', 'crl.circle_cd')
                            ->leftJoin('asset_master_divisions as dv', 'ofs.division_cd', '=', 'dv.division_cd')
                            ->leftJoin('asset_master_sub_divisions as sdv', 'ofs.sub_division_cd', '=', 'sdv.sub_div_cd')
                            ->where('ofs.office_type_cd', '=', Auth::user()->office_type_cd)
                            ->where('ofs.id', '=', Auth::user()->office)
                            ->where('department_id', Auth::user()->department)
                            ->get()->first();
            $road_id = $request->road_id; // pk
            $pk_id = $request->id;
            // $road = AssetRoadChainageMapping::findOrFail($id);
            // $road_id = $road->rd_system_id; // road id
            $office_cd = Auth::user()->office;
            $office_type_cd = Auth::user()->office_type_cd;
            $chainageValue = null;
            if ($office_type_cd == 'HQ') {
                Log::info("Checking if any Chainage has been done in Zone Level for Road Id: ". $road_id . " from HQ");
                $chainageValue =  DB::table('asset_road_chainage_mappings as chng')
                                ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                                'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr' ,'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                                'dv.division_name', 'sdv.sub_div_name')
                                ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                                ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                                ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                                ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                                ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                                ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                                ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                                ->where ('chng.id', function($query ) use ($myOfficesDetails, $road_id)
                                {
                                    $query->select(DB::raw('MAX(id)'))->from('asset_road_chainage_mappings as chng1')
                                    // ->where('chng1.zone_cd', '=', $myOfficesDetails->zone_cd)
                                    ->whereIn('chng1.zone_cd',  function($query) use($myOfficesDetails){
                                        $query->select(DB::raw('zone_cd'))->from('asset_master_zones as zn')
                                        ->where('zn.dept_cd', Auth::user()->department)->get();
                                    })
                                    ->whereNull('chng1.circle_cd')
                                    ->whereNull('chng1.division_cd')
                                    ->whereNull('chng1.sub_division_cd')
                                    ->where('chng1.rd_system_id', $road_id);
                                })
                                ->orderBy('updated_at', 'desc')
                                ->get()
                                ->first();
            }

            if ($office_type_cd == 'ZO') {
                Log::info("Checking if any Chainage has been done in Circle Level for Road Id: ". $road_id. 
                                " from Zone : ". $myOfficesDetails->zone_cd);
                $arrCircles = DB::table('asset_master_circles as crl')
                                ->select('crl.circle_cd')
                                ->where('crl.zone_cd', $myOfficesDetails->zone_cd)
                                ->get()
                                ->toArray();

                $chainageValue = DB::table('asset_road_chainage_mappings as chng')
                                ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                                'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr' ,'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                                'dv.division_name', 'sdv.sub_div_name')
                                ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                                ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                                ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                                ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                                ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                                ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                                ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                                ->where ('chng.id', function($query ) use ($myOfficesDetails, $road_id, $arrCircles)
                                {
                                    $query->select(DB::raw('MAX(id)'))->from('asset_road_chainage_mappings as chng1')
                                    // ->where('chng1.chainage_step_id', '!=', 0)
                                    // ->where('chng1.remaining_chainage_length', '!=', 0)
                                    ->where('chng1.zone_cd', '=', $myOfficesDetails->zone_cd)
                                    ->whereIn('chng1.circle_cd',  function($query) use($myOfficesDetails){
                                        $query->select(DB::raw('circle_cd'))->from('asset_master_circles as crcl')
                                        ->where('crcl.zone_cd', $myOfficesDetails->zone_cd)->get();
                                    })
                                    ->whereNull('chng1.division_cd')
                                    ->whereNull('chng1.sub_division_cd')
                                    ->where('chng1.rd_system_id', $road_id);
                                })->get()->first();
                
            }

            if ($office_type_cd == 'CO') {
                Log::info("Checking if any Chainage has been done in Division Level for Road Id: ". $road_id. 
                                    " from Circle : ". $myOfficesDetails->circle_cd);
                $arrDivs = DB::table('asset_master_divisions as div')
                                ->select('div.division_cd')
                                ->where('div.circle_cd', $myOfficesDetails->circle_cd)
                                ->get()
                                ->toArray();
                Log::info($arrDivs);
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('zone_cd', '=', $userMappingDetails->zone_cd)
                    ->where('circle_cd', '=', $userMappingDetails->circle_cd)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();
                $chainageValue = DB::table('asset_road_chainage_mappings as chng')
                    ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                    'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr' ,'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                    'dv.division_name', 'sdv.sub_div_name')
                    ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                    ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                    ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                    ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                    ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                    ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                    ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                    ->where ('chng.id', function($query ) use ($myOfficesDetails, $road_id, $pk_id)
                    {
                        $query->select(DB::raw('MAX(id)'))->from('asset_road_chainage_mappings as chng1')
                        // ->where('chng1.chainage_step_id', '!=', 0)
                        // ->where('chng1.remaining_chainage_length', '!=', 0)
                        ->where('chng1.zone_cd', '=', $myOfficesDetails->zone_cd)
                        ->where('chng1.circle_cd' , $myOfficesDetails->circle_cd)
                        ->whereIn('chng1.division_cd',  function($query) use($myOfficesDetails){
                            $query->select(DB::raw('division_cd'))->from('asset_master_divisions as div')
                            ->where('div.circle_cd', $myOfficesDetails->circle_cd)->get();
                        })
                        ->whereNull('chng1.sub_division_cd')
                        ->where('chng1.rd_system_id', $road_id)
                        ->where('chng1.parent_pk_id', $pk_id);
                    })->get()->first();
            }

            if ($office_type_cd == 'DO') {
                Log::info("Checking if any Chainage has been done in Sub Division Level for Road Id: ". $road_id. 
                                    " from Division : ". $myOfficesDetails->division_cd);
                $arrSubDivs = DB::table('asset_master_sub_divisions as sdiv')
                                ->select('sdiv.sub_div_cd')
                                ->where('sdiv.div_cd', $myOfficesDetails->division_cd)
                                ->get()
                                ->toArray();
                $chainageValue = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id', 'chainage_from', 'chainage_to', 'chainage_step_id', 
                    'chainage_created_at_office_cd', 'chainage_created_by', 'chainage_updated_by')
                    ->where('rd_system_id', '=', $road_id)
                    ->where('zone_cd', '=', $userMappingDetails->zone_cd)
                    ->where('circle_cd', '=', $userMappingDetails->circle_cd)
                    ->where('division_cd', '=', $userMappingDetails->division_cd)
                    ->where('chainage_created_at_office_cd', '=', $office_cd)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->first();

                $chainageValue = DB::table('asset_road_chainage_mappings as chng')
                    ->select('chng.*', 'rd.rd_number', 'rd.rd_name', 'rd.road_length','rd.rd_type_cd',
                    'rd_catg.rd_catg_descr', 'rd_td.rd_type_descr' ,'rd.road_type' , 'zn.zone_name', 'crl.circle_name', 
                    'dv.division_name', 'sdv.sub_div_name')
                    ->join('asset_road_details as rd', 'rd.rd_system_id', 'chng.rd_system_id')
                    ->join('asset_master_road_category as rd_catg', 'rd.rd_category_cd', 'rd_catg.rd_catg_cd')
                    ->join('asset_master_rd_type as rd_td', 'rd.rd_type_cd', 'rd_td.rd_type_cd')
                    ->leftJoin('asset_master_zones as zn', 'chng.zone_cd', '=', 'zn.zone_cd')
                    ->leftJoin('asset_master_circles as crl', 'chng.circle_cd', '=', 'crl.circle_cd')
                    ->leftJoin('asset_master_divisions as dv', 'chng.division_cd', '=', 'dv.division_cd')
                    ->leftJoin('asset_master_sub_divisions as sdv', 'chng.sub_division_cd', '=', 'sdv.sub_div_cd')
                    ->where ('chng.id', function($query ) use ($myOfficesDetails, $road_id, $pk_id)
                    {
                        $query->select(DB::raw('MAX(id)'))->from('asset_road_chainage_mappings as chng1')
                        // ->where('chng1.chainage_step_id', '!=', 0)
                        // ->where('chng1.remaining_chainage_length', '!=', 0)
                        ->where('chng1.zone_cd', '=', $myOfficesDetails->zone_cd)
                        ->where('chng1.circle_cd' , $myOfficesDetails->circle_cd)
                        ->where('chng1.division_cd', $myOfficesDetails->division_cd)
                        ->whereIn('chng1.sub_division_cd',  function($query) use($myOfficesDetails){
                            $query->select(DB::raw('sub_div_cd'))->from('asset_master_sub_divisions as sdiv')
                            ->where('sdiv.div_cd', $myOfficesDetails->division_cd)->get();
                        })
                        ->where('chng1.rd_system_id', $road_id)
                        ->where('chng1.parent_pk_id', $pk_id);
                    })->get()->first();
            }

            $query = DB::getQueryLog();
            Log::info($query);
            $totalRecords = collect($chainageValue)->count();
            $noOfRec = (int)$totalRecords;
            Log::info("Total Reocords : " . $noOfRec);

            if($noOfRec == 0 )
            { 
                Log::info("Found No Data");
                return response()->json(["data"=>$chainageValue, "success" => false]);
            }
            else
            {
                Log::info("Found Some Data");
                return response()->json(["data" => $chainageValue,"success"=> true]);
            }
                
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'road_id' => 'required',
            'road_name' => 'required',
            'road_number' => 'required',
            'chainage_office' => 'required',
            'start_chainage' => 'required',
            'end_chainage' => 'required',
            'remaining' => 'required',
        ]);
        try {
            $data = [
                'rd_system_id' => $request->road_id,
                'chainage_from' => $request->start_chainage,
                'chainage_to' => $request->end_chainage,
                'zone_cd' => $request->chainage_office,
                'circle_cd' => null,
                'division_cd' => null,
                'sub_division_cd' => null,
                'remaining_chainage_length' => $request->remaining,
                'chainage_step_id' => '1',
                'chainage_created_at_office_cd' => Auth::user()->office,
                'chainage_created_by' => Auth::user()->id,
                'chainage_updated_by' => Auth::user()->id,
                'calculated_length' => $request->calculated_length ? $request->calculated_length : null,
                'chainage_created_at' => Auth::user()->office_type_cd,
                'parent_pk_id' => $request->chainage_pk,
            ];

            if (Auth::user()->office_type_cd == 'ZO') {
                $data['zone_cd'] = $request->zone_cd;
                $data['circle_cd'] = $request->chainage_office;
            }

            if (Auth::user()->office_type_cd == 'CO') {
                $data['zone_cd'] = $request->zone_cd;
                $data['circle_cd'] = $request->circle_cd;
                $data['division_cd'] = $request->chainage_office;
            }

            if (Auth::user()->office_type_cd == 'DO') {
                $data['zone_cd'] = $request->zone_cd;
                $data['circle_cd'] = $request->circle_cd;
                $data['division_cd'] = $request->division_cd;
                $data['sub_division_cd'] = $request->chainage_office;
            }

            if (($request->remaining < 0) || ($request->remaining == 'NaN')) {
                return redirect()->back()
                    ->with('invalid', 'Invalid Value Found');
            } else {
                $status = AssetRoadChainageMapping::create($data);
                if ($status) {
                    Log::info("Successfully Created Chainage for Road id: ".$request->road_id .
                                " at " . Auth::user()->office_type_cd . " Level.".
                                " Zone_cd = ". $request->zone_cd . 
                                " , Circle_cd = " . $request->circle_cd . 
                                " , division_cd = " . $request->division_cd. 
                                " , sub_division_cd = " . $request->sub_division_cd);
                    if ($request->remaining == 0)
                    {
                        $updateChainageCompletedStatusOftheParentId = DB::table('asset_road_chainage_mappings')
                            ->where('id', $request->chainage_pk)
                            ->update([
                                'is_chainage_completed_for_down_level' => "Y"
                            ]);
                    }
                    Log::info("Here come for KML");
                    // Upload the kml file here -- Saiful -- Start
                    $divDtls = DB::table('asset_master_divisions as div')
                        ->select('div.division_cd', 'div.division_name')
                        ->where('div.division_cd', $request->chainage_office)
                        ->get()->first();

                    $division_name = null;
                    if ($divDtls)
                        $division_name = $divDtls->division_name;

                    log::info("$division_name: " . $division_name);
                    $configPath = config('customconfigpath.ROAD_DOCS_PATH');
                    $rootPath = config('filesystems.disks.external.root');
                    if ($request->hasFile('road_kml_file')) {
                        $file = $request->file('road_kml_file');
                        $uniqueFileName = $request->road_id . "_" . $request->chainage_office . '.kml';
                        $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';

                        if (!Storage::exists($folderPath)) {
                            Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                        }

                        // Store the file using the 'external' disk
                        $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                        // Combine the root path and folder path to get the complete file path
                        $completeFilePath = $rootPath . '/' . $filePath;

                        $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $request->road_id . "_" . $request->chainage_office . '.geojson';
                        AssetRoadDocumentKmlFileDetails::create([
                            'rd_system_id' => $request->road_id . "_" . $request->chainage_office,
                            'file_path' => $completeFilePath,
                            'geojson_file_path' => $geojson_file_path,
                            'file_type' => 'kml',
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'created_at_office_cd' => Auth::user()->office,
                        ]);


                        //Call POST API To convert the KML file to Shape File
                        $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_INTER_DIV_API');
                        // Create a Guzzle HTTP client
                        $client = new Client();

                        // Make a POST request to the API
                        $response = $client->request('POST', $kml_file_api, [
                            'json' => [
                                'uid' => auth()->id(),
                                'kml_file_path' => $completeFilePath,
                                'road_id' => $request->road_id . "_" . $request->chainage_office,
                                'road_name' => $request->road_name,
                                'road_length' => $request->road_length,
                                'road_category' => $request->road_category,
                                'division_cd' => $request->chainage_office,
                                'division_name' => $division_name
                            ]
                        ]);

                        log::info("hjhjhjhjhjhjhjhjhj");
                        // $json_data= json_decode($response->getBody()->getContents());
                        $json_data = json_decode($response->getBody());
                        log::info($json_data->status);
                        if ($json_data->status == False) {
                            DB::rollback();
                            return redirect()->route('chainage')
                                ->with('failed', 'Failed to create chainage. Uploaded KML File does not have Roads Layer Structure!!!!')
                                ->with('kmlFormatIssue', 'KML File Not In Proper Format');
                        }
                    }

                    // Upload the kml file here -- Saiful -- End
                    return redirect()->back()
                        ->with('success', 'Value inserted successfully.')
                        ->with('road_id', $request->road_id);

                } else {
                    return redirect()->back()
                        ->with('failed', 'Value not inserted.');
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
    }

    public function getNHEndChainage(Request $request)
    {
        Log::info('getNHEndChainage method in RoadChainageController');
        try {
            $user = Auth::user();
            // $officeType = $user->office_type_cd;
            $id = $request->id;
            Log::info('id: ' . $id);
            $road = AssetRoadChainageMapping::findOrFail($id);
            $road_id = $road->rd_system_id;
            Log::info('road id: ' . $road_id);
            // Log::info('office type: ' . $officeType);

            $road = AssetRoadChainageMapping::findOrFail($id);
            Log::info('Chainage id: ' . $id);
            $road_id = $road->rd_system_id;
            Log::info('Road id: ' . $road_id);
            $office_cd = $user->office;
            $users_office_type_cd = $user->office_type_cd;
            $zone_cd = null;
            $circle_cd = null;
            $division_cd = null;
            $sub_division_cd = null;

            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();

            $zone_cd = $userMapping->zone_cd;
            $circle_cd = $userMapping->circle_cd;
            $division_cd = $userMapping->division_cd;
            $sub_division_cd = $userMapping->sub_division_cd;
            DB::enableQueryLog();
            if (session('office_charge_type') == 1) {
                $office_cd = session('office_cd');
                $users_office_type_cd = session('users_office_type_cd');
                $officeDivisionDtls = DB::table('office_details as ofd')
                    ->select('ofd.zone_cd', 'ofd.circle_cd', 'ofd.division_cd', 'ofd.sub_division_cd')
                    ->where('ofd.id', '=', $office_cd)
                    ->get()->first();
                if ($officeDivisionDtls) {
                    $zone_cd = $officeDivisionDtls->zone_cd;
                    $circle_cd = $officeDivisionDtls->circle_cd;
                    $division_cd =  $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }

            $users_office_type_cd = Auth::user()->office_type_cd;
            $chainageDetails = [];
            if ($users_office_type_cd == 'HQ') {
                $max_id = DB::table('asset_road_chainage_mappings')
                    ->where('id', function ($query) use ($road_id) {
                        $query->select(DB::raw('max(id)'))
                            ->from('asset_road_chainage_mappings')
                            ->where('chainage_created_at', 'HQ')
                            ->where('rd_system_id', $road_id);
                    })
                    ->first();
                Log::info('max id: ' . $max_id->id);

                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('id', $max_id->id)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'ZO') {
                $max_id = DB::table('asset_road_chainage_mappings')
                    ->where('id', function ($query) use ($road_id) {
                        $query->select(DB::raw('max(id)'))
                            ->from('asset_road_chainage_mappings')
                            ->where('chainage_created_at', 'ZO')
                            ->orWhere('chainage_created_at', 'HQ')
                            ->where('rd_system_id', $road_id);
                    })
                    ->first();
                Log::info('max id: ' . $max_id->id);

                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('id', $max_id->id)
                    ->where('zone_cd', $zone_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'CO') {
                $max_id = DB::table('asset_road_chainage_mappings')
                    ->where('id', function ($query) use ($road_id) {
                        $query->select(DB::raw('max(id)'))
                            ->from('asset_road_chainage_mappings')
                            ->where('chainage_created_at', 'CO')
                            ->orWhere('chainage_created_at', 'ZO')
                            ->where('rd_system_id', $road_id);
                    })
                    ->first();
                Log::info('max id: ' . $max_id->id);
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('id', $id)
                    ->where('circle_cd', $circle_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'DO') {
                $max_id = DB::table('asset_road_chainage_mappings')
                    ->where('id', function ($query) use ($road_id) {
                        $query->select(DB::raw('max(id)'))
                            ->from('asset_road_chainage_mappings')
                            ->where('chainage_created_at', 'DO')
                            ->orWhere('chainage_created_at', 'CO')
                            ->where('rd_system_id', $road_id);
                    })
                    ->first();
                Log::info('max id: ' . $max_id->id);
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('id', $max_id->id)
                    ->where('division_cd', $division_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            $query = DB::getQueryLog();
            Log::info($query);
            Log::info('Calculated Length: ' . $chainageDetails->calculated_length);
            return response()->json($chainageDetails);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
    }

    public function getEndChainage(Request $request)
    {
        try
        {
            $user = Auth::user();
            $road_id = $request->id;

            $office_cd = $user->office;
            $users_office_type_cd = $user->office_type_cd;
            $zone_cd = null;
            $circle_cd = null;
            $division_cd = null;
            $sub_division_cd = null;

            $userMapping = DB::table('asset_user_mappings')
                            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                            ->where('user_id', '=', $user->id)
                            ->get()->first();

            $zone_cd = $userMapping->zone_cd;
            $circle_cd = $userMapping->circle_cd;
            $division_cd = $userMapping->division_cd;
            $sub_division_cd = $userMapping->sub_division_cd;
            if (session('office_charge_type') == 1) {
                $office_cd = session('office_cd');
                $users_office_type_cd = session('users_office_type_cd');
                $officeDivisionDtls = DB::table('office_details as ofd')
                    ->select('ofd.zone_cd', 'ofd.circle_cd', 'ofd.division_cd', 'ofd.sub_division_cd')
                    ->where('ofd.id', '=', $office_cd)
                    ->get()->first();
                if ($officeDivisionDtls) {
                    $zone_cd = $officeDivisionDtls->zone_cd;
                    $circle_cd = $officeDivisionDtls->circle_cd;
                    $division_cd =  $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }

            $users_office_type_cd = Auth::user()->office_type_cd;
            $chainageDetails = [];
            if ($users_office_type_cd == 'HQ') {
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'ZO') {
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('zone_cd', $zone_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'CO') {
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('circle_cd', $circle_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            if ($users_office_type_cd == 'DO') {
                $chainageDetails = DB::table('asset_road_chainage_mappings')
                    ->select('chainage_from', 'chainage_to', 'chainage_step_id', 'calculated_length', 'chainage_created_at')
                    ->where('rd_system_id', $road_id)
                    ->where('division_cd', $division_cd)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->first();
            }
            return response()->json($chainageDetails);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' =>$e->getTraceAsString()
            ]);
            return view('error');
        }
    }
}
