<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistersController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
           'firstname' => 'required',
            'lastname' => 'required',
            'email'=>'required',
            'password'=>'required',
            'phone'=>'required',
            'postal'=>'required',
            'city'=>'required',
        ]);



    }
}
