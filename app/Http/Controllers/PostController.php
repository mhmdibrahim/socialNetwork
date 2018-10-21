<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index($user_id)
    {
        $posts = DB::table('posts')->where('user_id', $user_id)->get();
        // Get all the friends of user with $id
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

        // if the current authenticated user is not in his friends
        if(!in_array(auth()->user()->id,$friends) && auth()->user()->id !=$user_id){
            return abort(404);
        }

        return view('post')->with('posts', $posts)
            ->with('id', $user_id)
            ->with('users_friends',$friends);
    }

    public function create(Request $request)
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

    public function like($post_id)
    {
        DB::table('likes')->insert([
            'user_id'=>auth()->user()->id,
            'post_id'=>$post_id,
        ]);
//            $like = new Like();
//            $like->user_id = auth()->user()->id;
//            $like->post_id = $id;
//            $like->save();
        return redirect()->back();
    }

    public function unlike($post_id)
    {

        DB::table('likes')->where('post_id',$post_id)
            ->where('user_id',auth()->user()->id)->delete();
//        Like::where('post_id',$id)
//            ->where('user_id',auth()->user()->id)->delete();
        return redirect()->back();
    }

    public function delete($post_id)
    {
        DB::table('posts')->where('id',$post_id)->delete();
//        Post::destroy($id);
        return redirect()->back();
    }
}
