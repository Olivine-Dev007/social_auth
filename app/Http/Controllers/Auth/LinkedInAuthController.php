<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LinkedInAuthController extends Controller
{
    function redirect(){
        return Socialite::driver("linkedin")->redirect();
    }

    function callback(){
        $linkedinuser= Socialite::driver("linkedin")->user();
        // dd($linkedinuser);
        $credential['name']=$linkedinuser->name;
        $credential['email']=$linkedinuser->email;
        $credential['linkedin_id']=$linkedinuser->id;
        // dd($credential);
        $userexist=User::where('email',$linkedinuser->email)->first();
        if($userexist==null){
            $newuser = User::create($credential);
            Auth::login($newuser);
            return redirect('/dashboard');
        }
        else{
            $userexist['linkedin_id']=$linkedinuser->id;
            $userexist->save();
            // dd($userexist);
            Auth::login($userexist);
            return redirect('/dashboard');
        }
        
    }
}
