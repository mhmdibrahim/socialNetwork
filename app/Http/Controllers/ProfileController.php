<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($user_id)
    {
        // Get count of the posts
        $posts = DB::table('posts')->where('user_id', $user_id)->count();
//        $posts = Post::count()-1;
        $user = DB::table('users')->where('id', $user_id)->first();
        if ($user == null) {
            abort(404);
        }
//        $user = User::where('id', $id)->get();
        $friends = DB::table('user_friends')->where('user_from', auth()->user()->id)
            ->orWhere('user_to', auth()->user()->id)->get();
//        dd($friends);
//        $friends = User_Friend::where('user_from', auth()->user()->id)
//            ->orWhere('user_to', auth()->user()->id)->get();
        $my_friends = [];
        foreach ($friends as $friend) {
            if ($friend->user_from == auth()->user()->id) {
                $my_friends[] = $friend->user_to;
            } else {
                $my_friends[] = $friend->user_from;
            }
        }
        $my_friends[] = auth()->user()->id;
        return view('profile')->with('myfriends', $my_friends)
            ->with('user', $user)
            ->with('posts', $posts);
    }

    public function edit()
    {
        return view('edit_profile');
    }

    public function update(Request $request)
    {
        DB::table('users')->where('id',auth()->user()->id)
            ->update([
                'name' => $request->name ,
            ]);
//        $user = User::find(auth()->user()->id);
//        $user->name = $request->name;
//        $user->save();
        return redirect('/profile/' . auth()->id());
    }
}
