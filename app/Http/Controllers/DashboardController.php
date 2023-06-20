<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AppUser;
use App\Models\Cases;


use Illuminate\Support\Facades\Hash;
use Auth;
use DateTime;
use DB;
use Carbon\Carbon;
use Config;
use Symfony\Component\Console\Input\Input;
use DatePeriod;
use DateInterval;

class DashboardController extends Controller
{
     public function index()
    {
        $data = AppUser::orderBy('id','desc')->get();

        $reported_cases = Cases::where('status',0)
                    ->orderBy('id','desc')->get();

        $pending_cases = Cases::where('status',1)
                    ->orderBy('id','desc')->get();
                    
        $resolve_cases = Cases::where('status',2)
                    ->orderBy('id','desc')->get();

        $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        if (session()->has('initial_count')) {
              $initialCount = session('initial_count');
              $newCount = Cases::count();

              if ($newCount > $initialCount) {

                  $count = 1;
                  $initialCount = $newCount;   
                  session(['initial_count' => $initialCount]);
              } else {
                  $count = 0;
              }
          } else {
              $initialCount = Cases::count();
              $session = session(['initial_count' => $initialCount]);
          }

        if(Auth::check()){
            return view('dashboard')
                ->with('reported_cases',$reported_cases)
                ->with('pending_cases',$pending_cases)
                ->with('resolve_cases',$resolve_cases)
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('count',$count);
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
        
    }
}
