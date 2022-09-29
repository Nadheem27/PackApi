<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Auth;

class AuthenticationController extends CommonController
{
    public function index(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response($this->returnResponse(3, $validator->messages()));

        $auth = Auth::guard('admin-web')->attempt(['email' => $request->email, 'password' => $request->password]);

        if(!$auth)
            return response($this->returnResponse(1, 'Invalid Credentials'));

        $admin = Admin::where('email', $request->email)->first();

        $data = array(
            'email' => $admin->email,
            'name' => $admin->name,
            'token' => $admin->createToken('Personal Access Token')->accessToken
        );

        return response($this->returnResponse(0, 'Successfully Logged In', $data));
    }

    public function logout(Request $request)
    {
        $current_token = auth()->user()->token();
        $token = $request->user()->tokens->find($current_token);
        $token->revoke();

        return response($this->returnResponse(0, 'Logged Out Successfully'));
    }
}
