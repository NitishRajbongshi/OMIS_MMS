<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{
  public function index()
    {
        $makers = DB::table('mechanicals.asset_master_vehicle_makers')->orderBy('maker_name')->get();
        
        $models = DB::table('mechanicals.asset_master_vehicle_maker_models as models')
            ->join('mechanicals.asset_master_vehicle_makers as makers', 'models.maker_cd', '=', 'makers.maker_cd')
            ->select('models.*', 'makers.maker_name')
            ->get();

        return view('master.mechanical.vehicleModels', compact('makers', 'models'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_maker_models')->insert([
                'model_cd' => strtoupper($request->model_cd),
                'model_name' => $request->model_name,
                'maker_cd' => $request->maker_cd
            ]);
            return response()->json(['status' => 'success', 'message' => 'Model added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Likely duplicate model code.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_maker_models')
                ->where('model_cd', $request->old_model_cd)
                ->update([
             //       'model_cd' => strtoupper($request->model_cd),
                    'model_name' => $request->model_name,
                    'maker_cd' => $request->maker_cd
                ]);
            return response()->json(['status' => 'success', 'message' => 'Model updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
