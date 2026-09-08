<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    public function forgotPassword()
    {
        try {
            return view('auth.forgotPassword');
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }
    public function storeNewPassword(Request $request)
    {
        $formValidation = $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);

        try {
            $email = $request->input('email');
            $code = $request->input('code');
            $password = $request->input('password');
            DB::enableQueryLog();
            Log::info('Password controller: ');
            $user = User::select('id', 'activity_status', 'secret_code')->where('email', $email)->get()->first();
            if ($user) {
                // check for active user
                if ($user->activity_status === 'A') {
                    // check if the user have the code
                    if ($user->secret_code === '0') {
                        return redirect()->back()
                            ->with('failed', 'Code not set yet!');
                    } else {
                        if (Hash::check($code, $user->secret_code)) {
                            $newpassword = Hash::make($password);
                            $status = DB::table('users')
                                ->where('id', $user->id)
                                ->update(['password' => $newpassword]);
                            if ($status) {
                                return redirect()->back()
                                    ->with('success', 'Password created successfully.');
                            } else {
                                return redirect()->back()
                                    ->with('failed', 'Failed to create new password!');
                            }
                        } else {
                            return redirect()->back()
                                ->with('failed', 'Secret code does not match!');
                        }
                    }
                } else {
                    return redirect()->back()
                        ->with('failed', 'Account deactivated!');
                }
            } else {
                return redirect()->back()
                    ->with('failed', 'Invalid email id!');
            }
            $query = DB::getQueryLog();
            Log::info($query);
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
    public function resetPassword(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'code' => 'required',
            'password' => 'required|string|min:8',
        ]);
        try {
            $code = $request->input('code');
            $password = $request->input('password');
            DB::enableQueryLog();
            Log::info('Password controller: ');
            if (Hash::check($code, $user->secret_code)) {
                $newpassword = Hash::make($password);
                $status = DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $newpassword]);
                if ($status) {
                    return redirect()->back()
                        ->with('success', 'Password reset successfully.');
                } else {
                    return redirect()->back()
                        ->with('failed', 'Failed to reset the password!');
                }
            } else {
                return redirect()->back()
                    ->with('failed', 'Your code does not match!');
            }
            $query = DB::getQueryLog();
            Log::info($query);
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
