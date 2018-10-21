<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function add(Request $request)
    {
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

    public function showLikes($comment_id,$post_id)
    {
        $likes = DB::table('comment_likes')->where('post_id',$post_id)
            ->where('comment_id',$comment_id)->get();
        $post = DB::table('posts')->find($post_id);
        $user_id = $post->user_id;
        $user_friends = DB::table('user_friends')->where('user_from',$user_id)
            ->orWhere('user_to',$user_id)->get()->toArray();
        $friends=[];
        foreach ($user_friends as $user_friend){
            if ($user_friend->user_from = $user_id){
                $friends[]=$user_friend->user_to;
            }
            else{
                $friends[]=$user_friend->user_from;
            }
        }
        if (!in_array(auth()->user()->id,$friends)){
            return abort(404);
        }
        return view('comment_likes')->with('likes',$likes);
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
