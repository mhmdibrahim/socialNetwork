@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        @if($id == auth()->user()->id)
    <form method="post" action="/{{auth()->user()->id}}/post/create">
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
    <td>{{$post->text}}
            @if(\Illuminate\Support\Facades\Auth::check())
            <a href="/{{$post->id}}/post/delete" class="float-md-right btn btn-danger">Delete Post</a>
            @endif
            <br>
            <a href="/post/{{$post->id}}/comments" class="btn btn-primary">Show Post Comments</a>
    </td>

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
