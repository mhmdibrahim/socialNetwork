@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        @if($id == auth()->user()->id)
            <form method="post" action="/post/create">
                {{csrf_field()}}
                <div class="form-group">
                    <label>Create New Post</label>
                </div>
                <div class="form-group">
                    <textarea rows="4" cols="50" name="post_create" placeholder="Write Your Post"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Add Post</button>
                </div>
            </form>
        @endif
    </div>
    <hr>
    <div class="text-md-center">
        <table class="table table-hover">
            <tr>
                <th>Show All posts</th>
            </tr>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    {{--The post is not shared from someone else--}}
                    @if($post->origin_user_id == null)
                        <td><b>{{$post->text}}</b>
                            {{--enable delete if the viewer of the profile is the profile owner--}}
                            @if(auth()->user()->id== $post->user_id)
                                <a href="/{{$post->id}}/post/delete" class="float-md-right btn btn-danger">Delete
                                    Post</a>
                            @endif
                            <br>
                            @php
                                $likes= \App\Like::where('post_id',$post->id)->count();
                                $comments = \App\Comment::where('post_id',$post->id)->count();
                                $like  = \App\Like::where('post_id',$post->id)
                                                    ->where('user_id',auth()->user()->id)->count();
                            @endphp

                            @if($like > 0)
                                <a href="/post/{{$post->id}}/unlike" class="btn btn-outline-info tab-pane">UnLike</a>
                                <b> ({{$likes}} likes) </b>
                            @else
                                <a href="/post/{{$post->id}}/like" class="btn btn-outline-info">Like</a> <b> ({{$likes}}
                                    likes) </b>
                            @endif

                            <a href="/post/{{$post->id}}/likes" class="btn btn-outline-info"><b>show All Likes</b></a>
                            @if($post->user_id != auth()->user()->id)
                                <a href="/user/{{$post->user_id}}/post/{{$post->id}}/share" class="btn btn-dark"><b>Share</b></a>
                            @endif
                            <br> <br>
                            <a href="/post/{{$post->id}}/comments" class="btn btn-primary">Show Post Comments</a>
                            <b>( {{$comments}} Comments ) </b>
                        </td>
                    @elseif($post->origin_user_id !=auth()->user()->id)
                        <td>
                            @php
                                // The post creator
                                    $friend = \App\User::find($post->origin_user_id);
                                // The owner of the profile being showed now
                                    $posted = \App\User::find($post->user_id);
                                // Friends of the post creator
                                    $user_friend = \Illuminate\Support\Facades\DB::table('user_friends')->where('user_from',$post->origin_user_id)->orWhere('user_to',$post->origin_user_id)->get();
                                    $friends=[];
                                    foreach($user_friend as $value){
                                        if ($value->user_from == $post->origin_user_id){
                                            $friends[]=$value->user_to ;
                                        }
                                        else{
                                            $friends[]=$value->user_from;
                                        }
                                    }
                            @endphp
                            <a href="/{{$post->user_id}}/profile">{{$posted->name}}</a> Shared <a
                                href="/{{$friend->id}}/profile">{{$friend->name}}'s</a>
                            @php
                                $origin_post= \Illuminate\Support\Facades\DB::table('posts')->find($post->orgin_post_id);
                            @endphp
                            <a href="/post/{{$post->orgin_post_id}}/comments">Post</a>
                            @if(in_array(auth()->user()->id,$friends))
                                <br>
                                <h3>{{$origin_post->text}}</h3>
                            @else
                                <br>NoT Allowed To show Post
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td>No Posts Founded</td>

                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
