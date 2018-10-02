<?php

namespace App\Http\Controllers;

use App\Request;
use App\User;

class UserController extends Controller
{
    public function index(){
        $requests = Request::where('user_to',auth()->user()->id)->get()->pluck('user_from');
//        dd($requests);

        $users = User::whereIn('id',$requests)->get();
        return view('request')->with('users',$users);
    }
    public function profile($id){
        $user = User::where('id',$id)->get();
        return view('profile')->with('user',$user);
    }
    public  function updateProfile(Request $request , $id){
        $user = User::find($id);
        $user->name=$request->name;
        $user->save;
        return redirect()->route('home');
    }
}
