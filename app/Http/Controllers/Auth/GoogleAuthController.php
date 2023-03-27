<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class GoogleAuthController extends Controller
{
    //
    function redirect(){
        return Socialite::driver("google")->redirect();
    }

    function callback(){
        $googleuser= Socialite::driver("google")->user();
        // dd($googleuser);
        $credential['name']=$googleuser->name;
        $credential['email']=$googleuser->email;
        $credential['google_id']=$googleuser->id;
        // dd($credential);
        $userexist=User::where('email',$googleuser->email)->first();
        if($userexist==null){
            $newuser = User::create($credential);
            Auth::login($newuser);
            return redirect('/dashboard');
        }
        else{
            $userexist['google_id']=$googleuser->id;
            $userexist->save();
            Auth::login($userexist);
            return redirect('/dashboard');
        }
        
    }
}
