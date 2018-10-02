<?php

namespace App\Http\Controllers;

use App\User;
use App\User_Friend;
use App\Request;
use function PhpParser\filesInDir;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Request::where('user_from',auth()->user()->id)->get()->pluck('user_to')->toArray();
//        dd($requests->toArray());
        $user_friends = User_Friend::where('user_from',auth()->user()->id)
                                    ->orWhere('user_to',auth()->user()->id)->get();
        $friendsIds = [];
        foreach ($user_friends as $friend){
            if($friend->user_from == auth()->id()){
                $friendsIds[] = $friend->user_to;
            }else{
                $friendsIds[] = $friend->user_from;
            }
        }
//        dd($friendsIds);
//        dd($user_friends);
        $users = User::whereNotIn('id',$friendsIds)->paginate(10);
//        dd($users);
        return view('home')->with('members',$users)
                                ->with('requests',$requests);
    }
}
