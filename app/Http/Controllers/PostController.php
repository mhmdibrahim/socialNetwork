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
            'showComments',
            'share',
            'showLikes',
        ]);
    }
    public function index($user_id)
    {
        $isMe=false;
       if($user_id == auth()->user()->id){
           $isMe = true ;
       }

        $posts = DB::table('posts')
//          ->leftJoin('users','posts.user_id','=','users.id')
            ->leftJoin('likes','posts.id','=','likes.post_id')
            ->leftJoin('comments','posts.id','=','comments.post_id')
            ->select('posts.*', DB::raw('COUNT(distinct(likes.id)) as likesCount'),
                DB::raw('COUNT(distinct(comments.id)) as commentsCount'))
            ->where('posts.user_id',$user_id)
            ->groupBy('posts.id')
            ->orderBy('posts.id','desc')->get();

        return view('post')->with('posts', $posts)
            ->with('isMe',$isMe)
            ->with('users_friends',request()->friendsIds);
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
        DB::table('posts')->where('id', $post_id)->delete();
        return redirect()->back();
    }

    public function share($user_id,$post_id)
    {
        //Friends of te post creator
        DB::table('posts')->insert([
            'user_id' => auth()->user()->id,
            'text' => null,
            'origin_user_id' => $user_id,
            'orgin_post_id' => $post_id,
        ]);
        return redirect()->back();
    }

    public function showLikes($user_id,$post_id)
    {
        $users = DB::table('likes')
            ->where('post_id', $post_id)
            ->leftJoin('users','likes.user_id','=','users.id')
            ->select('users.name as user_name')
            ->get();
        return view('likes')->with('users', $users);
    }

    public function showComments($user_id, $post_id)
    {
        $post = DB::table('posts')->find($post_id);
        if(!$post){
            abort(404);
        }
        $comments = DB::table('comments')
            ->leftJoin('comment_likes','comments.id','=','comment_likes.comment_id')
            ->leftJoin('users','comments.user_id','=','users.id')
            ->select('comments.*', DB::raw('COUNT(comment_likes.comment_id) as likesCount'),
                'users.name as user_name','comment_likes.id as comment_like_id')
            ->where('comments.post_id','=',$post_id)
            ->groupBy('comments.id','comment_likes.id')
            ->get();
        return view('comments')->with('comments',$comments)
            ->with('post',$post);
    }
}
