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
        $user = DB::table('posts')
            ->leftJoin('users','posts.user_id','users.id')
            ->select(DB::raw('count(*) as postsCount'),'users.*')
            ->groupBy('posts.user_id')
            ->where('posts.user_id','=', $user_id)->first();
        if ($user == null) {
            abort(404);
        }
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

        return view('profile')
            ->with('isMyFriend', $isMyFriend)
            ->with('isMe', $isMe)
            ->with('user', $user);
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
