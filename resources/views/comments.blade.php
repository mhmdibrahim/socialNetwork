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

                     @if($comment->likesCount > 0)
                        <form class="d-inline" method="post" action="/comments/{{$comment->comment_like_id}}/unlike">
                            @csrf
                            <button class="btn btn-info">UnLike</button>
                        </form>
                     @else
                         <form class="d-inline" method="post" action="/comments/{{$comment->id}}/post/{{$comment->post_id}}/like">
                             @csrf
                            <button  type="submit" class="btn btn-info">Like</button>
                         </form>
                     @endif
                            <br><br>
                    <a href="/comments/{{$comment->user_id}}/{{$comment->id}}/post/{{$comment->post_id}}/likes" class="btn-primary">Show All Likes</a> <b>{{$comment->likesCount}} Likes</b>
                    </td>
                    <td>@if($comment->user_name === auth()->user()->name)  <b>Me</b> @else <b> {{$comment->user_name}} </b> @endif
                    @if($comment->user_id == auth()->user()->id || (auth()->user()->id == $comment->user_id))
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
