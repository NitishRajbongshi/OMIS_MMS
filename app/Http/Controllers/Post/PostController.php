<?php

namespace App\Http\Controllers\Post;

use Exception;
use Carbon\Carbon;
use App\Models\PostDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            DB::enableQueryLog();
            Log::info('Post controller: ');
            $p_details = PostDetail::all();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('post.index', compact('p_details'));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
    
    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), [
                    'post_name' => 'required|string|max:255',
                ], [
                    'post_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = PostDetail::whereRaw('LOWER(post_name) = ?', [strtolower($request->post_name)])->exists();

                    if (!$isNameExist) {
                        $PostDetail = new PostDetail();
                        $PostDetail->post_name = $request->post_name;
                        $PostDetail->created_at = Carbon::now();
                        $PostDetail->updated_at = Carbon::now();

                        $PostDetail->save();

                        return response()->json([
                            'message' => 'success',
                            'request' => 'Post added successfully!',
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'duplicate'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return response()->json([
                'messege' => 'error',
                'request' => 'Something Went Wrong',
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'post_name' => 'required|string|max:255',
                ], [
                    'post_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = PostDetail::whereRaw('LOWER(post_name) = ?', [strtolower($request->post_name)])->exists();

                    if (!$isNameExist) {
                        $postdata = PostDetail::find($request->id);
                        $postdata->post_name = $request->post_name;
                        $postdata->created_at = Carbon::now();
                        $postdata->updated_at = Carbon::now();
                        $postdata->save();
                        return response()->json([
                            'message' => 'success'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'duplicate'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return $e;
        }
    }

    public function destroy($id)
    {
        $data = PostDetail::find($id);
        $data->delete();
        alert()->success('Record Deleted successfully')->persistent('Close')->autoclose(3000);
        return redirect()->route('managePost');
    }
}
