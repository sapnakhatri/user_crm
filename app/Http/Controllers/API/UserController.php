<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Jobs\UserProcess;
use App\Models\UserHistory;
use Carbon\Carbon;

class UserController extends Controller
{
    public function create(Request $request)
    {
    	$rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email',
            'phone_number' => 'required|string|max:10',
            'address' => 'string',
            'date_of_birth' => 'string|date_format:Y-m-d|before:today',
            'is_vaccinated' => 'string|in:YES,NO',
            'vaccine_name' => 'string|required_if:is_vaccinated,==,YES|in:COVAXIN,COVISHIELD'
        ];

        $validator = Validator::make(array_map('trim', ($request->all())),$rules);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }else{
        	$user = new User;
	        $user->first_name = $request->first_name;
	        $user->last_name = $request->last_name;
	        $user->email = $request->email;
	        $user->phone_number = $request->phone_number;
	        $user->address = $request->address;
	        $user->date_of_birth = $request->date_of_birth;
	        $user->is_vaccinated = $request->is_vaccinated;
	        $user->vaccine_name = $request->vaccine_name;
	        $user->save();

	        $token = $user->createToken('auth_token')->plainTextToken;
	        if($user->save()){
	        	$this->dispatch(new UserProcess($user->id));
	        
	        	return response()
	            ->json(['data' => $user,'status' => '200','message' => 'User Added Successfully','access_token' => $token, 'token_type' => 'Bearer']);
	        }else{
	        	return response()
	            ->json(['data' => $user,'status' => '409','message' => 'Something went wrong!']);
	        }
	    }
    } 
}
