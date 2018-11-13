@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        @if($id == auth()->user()->id)
            <form method="post" action="/posts/create">
                @csrf
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
                                <form class="d-inline" method="post" action="/posts/{{$post->id}}/delete">
                                    @csrf
                                    <button type="submit" class="float-md-right btn btn-danger">Delete
                                    Post</button>
                                </form>
                            @endif
                            <br>
                            @php
                                $likes= \App\Like::where('post_id',$post->id)->count();
                                $comments = \App\Comment::where('post_id',$post->id)->count();
                                $like  = \App\Like::where('post_id',$post->id)
                                                    ->where('user_id',auth()->user()->id)->count();
                            @endphp

                            @if($like > 0)
                                <form class="d-inline" method="post" action="/posts/{{$post->id}}/unlike">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-info">UnLike</button> <b> ({{$likes}}
                                        likes) </b>
                                </form>
                            @else
                                <form class="d-inline" method="post" action="/posts/{{$post->id}}/like">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-info">Like</button> <b> ({{$likes}}
                                    likes) </b>
                                </form>
                            @endif
                                    <a type="submit" class="btn btn-outline-info" href="/posts/{{$post->id}}/likes"><b>show All Likes</b></a>
                                @if($post->user_id != auth()->user()->id)
                                <form class="d-inline" method="post" action="/posts/{{$post->id}}/share">
                                    @csrf
                                    <button type="submit" class="btn btn-dark"><b>Share</b></button>
                                </form>
                            @endif
                            <br> <br>
                            <a href="/posts/{{$post->user_id}}/{{$post->id}}/comments" class="btn btn-primary">Show Post Comments</a>
                            <b>( {{$comments}} Comments ) </b>
                        </td>
                    @elseif($post->origin_user_id !==auth()->user()->id)
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
                            <a href="/posts/{{$post->orgin_post_id}}/comments">Post</a>
                            @if(in_array(auth()->user()->id,$friends))
                                <br>
                                <h3>{{$origin_post->text}}</h3>
                            @if($post->user_id == $origin_post->user_id || $post->user_id == auth()->user()->id)
                                <form class="d-inline" method="post" action="/posts/{{$post->id}}/delete">
                                    @csrf
                                    <button type="submit" class="float-md-right btn btn-danger">Delete
                                        Post</button>
                                </form>
                            @endif
                    @else
                                <br>NoT Allowed To show Post
                            @endif
                        </td>
                    @elseif(auth()->user()->id == $post->origin_user_id)
                        @php
                            // The post creator
                            // The owner of the profile being showed now
                                $posted = \App\User::find($post->user_id);
                                 $origin_post= \Illuminate\Support\Facades\DB::table('posts')->find($post->orgin_post_id);
                            // Friends of the post creator
                        @endphp
                        <td>
                            <h3>{{$posted->name}} Shared Your Post {{$origin_post->text}}</h3>
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
