<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Request;
use App\User;
use App\User_Friend;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = Request::where('user_to', auth()->user()->id)->get()->pluck('user_from');
//        dd($requests);

        $users = User::whereIn('id', $requests)->get();
        return view('request')->with('users', $users);
    }

    public function profile($id)
    {
        $user = User::where('id', $id)->get();
        $friends = User_Friend::where('user_from', auth()->user()->id)
            ->orWhere('user_to', auth()->user()->id)->get();
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
            ->with('user', $user);
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
//        dd($request->all());
        $user = User::find(auth()->user()->id);
        $user->name = $request->name;
        $user->save();
        return redirect()->route('home');
    }

    public function createPost(\Illuminate\Http\Request $request, $id)
    {
        $post = new Post();
        $post->user_id = $id;
        $post->text = $request->post_create;
        $post->save();
        return redirect()->back();
    }

    public function acceptRequest($id)
    {
        $user_friend = new User_Friend();
        $user_friend->user_from = $id;
        $user_friend->user_to = auth()->user()->id;
        $user_friend->save();
        $request = Request::where('user_to', auth()->user()->id)->where('user_from', $id)->get()->pluck('id');
        Request::destroy($request);
        return redirect()->back();
    }

    public function cancelRequest($id)
    {
        $request = Request::where('user_to', auth()->user()->id)->where('user_from', $id)->get()->pluck('id');
        Request::destroy($request);
        return redirect()->back();

    }

    public function showFriends()
    {
        $friends = User_Friend::where('user_from', auth()->user()->id)
            ->orWhere('user_to', auth()->user()->id)->get();
//        dd($friends);
        $firendsIds = [];
        foreach ($friends as $friend) {
            if ($friend->user_from == auth()->user()->id) {
                $firendsIds[] = $friend->user_to;
            } else {
                $firendsIds[] = $friend->user_from;
            }
        }
        $users = User::whereIn('id', $firendsIds)->get();

        return view('myfriends')->with('friends', $users);
    }

    public function editMyProfile()
    {
        return view('edit_profile');
    }

    public function showposts($id)
    {
        $posts = Post::with('comments')->where('user_id', $id)->get();
//        dd($posts);
        $comments = Comment::with('post')->get();
//        dd($comments);
        return view('post')->with('posts', $posts)
            ->with('id', $id);

    }

    public function deletePost($id)
    {
        Post::destroy($id);
        return redirect()->back();
    }

    public function deleteFriend($id){
       User_Friend::where('user_to',auth()->user()->id)
                    ->where('user_from',$id)
                    ->orWhere('user_from',auth()->user()->id)
                    ->where('user_to',$id)->delete();
        return redirect()->back();
    }

    public function addComment(\Illuminate\Http\Request $request){
//        dd($request->all());
        $comment = new Comment();
        $comment->body = $request->body ;
        $comment->user_id = auth()->user()->id ;
        $comment->post_id = $request->post_id;
        $comment->save();
        return redirect()->back();
    }

    public function showComments($id){
        $post = Post::find($id) ;
        $comments = Comment::with('user')->where('post_id',$id)->get();
//        dd($comments);
        return view('comments')->with('comments',$comments)
                                    ->with('post',$post);
    }
}
