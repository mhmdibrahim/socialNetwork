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
                    @endphp
                     @if(count($comment_like) > 0)
                        <a href="/comment/{{$comment_like[0]->id}}/commentUnlike" class="btn btn-info">UnLike</a>
                     @else
                        <a href="/{{$post->id}}/comment/{{$comment->id}}/commentLike" class="btn btn-info">Like</a>
                     @endif
                            <br><br>
                    <a href="/{{$post->id}}/comment/{{$comment->id}}/likes" class="btn-primary">Show All Likes</a> <b>{{$post_comment_likes}} Likes</b>
                    </td>
                    <td>@if($comment->user->name === auth()->user()->name)  <b>Me</b> @else <b> {{$comment->user->name}} </b> @endif
                    @if($post->user_id == auth()->user()->id || (auth()->user()->id == $comment->user->id))
                            <a class="btn btn-danger float-md-right" href="/comment/{{$comment->id}}/delete">Delete</a>@endif
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
        <form method="post" action="/add/comment">
        {{csrf_field()}}
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <textarea cols="30" rows="3"  name="body" placeholder="Write Your Comment Here"></textarea>
        <button class="btn btn-primary">Add</button>
        </form>
    </div>
@endsection
