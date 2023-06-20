<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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

class UsersController extends Controller
{
    public function index()
    {

        $data = AppUser::orderBy('id','desc')->get();

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

        return view('users.user')
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('count',$count);
    }

    public function show()
    {

        $uid = Auth::user()->id;
        $data = User::where('id',$uid)
                    ->orderBy('id','desc')->get();

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('users.profile')
                ->with('data',$data)
                ->with('notif',$notif);
    }

    public function edit()
    {

        $uid = Auth::user()->id;
        $data = User::where('id',$uid)
                    ->orderBy('id','desc')->get();

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('users.edit')
                ->with('data',$data)
                ->with('notif',$notif);
    }

    public function update(Request $request)
    {
        $uid = Auth::user()->id;
        $name = $request->name;
        $email = $request->email;
        $pword = $request->pword;

        if ($request->pword == $request->cpword) {
            DB::table('users')
                      ->where('id', $uid)
                      ->update(['name' => $name,
                                'email' => $email,
                                'password' => Hash::make($pword)]);
        }

        
        $data = User::where('id',$uid)
                    ->orderBy('id','desc')->get();

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('users.profile')
                ->with('data',$data)
                ->with('notif',$notif);
    }
}
