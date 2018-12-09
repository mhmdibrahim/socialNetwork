<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\Post;
use Illuminate\Http\Request;
use App\User;
use App\User_Friend;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Array of users' ids that I have sent requests to
        $users = DB::table('requests')
            ->leftJoin('users','requests.user_from','=','users.id')
            ->select('users.*')
            ->where('requests.user_to','=',auth()->id)
            ->get();
//        dd($requests);
//        $requests = DB::table('requests')->where('user_to', auth()->user()->id)->get()->pluck('user_from');
//        // Get the users out of the ids array
//        $users = DB::table('users')->whereIn('id',$requests)->get();
        return view('request')->with('users', $users);
    }

    public function showFriends()
    {
        $users = DB::table('user_friends')
            ->leftJoin('users',function ($join){
                $join->on('user_friends.user_from','=','users.id');
                $join->orOn('user_friends.user_to','=','users.id');
                $join->on('user_friends.user_from','=',DB::raw(auth()->id()));
                $join->on('user_friends.user_to','=',DB::raw(auth()->id()));
            })
            ->distinct()
            ->select('users.*')
            ->whereNotIn('users.id',[auth()->id()])
            ->get();
        return view('myfriends')->with('friends',$users);
    }

    public function deleteFriend($id)
    {
        DB::table('user_friends')->where('user_to',auth()->user()->id)
            ->where('user_from',$id)
            ->orWhere('user_from',auth()->user()->id)
            ->where('user_to',$id)->delete();
        return redirect()->back();
    }

    public function notifications($user_id)
    {
        if ($user_id == auth()->id()){
            $posts = DB::table('posts')
                ->where('origin_user_id',$user_id)
                ->orderBy('id','desc')
                ->get();
        }
        else{
            abort(404);
        }
        return view('notification')->with('posts',$posts);
    }
}
