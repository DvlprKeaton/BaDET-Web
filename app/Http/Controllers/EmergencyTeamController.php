<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmergencyTeam;
use App\Models\User;
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

use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

class EmergencyTeamController extends Controller
{
    public function index()
    {
         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        $uid = Auth::user()->id;

        $holdteam = DB::select("
                        SELECT *
                        FROM users
                        WHERE id = $uid AND team_handled != 0");
      if($holdteam){
        foreach($holdteam as $ht){
          $tid = $ht->team_handled;  
        }
      }else{
          $tid = 0;
      }

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

        

        $data = EmergencyTeam::orderBy('id','desc')->get();
        return view('response.team')
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('holdteam',$holdteam)
                ->with('tid',$tid)
                ->with('count',$count);
    }

    public function create()
    {
         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

         $uHand = User::where('team_handled',0)
                              ->orderBy('id','desc')
                              ->get();

        return view('response.create')
        ->with('notif',$notif)
        ->with('uHand',$uHand);
    }

    public function store(Request $request)
    {

        $handler = $request->user_handler;

        $request->validate([
            'tname'    => 'required',
            'taddress' => 'required',
            'tcontact' => 'required']);


        $Team = EmergencyTeam::create([
            'team_name'         => $request->tname,
            'address'           => $request->taddress,
            'contact_number'    => $request->tcontact,
            'status'            => 0]);

        DB::table('users')
                      ->where('id', $handler)
                      ->update(['team_handled' => $Team->id]);


        return redirect()->route('team');

    }

    public function edit($id)
    {

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        $info = EmergencyTeam::where('id',$id)
                        ->orderBy('id','desc')
                        ->get();
        $handler = User::where('team_handled',$id)
                        ->orderBy('id','desc')
                        ->get();

         $uHand = User::where('team_handled',0)
                              ->orderBy('id','desc')
                              ->get();
               

        return view('response.edit')
                ->with('info',$info)
                ->with('notif',$notif)
                ->with('handler',$handler)
                ->with('id',$id)
                ->with('uHand',$uHand);

    }

    public function update(Request $request)
    {

        $handler = $request->user_handler;
        $tid = $request->team_id;

        $request->validate([
            'tname'    => 'required',
            'taddress' => 'required',
            'tcontact' => 'required']);

        DB::table('emergencyteam')
                      ->where('id', $tid)
                      ->update(['team_name' => $request->tname,
                                'address'   => $request->taddress,
                                'contact_number' => $request->tcontact]);

        $selectHandler = DB::select("
                        SELECT *
                        FROM users
                        WHERE id = $handler AND team_handled = $tid");

        if ($selectHandler) {
            DB::table('users')
                      ->where('id', $handler)
                      ->update(['team_handled' => $tid]);
        }else{
            $remove = DB::table('users')
                      ->where('team_handled', $tid)
                      ->update(['team_handled' => 0]);
            if ($remove) {
                 DB::table('users')
                      ->where('id', $handler)
                      ->update(['team_handled' => $tid]);
            }
            
        }


        return redirect()->route('team');

    }

    public function myTeam($id)
    {

         $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        $data = DB::select("SELECT * 
                            FROM emergencyteam
                            WHERE id = ".$id."");

        return view('response.myteam')
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('id',$id);

    }

    public function resolve($id)
    {

        $selectCase = DB::selectOne("
                        SELECT *
                        FROM cases
                        WHERE respondent = $id");

        if (class_exists('Nexmo\Client')) {
            $apiKey = '0a28de3f';
            $apiSecret = 'veSKESEk3LSYAAHu';
            $nexmo = new Client(new Basic($apiKey, $apiSecret));
        } else {
            // The class doesn't exist, handle the error
            echo "Nexmo\Client class does not exist.";
        }

        //$accountSid = 'ACd0b9b45b89d9fa893d9e4c71b360ea87';
       // $authToken = 'f100eb7a2aa87bd27a3a460806acbcc6';
      //  $client = new Client($accountSid, $authToken);

        $team = DB::selectOne("SELECT team_name
                          FROM emergencyteam
                          WHERE id = $id");

        if ($selectCase) {
            $case_id = $selectCase->id;

            $reporter = DB::selectOne("SELECT app_users.name,
                                          app_users.contact_number
                                          FROM cases
                                          LEFT OUTER JOIN app_users
                                          ON app_users.id = cases.created_by
                                          WHERE cases.id = $case_id");
        }

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
                              new \Vonage\SMS\Message\SMS($modifiedContact, 'BaDET', 'Hi! '.$reporterName. ', ' .$teamName. ' resolved your emergency report. Thank you for using BaDET')
                          );

                          $message = $response->current();

                          if ($message->getStatus() == 0) {
                             DB::table('cases')
                                                  ->where('id', $selectCase->id)
                                                  ->where('status', 1)
                                                  ->update(['status' => 2]);

                                             DB::table('emergencyteam')
                                                  ->where('id', $id)
                                                  ->update(['status' => 0]);
                          } else {
                              echo "The message failed with status: " . $message->getStatus() . "\n";
                          }

                    }
                }elseif (substr($reporterContact, 0, 3) == '+63') { 
                  if (strlen($reporterContact) == 13) {


                      $response = $nexmo->sms()->send(
                              new \Vonage\SMS\Message\SMS($reporterContact, 'BaDET', 'Hi! '.$reporterName. ', ' .$teamName. ' resolved your emergency report. Thank you for using BaDET')
                          );

                          $message = $response->current();

                          if ($message->getStatus() == 0) {
                              DB::table('cases')
                                                  ->where('id', $selectCase->id)
                                                  ->where('status', 1)
                                                  ->update(['status' => 2]);

                                             DB::table('emergencyteam')
                                                  ->where('id', $id)
                                                  ->update(['status' => 0]);
                          } else {
                              echo "The message failed with status: " . $message->getStatus() . "\n";
                          }

                    }
                }
            
          }

        }  
        
        $notif = DB::select("
            SELECT DISTINCT *
            FROM cases
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY id DESC");

        $data = DB::select("SELECT * 
                            FROM emergencyteam
                            WHERE id = ".$id."");
        
        return view('response.myteam')
                ->with('data',$data)
                ->with('notif',$notif)
                ->with('id',$id);

    }
}
