<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUser;
use App\Models\Cases;
use App\Models\User;
use App\Models\EmergencyTeam;

use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

use Illuminate\Support\Facades\Hash;
use Auth;
use DateTime;
use DB;
use Carbon\Carbon;
use Config;
use Symfony\Component\Console\Input\Input;
use DatePeriod;
use DateInterval;

use App\Events\CasesCreated;

class CasesController extends Controller
{
    public function index()
    {
        $cases = Cases::orderBy('id','desc')->get();
        $user = User::orderBy('id','desc')->get();
        $app_user = AppUser::orderBy('id','desc')->get();

        $data = DB::select("SELECT
              users.name as uname,
              app_users.name as aname,
              cases.status,
              cases.updated_by,
              cases.created_by,
              cases.id
            FROM cases
            LEFT OUTER JOIN users
              ON users.id = cases.updated_by
            LEFT OUTER JOIN app_users
              ON app_users.id = cases.created_by");

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

        return view('cases.cases')
                ->with('cases',$cases)
                ->with('user',$user)
                ->with('app_user',$app_user)
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('count',$count);
    }

    public function info($id)
    {
        $cases = Cases::orderBy('id','desc')->get();
        $user = User::orderBy('id','desc')->get();
        $app_user = AppUser::orderBy('id','desc')->get();
       

        $data = DB::select("SELECT
              users.name as uname,
              app_users.name as aname,
              cases.status,
              cases.updated_by,
              cases.created_by,
              cases.case_name,
              cases.description,
              cases.file_name,
              cases.created_at,
              cases.updated_at,
              cases.id,
              cases.respondent,
              emergencyteam.team_name
            FROM cases
            LEFT OUTER JOIN users
              ON users.id = cases.updated_by
            LEFT OUTER JOIN app_users
              ON app_users.id = cases.created_by
            LEFT OUTER JOIN emergencyteam
              ON emergencyteam.id = cases.respondent
              WHERE cases.id = ".$id."");

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('cases.info')
                ->with('cases',$cases)
                ->with('user',$user)
                ->with('app_user',$app_user)
                ->with('data',$data)
                ->with('notif',$notif);
    }

    public function dispatch($id)
    {
        $cases = Cases::orderBy('id','desc')->get();
        $user = User::orderBy('id','desc')->get();
        $app_user = AppUser::orderBy('id','desc')->get();
        $eTeam = EmergencyTeam::where('status',0)
                              ->orderBy('id','desc')
                              ->get();


        $data = DB::select("SELECT * 
                            FROM cases
                            WHERE id = ".$id."");



         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('cases.dispatch')
                ->with('cases',$cases)
                ->with('user',$user)
                ->with('app_user',$app_user)
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('eTeam',$eTeam)
                ->with('id',$id);
    }

    public function store(Request $request)
    {


        $respondent = $request->teams;
        $case_id = $request->case_id;
        $uid = Auth::user()->id;

        //$accountSid = 'ACd0b9b45b89d9fa893d9e4c71b360ea87';
       // $authToken = 'f100eb7a2aa87bd27a3a460806acbcc6';
        //$client = new Client($accountSid, $authToken);

        if (class_exists('Nexmo\Client')) {
            $apiKey = '0a28de3f';
            $apiSecret = 'veSKESEk3LSYAAHu';
            $nexmo = new Client(new Basic($apiKey, $apiSecret));
        } else {
            // The class doesn't exist, handle the error
            echo "Nexmo\Client class does not exist.";
        }
                

        $current_timestamp = time();
        $current_date_time = date('Y-m-d H:i:s', $current_timestamp);

        $team = DB::selectOne("SELECT team_name
                          FROM emergencyteam
                          WHERE id = $respondent");
        
        $reporter = DB::selectOne("SELECT app_users.name,
                                          app_users.contact_number
                                          FROM cases
                                          LEFT OUTER JOIN app_users
                                          ON app_users.id = cases.created_by
                                          WHERE cases.id = $case_id");


        if ($team) {
          $teamName = $team->team_name;
            if ($reporter) {
              $reporterName = $reporter->name;
              $reporterContact = $reporter->contact_number;
              if(substr_count($reporterContact, "-") > 1) {
                    $removedHypen = str_replace("-", "", $reporterContact);
              }else{
                    $removedHypen = $reporterContact;
              } 
                if (substr($removedHypen, 0, 1) == '0') {
                  $modifiedContact = substr_replace($reporterContact, '+63', 0, 1);
                    if (strlen($modifiedContact) == 13) {


                      $response = $nexmo->sms()->send(
                              new \Vonage\SMS\Message\SMS($modifiedContact, 'BaDET', 'Hi! '.$reporterName. ', ' .$teamName. ' was dispatch to your reported emegency. Plase wait for their arrival. Thank you!')
                          );

                          $message = $response->current();

                          if ($message->getStatus() == 0) {
                              DB::table('cases')
                                    ->where('id', $case_id)
                                    ->update(['status' => 1,
                                              'respondent' => $respondent,
                                              'updated_by' => $uid,
                                              'updated_at' => $current_date_time]);

                                    DB::table('emergencyteam')
                                    ->where('id', $respondent)
                                    ->update(['status' => 1]);
                          } else {
                              echo "The message failed with status: " . $message->getStatus() . "\n";
                          }

                    }
                }elseif (substr($reporterContact, 0, 3) == '+63') { 
                  if (strlen($reporterContact) == 13) {


                      $response = $nexmo->sms()->send(
                              new \Vonage\SMS\Message\SMS($reporterContact, 'BaDET', 'Hi! '.$reporterName. ', ' .$teamName. ' was dispatch to your reported emegency. Plase wait for their arrival. Thank you!')
                          );

                          $message = $response->current();

                          if ($message->getStatus() == 0) {
                              DB::table('cases')
                                    ->where('id', $case_id)
                                    ->update(['status' => 1,
                                              'respondent' => $respondent,
                                              'updated_by' => $uid,
                                              'updated_at' => $current_date_time]);

                                    DB::table('emergencyteam')
                                    ->where('id', $respondent)
                                    ->update(['status' => 1]);
                          } else {
                              echo "The message failed with status: " . $message->getStatus() . "\n";
                          }

                    }
                }
            
          }

        }  


        $cases = Cases::orderBy('id','desc')->get();
        $user = User::orderBy('id','desc')->get();
        $app_user = AppUser::orderBy('id','desc')->get();
       

        $data = DB::select("SELECT
              users.name as uname,
              app_users.name as aname,
              cases.status,
              cases.updated_by,
              cases.created_by,
              cases.case_name,
              cases.description,
              cases.file_name,
              cases.created_at,
              cases.updated_at,
              cases.id,
              emergencyteam.team_name
            FROM cases
            LEFT OUTER JOIN users
              ON users.id = cases.updated_by
            LEFT OUTER JOIN app_users
              ON app_users.id = cases.created_by
            LEFT OUTER JOIN emergencyteam
              ON emergencyteam.id = cases.respondent
              WHERE cases.id = ".$case_id."");

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        return view('cases.info')
                ->with('cases',$cases)
                ->with('user',$user)
                ->with('app_user',$app_user)
                ->with('data',$data)
                ->with('notif',$notif);

    }
}
