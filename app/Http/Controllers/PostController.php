<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_my_friend')->only([
            'index' ,
            'showComments'
        ]);
    }
    public function index($user_id)
    {
        $posts = DB::table('posts')->where('user_id', $user_id)->
                orWhere('origin_user_id',$user_id)->orderBy('id', 'desc')->get();

        return view('post')->with('posts', $posts)
            ->with('id', $user_id)
            ->with('users_friends', request()->friendsIds);
    }

    public function create(Request $request)
    {
        $request->validate([
            'post_create'=>'string|required',
        ]);
        DB::table('posts')->insert([
            'user_id' => auth()->id(),
            'text' => $request->post_create,
        ]);
        return redirect()->back();

    }

    public function like($post_id)
    {
        DB::table('likes')->insert([
            'user_id' => auth()->user()->id,
            'post_id' => $post_id,
        ]);
        return redirect()->back();
    }

    public function unlike($post_id)
    {

        DB::table('likes')->where('post_id', $post_id)
            ->where('user_id', auth()->user()->id)->delete();

        return redirect()->back();
    }

    public function delete($post_id)
    {
//        dd($post_id);
        DB::table('posts')->where('id', $post_id)->delete();
        return redirect()->back();
    }

    public function share($post_id)
    {
        //Friends of te post creator
        $post = DB::table('posts')->find($post_id);
        $user_id = $post->user_id;
        $user_friends = DB::table('user_friends')->where('user_from', $user_id)
            ->orWhere('user_to', $user_id)->get()->toArray();

        $friends = [];
        foreach ($user_friends as $user_friend) {
            if ($user_friend->user_from == $user_id) {
                $friends[] = $user_friend->user_to;
            } else {
                $friends[] = $user_friend->user_from;
            }
        }
        // Am I in the friends of the post creator?
        if (!in_array(auth()->user()->id, $friends)) {
           return abort(404);
        }
        DB::table('posts')->insert([
            'user_id' => auth()->user()->id,
            'text' => null,
            'origin_user_id' => $user_id,
            'orgin_post_id' => $post_id,
        ]);
        return redirect()->back();
    }

    public function showLikes($post_id)
    {
        $likes = DB::table('likes')->where('post_id', $post_id)->get();
        $post = DB::table('posts')->find($post_id);
        $user_id = $post->user_id;
        $user_friends = DB::table('user_friends')->where('user_from', $user_id)
            ->orWhere('user_to', $user_id)->get()->toArray();
        $friends = [];
        foreach ($user_friends as $user_friend) {
            if ($user_friend->user_from == $user_id) {
                $friends[] = $user_friend->user_to;
            } else {
                $friends[] = $user_friend->user_from;
            }
        }
//        if (!in_array(auth()->user()->id, $friends)) {
////              abort(404);
//        }
        return view('likes')->with('likes', $likes);

    }

    public function showComments($user_id, $post_id)
    {
        $post = DB::table('posts')->find($post_id);
        if(! $post){
            abort(404);
        }
        $comments = DB::table('comments')->where('post_id',$post_id)
                                        ->orderBy('id','desc')->get();
        return view('comments')->with('comments',$comments)
            ->with('post',$post);
    }


}
