<?php

namespace App\Http\Controllers\backend\auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function index(){
        return view('backend.auth.login');
    }
    public function login(Request $request){
        $auth = Auth::attempt(
            [
               'email' => strtolower($request->email),
               'password' => $request->password,
            ],
        );
        if($auth){
            return response()->json(['success'=>true,'user_id'=>auth()->user()->id,'user_name'=>auth()->user()->name],200);
        }else{
            return response()->json(['error_success' => 'Credentials do not match!'], 200);
        }
    }
    public function dashboard(){
        return view('backend.admin.modules.dashboard');
    }
    public function sendotp(Request $request){
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
               'email' => 'required|email',
            ]);
            if ($validator->fails()) {
               return response()->json([
                  'error_validation' => $validator->errors()->all(),
               ], 200);
            }
         }
   
         $otp = random_int(100000, 999999);
         $emails = $request->email;
         $check_email = User::where('email', $emails)->get(['email']); // checking email ID found in db.
         $check_email = $check_email[0]->email ?? '';
         if ($check_email == $emails) {  // checking entered email and db email are same or not.
            $check_emailotp = EmailOtp::where('email', $emails)->get(['email']);
            $check_emailotp = $check_emailotp[0]->email ?? '';
            if ($check_emailotp == '') {
               $emailotp = new EmailOtp();
               $emailotp->email = $emails;
               $emailotp->otp = $otp;
               $emailotp->save(); // save new email id and otp in db.
            } else {
               EmailOtp::where('email', $emails)->update(
                  [
                     'otp' => $otp
                  ]
               ); // updating otp in db againt email id.
            }
            // Mail::send('backend.auth.otp-forgotpass', ['otp' => $otp], function ($message) use ($request) {
            //    $message->to($request->input('email'))->subject('OTP For Password Reset');
            // }); //OTP send on mail function
            return response()->json(['success' => 'OTP sent successfully']);
         } else {
            $response = response()->json(['error_success' => 'Email id not found'], 200);
         }
         return $response;
    }

    public function verifyotp(Request $request){
       $user_email = $request->email;
       $user_otp = $request->otp;
       $check_otp = EmailOtp::where('email', $user_email)->get();
       $otp_time = $check_otp[0]->updated_at;
       $mytime = Carbon::now()->toDateTimeString();
       $startTime = Carbon::parse($otp_time);
       $finishTime = Carbon::parse($mytime);
       $otpduration = $finishTime->diffInMinutes($startTime) ?? '';
       $otp = $check_otp[0]->otp ?? '';
       $email = $check_otp[0]->email ?? '';
       if ($user_email == $email && $user_otp == $otp && $otpduration <= 15) {
          $response = response()->json(['success' => 'OTP Verified successfully'], 200);
       } else {
          $response = response()->json(['errors_success' => 'Error in OTP Verification !'], 200);
       }
       return $response;
    }
    public function updatepass(Request $request){
       $user_email = $request->email;
       $pass = $request->pass;
       $cpass = $request->cpass;
       if ($pass == $cpass) {
          $pass1 = Hash::make($pass);
          User::where('email', $user_email)->update(
             [
                'password' => $pass1,
                'plain_password' => $pass
             ]
          );
          $response = response()->json(['success' => 'Password changed successfully'], 200);
       } else {
          $response = response()->json(['errors_success' => 'Error in changing password !'], 200);
       }
       return $response;
    }
      
   public function logout(Request $request)
   {
      Auth::guard('web')->logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
    //return view('backend.auth.logout');
      return redirect("/");
   }
}
