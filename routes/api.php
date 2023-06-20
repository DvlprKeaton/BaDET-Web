<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Cases;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/notification-sound', function () {
    return asset('sounds/notification.mp3');
});

$newCountReachedOne = false;

function checkUpdates() {
    global $newCountReachedOne;

    $newCount = Cases::count();
    $ctr = Cache::get('ctr');

    $initialCount = Cache::get('initial_count');
    if (!$initialCount) {
        $initialCount = $newCount;
        Cache::put('initial_count', $initialCount, 60); // store initial count for 1 hour
        return checkUpdates();
    }

    if ($ctr == 1 && $newCount == 1) {
         return response()->json(['newCount' => $newCount, 'initialCount' => $ctr, 'count' => 3]);
    }
    if ($newCount === 0) {
        Cache::put('ctr', 0, 60);
        return response()->json(['newCount' => $newCount, 'initialCount' => $ctr, 'count' => 0]);
    }elseif ($newCount == 1 && $ctr == 0) {
        Cache::put('ctr', 1, 60);
        return response()->json(['newCount' => $newCount, 'initialCount' => $ctr, 'count' => 1]);
    }

    if ($newCount < $initialCount) {
        Cache::flush();
        return checkUpdates();
    }

    if ($newCount > $initialCount) {
        $count = 1;
        Cache::put('initial_count', $newCount, 60); // update initial count with new count value for 1 hour

        // Do something when new cases are added
        return response()->json(['newCount' => $newCount, 'initialCount' => $initialCount, 'count' => $count]);
    } else {
        $count = 0;
        return response()->json(['newCount' => $newCount, 'initialCount' => $initialCount, 'count' => $count]);
    }
}




Route::get('/check-updates', function () {
    return checkUpdates();
});
