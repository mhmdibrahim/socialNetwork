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
        $isMe = auth()->id() == $user_id;
        $isMyFriend = false;
        if (!$isMe) {
            $isMyFriend = (bool) DB::table('user_friends')
                ->where(function ($query) use ($user_id) {
                    $query->where('user_from', auth()->id())
                        ->where('user_to', $user_id);
                })
                ->orWhere(function ($query) use ($user_id) {
                    $query->where('user_from', $user_id)
                        ->where('user_to', auth()->id());
                })->count();
        }
////        dd($friends);
////        $friends = User_Friend::where('user_from', auth()->user()->id)
////            ->orWhere('user_to', auth()->user()->id)->get();
//        $my_friends = [];
//        foreach ($friends as $friend) {
//            if ($friend->user_from == auth()->user()->id) {
//                $my_friends[] = $friend->user_to;
//            } else {
//                $my_friends[] = $friend->user_from;
//            }
//        }
//        dd(request()->all());
//        $my_friends[] = auth()->user()->id;
        return view('profile')
            ->with('isMyFriend', $isMyFriend)
            ->with('isMe', $isMe)
            ->with('user', $user)
            ->with('posts', $posts);
    }

    public function edit()
    {
        return view('edit_profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
        ]);
        DB::table('users')->where('id', auth()->user()->id)
            ->update([
                'name' => $request->name,
            ]);
        return redirect('/profile/' . auth()->id());
    }
}
