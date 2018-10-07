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
                    <a href="#" class="btn btn-info">Like</a>
                        <br><br>
                    <a href="#" class="btn-primary">Show All Likes</a>
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
