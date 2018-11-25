<?php

namespace App\Http\Controllers\API;

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
        $profileOwner = DB::table('users')->where('id', $user_id)->first();
        if ($profileOwner == null) {
            abort(404);
        }
        $postsCount = DB::table('posts')->where('user_id', $user_id)->count();
        $isMe = auth()->id() == $user_id;
        $isMyFriend = false;
        if (!$isMe) {
            $isMyFriend = (bool)DB::table('user_friends')
                ->where(function ($query) use ($user_id) {
                    $query->where('user_from', auth()->id())
                        ->where('user_to', $user_id);
                })
                ->orWhere(function ($query) use ($user_id) {
                    $query->where('user_from', $user_id)
                        ->where('user_to', auth()->id());
                })->count();
        }

        return response()->json([
            'is_my_friend' => $isMyFriend,
            'is_me' => $isMe,
            'profile_owner' => $profileOwner,
            'posts_count' => $postsCount
        ]);
    }

    public function edit()
    {
        return view('edit_profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'=>'string|required',
        ]);
        DB::table('users')->where('id',auth()->user()->id)
            ->update([
                'name' => $request->name ,
            ]);
        return redirect('/profile/' . auth()->id());
    }
}
