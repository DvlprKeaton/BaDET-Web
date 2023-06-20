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

class MapController extends Controller
{
    public function index()
    {
        $data = DB::select("SELECT
              users.name as uname,
              app_users.name as aname,
              cases.*
            FROM cases
            LEFT OUTER JOIN users
              ON users.id = cases.updated_by
            LEFT OUTER JOIN app_users
              ON app_users.id = cases.created_by
              WHERE status = 0 OR status = 1");

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

        $cases = Cases::orderBy('id','desc')->get();
        return view('maps.map')
            ->with('cases',$cases)
            ->with('data',$data)
            ->with('notif',$notif)
            ->with('count',$count);
    }
}
