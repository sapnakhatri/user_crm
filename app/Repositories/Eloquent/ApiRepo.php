<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ApiInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserHistory;
use Log;

class ApiRepo implements ApiInterface {

	 public function getUserDetails($userid) {
	 	Log::info('User ID - '.@$userid);
	 	$user_history = new UserHistory();
	 	$save_user = User::find($userid);
	 	Log::info('User Json Data - '.@$save_user);
	 	$user_history->user_id = $userid;
	 	$user_history->first_name = $save_user->first_name;
        $user_history->last_name = $save_user->last_name;
        $user_history->email = $save_user->email ;
        $user_history->phone_number = $save_user->phone_number;
        $user_history->address = $save_user->address;
        $user_history->date_of_birth = $save_user->date_of_birth;
        $user_history->is_vaccinated = $save_user->is_vaccinated;
        $user_history->vaccine_name = $save_user->vaccine_name;
        $user_history->save();
        if($user_history->save()){
        	Log::info('User history Saved!');
        }
	 }
}