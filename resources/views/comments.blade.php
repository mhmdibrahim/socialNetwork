@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        <label>{{$post->text}}</label>
    </div>
    <div class="text-md-center">
        <table class="table table-hover">
            <tr>
                <th>All Comments</th>
                <th>by Who</th>
            </tr>
            <tbody>
            @forelse($comments as $comment)
                <tr>
                    <td>{{$comment->body}}
                    @php
                    $comment_like = \Illuminate\Support\Facades\DB::table('comment_likes')->where('post_id',$post->id)
                                                                    ->where('user_id',auth()->user()->id)
                                                                    ->where('comment_id',$comment->id)->get();

                    $post_comment_likes = \Illuminate\Support\Facades\DB::table('comment_likes')->where('post_id',$post->id)
                                                                    ->where('comment_id',$comment->id)->count();
                    $comment_user = \App\User::find($comment->user_id);
                    @endphp
                     @if(count($comment_like) > 0)
                        <form class="d-inline" method="post" action="/comments/{{$comment_like[0]->id}}/unlike">
                            @csrf
                            <button class="btn btn-info">UnLike</button>
                        </form>
                     @else
                         <form class="d-inline" method="post" action="/comments/{{$comment->id}}/post/{{$post->id}}/like">
                             @csrf
                            <button  type="submit" class="btn btn-info">Like</button>
                         </form>
                     @endif
                            <br><br>
                    <a href="/comments/{{$comment->id}}/post/{{$post->id}}/likes" class="btn-primary">Show All Likes</a> <b>{{$post_comment_likes}} Likes</b>
                    </td>
                    <td>@if($comment_user->name === auth()->user()->name)  <b>Me</b> @else <b> {{$comment_user->name}} </b> @endif
                    @if($post->user_id == auth()->user()->id || (auth()->user()->id == $comment_user->id))
                        <form class="d-inline" method="post" action="/comments/{{$comment->id}}/delete">
                            @csrf
                            <button class="btn btn-danger float-md-right" type="submit">Delete</button>
                        </form>
                    @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">No Comments Founded!</td>
                </tr>
             @endforelse
            </tbody>
        </table>
    </div>
    <div class="text-md-center">
        <form class="d-inline" method="post" action="/comments/add">
        {{csrf_field()}}
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <textarea cols="30" rows="3"  name="body" placeholder="Write Your Comment Here"></textarea>
        <button class="btn btn-primary">Add</button>
        </form>
    </div>
@endsection
