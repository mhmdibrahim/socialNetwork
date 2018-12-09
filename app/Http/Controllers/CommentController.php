<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_my_friend')->only([
            'showLikes'
        ]);
    }
    public function add(Request $request)
    {
        $request->validate([
            'body'=>'string|required',
        ]);
        DB::table('comments')->insert([
            'body'=>$request->body,
            'user_id'=>auth()->user()->id,
            'post_id'=>$request->post_id,
        ]);
        return redirect()->back();
    }

    public function delete($comment_id)
    {
        DB::table('comments')->where('id',$comment_id)->delete();
        return redirect()->back();
    }

    public function showLikes($user_id,$comment_id,$post_id)
    {
        $users = DB::table('comment_likes')->where('post_id',$post_id)
            ->where('comment_id',$comment_id)
            ->leftJoin('users','comment_likes.user_id','=','users.id')
            ->select('users.name as user_name')
            ->orderBy('comment_likes.id')
            ->get();
        return view('comment_likes')->with('users',$users);
    }

    public function like($comment_id,$post_id)
    {
        DB::table('comment_likes')->insert([
            'user_id'=>auth()->user()->id,
            'post_id'=>$post_id,
            'comment_id'=>$comment_id,
        ]);
        return redirect()->back();
    }

    public function unlike($like_id)
    {
        DB::table('comment_likes')->where('id',$like_id)->delete();
        return redirect()->back();
    }

}
