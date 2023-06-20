<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SmsController extends Controller
{

    public function index()
    {

        return view('sms');
    }

    public function sendSms(Request $request)
    {
        $to = $request->recipient;

        $accountSid = 'ACd0b9b45b89d9fa893d9e4c71b360ea87';
        $authToken = 'f100eb7a2aa87bd27a3a460806acbcc6';

        $client = new Client($accountSid, $authToken);

        $message = $client->messages->create(
            $to, // To number
            array(
                'from' => '+15674852915',
                'body' => 'Hello, this is a test message!'
            )
        );

        echo $message->sid;
    }
}
