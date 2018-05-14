<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;


class HomeController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth');
        
    }

    public function index()
    {   
        $subject = User::find(Auth::user()->id)->subject;
        return view('home')->with('subject',$subject);
        //echo $subject[0];
    }
}
