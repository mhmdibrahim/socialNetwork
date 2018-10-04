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
                    <td>{{$comment->body}}</td>
                    <td>{{$comment->user->name}}</td>
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
