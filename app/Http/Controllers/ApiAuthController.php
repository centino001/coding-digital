<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controller\BaseController as BaseController;
use App\Models\user;
use Validator;

class ApiAuthController extends BaseController
{
    public function register(Request $request){
        $validator =Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'require|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->$sendError('validator error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        // generate auth token
        $success['token'] = $user->createToken("AuthToken")->accessToken;
        $success['account'] = $user;

        return $this->sendResponse($success, 'account created successfully!!');
    }
}
