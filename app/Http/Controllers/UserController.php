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
        $requests = DB::table('requests')->where('user_to', auth()->user()->id)->get()->pluck('user_from');
        // Get the users out of the ids array
        $users = DB::table('users')->whereIn('id',$requests)->get();

        return view('request')->with('users', $users);
    }

    public function profile($id)
    {
        // Get count of the posts
        $posts = DB::table('posts')->where('user_id',$id)->count() ;
//        $posts = Post::count()-1;
        $user = DB::table('users')->where('id',$id)->first();
        if($user == null){
            abort(404);
        }
//        $user = User::where('id', $id)->get();
        $friends = DB::table('user_friends')->where('user_from',auth()->user()->id)
                                                ->orWhere('user_to',auth()->user()->id)->get();
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
            ->with('posts',$posts);
    }

    public function updateProfile(Request $request)
    {
         DB::table('users')->where('id',auth()->user()->id)
        ->update([
            'name' => $request->name ,
        ]);
//        $user = User::find(auth()->user()->id);
//        $user->name = $request->name;
//        $user->save();
        return redirect(auth()->id()  . '/profile');
    }

    public function createPost(Request $request)
    {
        DB::table('posts')->insert([
            'user_id'=>auth()->id(),
            'text'=>$request->post_create,
        ]);
//        $post = new Post();
//        $post->user_id = $id;
//        $post->text = $request->post_create;
//        $post->save();
        return redirect()->back();
    }

    public function acceptRequest($id)
    {
        DB::table('user_friends')->insert([
            'user_from'=>$id,
            'user_to'=>auth()->user()->id,
        ]);
//        $user_friend = new User_Friend();
//        $user_friend->user_from = $id;
//        $user_friend->user_to = auth()->user()->id;
//        $user_friend->save();
//        $request = Request::where('user_to', auth()->user()->id)->where('user_from', $id)->get()->pluck('id');
//        Request::destroy($request);
        $request = DB::table('requests')->where('user_to', auth()->user()->id)
            ->where('user_from', $id)->get()->pluck('id');
        DB::table('requests')->where('id',$request)->delete();
        return redirect()->back();
    }

    public function cancelRequest($id)
    {
        $request = DB::table('requests')->where('user_to', auth()->user()->id)->where('user_from', $id)->get()->pluck('id');
//        $request = Request::where('user_to', auth()->user()->id)->where('user_from', $id)->get()->pluck('id');
        //        Request::destroy($request);
        DB::table('requests')->where('id',$request)->delete();
        return redirect()->back();

    }

    public function showFriends()
    {
        $friends = DB::table('user_friends')->where('user_from', auth()->user()->id)
                        ->orWhere('user_to', auth()->user()->id)->get();
//        $friends = User_Friend::where('user_from', auth()->user()->id)
//            ->orWhere('user_to', auth()->user()->id)->get();
//        dd($friends);
        $firendsIds = [];
        foreach ($friends as $friend) {
            if ($friend->user_from == auth()->user()->id) {
                $firendsIds[] = $friend->user_to;
            } else {
                $firendsIds[] = $friend->user_from;
            }
        }
          $users =DB::table('users')->whereIn('id',$firendsIds)->get();
//        $users = User::whereIn('id', $firendsIds)->get();

        return view('myfriends')->with('friends', $users);
    }

    public function editMyProfile()
    {
        return view('edit_profile');
    }

    public function showposts($id)
    {
        // TODO : Try to make this line with DB instead
//
        // We need to show posts of some specific user with $id
        // Get all the posts by him
        $posts = DB::table('posts')->where('user_id', $id)->get();
        // Get all the friends of user with $id
        $user_friend =DB::table('user_friends')->where('user_from',$id)
                                ->orWhere('user_to',$id)->get();
        $friends=[];
        foreach ($user_friend as $friend){
            if ($friend->user_from == $id){
                $friends[] = $friend->user_to ;
            }
            else{
                $friends[] = $friend->user_from ;
            }
        }

        // if the current authenticated user is not in his friends
        if(!in_array(auth()->user()->id,$friends) && auth()->user()->id !=$id){
            return abort(404);
        }

        return view('post')->with('posts', $posts)
                                ->with('id', $id)
                                ->with('users_friends',$friends);
    }
    public function deletePost($id)
    {
        DB::table('posts')->where('id',$id)->delete();
//        Post::destroy($id);
        return redirect()->back();
    }

    public function deleteFriend($id){
        DB::table('user_friends')->where('user_to',auth()->user()->id)
            ->where('user_from',$id)
            ->orWhere('user_from',auth()->user()->id)
            ->where('user_to',$id)->delete();
//       User_Friend::where('user_to',auth()->user()->id)
//                    ->where('user_from',$id)
//                    ->orWhere('user_from',auth()->user()->id)
//                    ->where('user_to',$id)->delete();
        return redirect()->back();
    }

    public function addComment(\Illuminate\Http\Request $request){
//        dd($request->all());
        DB::table('comments')->insert([
            'body'=>$request->body,
            'user_id'=>auth()->user()->id,
            'post_id'=>$request->post_id,
        ]);
//        $comment = new Comment();
//        $comment->body = $request->body ;
//        $comment->user_id = auth()->user()->id ;
//        $comment->post_id = $request->post_id;
//        $comment->save();
        return redirect()->back();
    }

    public function showComments($id){
//        $comment_likes = DB::table('comment_likes')->where('post_id',$id)->count();
//        $post = Post::find($id) ;
        $post = DB::table('posts')->find($id);
        $user_id = $post->user_id ;
        $user_friend =DB::table('user_friends')->where('user_from',$user_id)
            ->orWhere('user_to',$user_id)->get();
        $friends=[];
        foreach ($user_friend as $friend){
            if ($friend->user_from == $user_id){
                $friends[] = $friend->user_to ;
            }
            else{
                $friends[] = $friend->user_from ;
            }
        }
//        dd($friends);
        if(!in_array(auth()->user()->id,$friends) && auth()->user()->id !=$user_id){
            return abort(404);
        }

        $comments = DB::table('comments')->where('post_id',$id)->get();
//        $comments = Comment::with('user')->where('post_id',$id)->get();
//        dd($comments);
        return view('comments')->with('comments',$comments)
                                    ->with('post',$post);
    }

    public function deleteComment($id){
        DB::table('comments')->where('id',$id)->delete();
//        Comment::destroy($id);
        return redirect()->back();

    }

    public function showLikes($id){

        $likes =DB::table('likes')->where('post_id',$id)->get();
        $post = DB::table('posts')->find($id);
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
//        $likes = Like::where('post_id',$id)->get();
//        dd($likes);

        return view('likes')->with('likes',$likes);
    }
    public function putLike($id)
    {
        DB::table('likes')->insert([
            'user_id'=>auth()->user()->id,
            'post_id'=>$id,
        ]);
//            $like = new Like();
//            $like->user_id = auth()->user()->id;
//            $like->post_id = $id;
//            $like->save();
        return redirect()->back();
    }

    public function unlike($id)
    {
        DB::table('likes')->where('post_id',$id)
            ->where('user_id',auth()->user()->id)->delete();
//        Like::where('post_id',$id)
//            ->where('user_id',auth()->user()->id)->delete();
        return redirect()->back();
    }

    public function showCommentLikes($id1 ,$id2)
    {
        $likes = DB::table('comment_likes')->where('post_id',$id1)
                            ->where('comment_id',$id2)->get();

        $post = DB::table('posts')->find($id1);
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
    public function likeComment($id1,$id2)
    {
         DB::table('comment_likes')->insert([
            'user_id'=>auth()->user()->id,
            'post_id'=>$id1,
            'comment_id'=>$id2,
        ]);
        return redirect()->back();
    }

    public function unlikeComment($id)
    {
        DB::table('comment_likes')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function share($origin_user_id , $origin_post_id){

        $user_friends = DB::table('user_friends')->where('user_from',$origin_user_id)
            ->orWhere('user_to',$origin_user_id)->get()->toArray();
        $friends=[];
        foreach ($user_friends as $user_friend){
            if ($user_friend->user_from = $origin_user_id){
                $friends[]=$user_friend->user_to;
            }
            else{
                $friends[]=$user_friend->user_from;
            }
        }
        if (!in_array(auth()->user()->id,$friends)){
            return abort(404);
        }
        DB::table('posts')->insert([
            'user_id'=>auth()->user()->id,
            'text'=>null,
            'origin_user_id'=>$origin_user_id,
            'orgin_post_id'=>$origin_post_id,
        ]);
        return redirect()->back();
    }
}
