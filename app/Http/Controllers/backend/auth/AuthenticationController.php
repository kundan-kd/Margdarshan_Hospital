<?php

namespace App\Http\Controllers\backend\auth;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Billing;
use App\Models\EmailOtp;
use App\Models\LabInvestigation;
use App\Models\LabReport;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
               'status' => 1 // Only allow login if status is 1
            ],
        );
        if($auth){
            return response()->json(['success'=>true,'user_id'=>auth()->user()->id,'user_name'=>auth()->user()->name],200);
        }else{
            return response()->json(['error_success' => 'Credentials do not match!'], 200);
        }
    }
    public function dashboard(){
      //   $tot_patients = Patient::count();
      //   $appointments = PatientLog::where('status','!=','Cancelled')->count();
      //   $opd_patients = Patient::where('type','OPD')->count();
      //   $ipd_patients = Patient::where('type','IPD')->count();
      //   $icu_patients = Patient::where('type','ICU')->count();
      //   $emergency_patients = Patient::where('type','EMERGENCY')->count();
      //   $doctors = User::where('usertype_id',2)->count();
      //   $total_income = PaymentReceived::sum('amount') + PaymentReceived::sum('discount_amount');
      //   $leads = Lead::count();
      //   $convertedLeads = Lead::where('lead_status','Converted')->count();
      //   $assignLeads = Lead::whereNotNull('assign_to')->count();
      //   $UnAssignLeads = Lead::whereNull('assign_to')->count();
      //   $now = Carbon::now();
      //   $formattedDate = $now->toDateString(); // Output: "2025-07-16"
      //   $todayFollowup = Lead::where('next_followup_date',$formattedDate)->get();
      //   $duefollowup = Lead::whereNull('naration')->get();
      //   $today_pharmacy_bill = Billing::whereDate('created_at', $formattedDate)
      //       ->selectRaw('SUM(paid_amount - return_amount) as total')
      //       ->value('total');
      //   $total_pharmacy_bill = Billing::selectRaw('SUM(paid_amount - return_amount) as total')
      //       ->value('total');
      //   $total_lab_report  = LabInvestigation::count();
      //   $report_generated  = LabReport::count();
      //   return view('backend.admin.modules.dashboard',compact('tot_patients','appointments','opd_patients','ipd_patients','icu_patients','emergency_patients','doctors','total_income','leads','convertedLeads','assignLeads','UnAssignLeads','todayFollowup','duefollowup','today_pharmacy_bill','total_pharmacy_bill','total_lab_report','report_generated'));









      $doctors = User::where('usertype_id',2)->get(['id','name']);
      $consultantCount = [];
      foreach($doctors as $doctor){
         $count = PatientLog::where('doctor_id',$doctor->id)->count();
         $consultantCount[] = [
            'doctor_name' => $doctor->name,
            'patient_count' => $count
         ];
      }
      $appointments = PatientLog::where('type','OPD')->count();
      $appointment_visited = PatientLog::where('type','OPD')->where('status','Visited')->orWhere('status','Moved to IPD')->count();
      $appoinrmentsCount = [                          
         'total_appointment' => $appointments,
         'walk_in' => $appointment_visited
      ];
      $totalBIlls = PaymentBill::sum('amount');
      $billReceived = PaymentReceived::sum('amount') + PaymentReceived::sum('discount_amount');
      $totalRevenue = [
         'total_amount' => $totalBIlls,
         'received_amount' => $billReceived
      ];
      $newPatients = PatientLog::select('patient_id')->groupBy('patient_id')->havingRaw('COUNT(*) = 1')->count();
      $repeatPatient = PatientLog::select('patient_id')->groupBy('patient_id')->havingRaw('COUNT(*) > 1')->count();
      $patientCounts = [
         'repeat_patient' => $repeatPatient,
         'new_patient' => $newPatients
      ];
      $emergencyAdded = PatientLog::where('type','EMERGENCY')->where('current_status','Admitted')->count();
      $emergencyMoved = PatientLog::where('type','EMERGENCY')->where('description','Movement EMERGENCY to IPD')->orWhere('description','Movement EMERGENCY to ICU')->count();
      $emergencyPatients = [
         'admission' => $emergencyAdded,
         'moved' => $emergencyMoved
      ];
      $icuBeds = Bed::where('bed_group_id',4)->count();
      $icuBedOccupied = Bed::where('bed_group_id',4)->where('current_status','occupied')->count();
      $icuBeds = [
         'total' => $icuBeds,
         'occupied' => $icuBedOccupied
      ];
      $today = Carbon::today();
      $daily_lab = LabInvestigation::whereDate('created_at', $today)->count();
      $weekly_lab = LabInvestigation::whereBetween('created_at', [$today->copy()->subDays(7),$today->endOfDay()])->count();
      $monthly_lab = LabInvestigation::whereBetween('created_at', [$today->copy()->subDays(30),$today->endOfDay()])->count();
      $lab_count = [
         'today_lab' => $daily_lab,
         'weekly_lab' => $weekly_lab,
         'monthly_lab' => $monthly_lab
      ];
      $ipdBeds = Bed::where('bed_group_id',5)->count();
      $ipdBedOccupied = Bed::where('bed_group_id',5)->where('current_status','occupied')->count();
      $ipdBeds = [
         'total' => $ipdBeds,
         'occupied' => $ipdBedOccupied
      ];
      $avg_stay = PaymentBill::where('amount_for','Bed Charge')->avg('qty');
      $average_stay = round($avg_stay);
      return view('backend.admin.modules.dashboard2',compact('consultantCount','appoinrmentsCount','totalRevenue','patientCounts','emergencyPatients','icuBeds','lab_count','ipdBeds','average_stay'));
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
            //     $message->to($request->input('email'))->subject('OTP For Password Reset');
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
   
   public function setupRoles(){
    $admin = Role::create(['name' => 'admin']);
    $editor = Role::create(['name' => 'editor']);

    $edit = Permission::create(['name' => 'edit articles']);
    $delete = Permission::create(['name' => 'delete articles']);

    $admin->givePermissionTo([$edit, $delete]);
    $editor->givePermissionTo($edit);

    return 'Roles and permissions created!';
  }
   public function clearAll(){
      Artisan::call('view:clear');
      Artisan::call('route:clear');
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      return response()->json(['success' => 'Cache cleared successfully.']);
   }

}
