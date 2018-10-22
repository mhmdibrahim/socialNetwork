@extends('layouts.app')
@section('content')

    <div class="text-md-center">
        <table class="table table-hover">
            <tr>
                <th>Your Notifications</th>
            </tr>
            <tbody>
            @forelse($posts as $post)
            <tr>
                @php
                    // The owner of the profile being showed now
                    $posted = \App\User::find($post->user_id);
                     $origin_post= \Illuminate\Support\Facades\DB::table('posts')->find($post->orgin_post_id);
                    $comments = \Illuminate\Support\Facades\DB::table('comments')->where('post_id',$post->orgin_post_id)->get();
                @endphp
                <td>
                    <h3><a href="/profile/{{$posted->id}}">{{$posted->name}}</a> Shared Your Post <a href="/posts/{{$origin_post->id}}/comments">{{$origin_post->text}}</a></h3>
                {{--@if($comments)--}}
                    {{--@foreach($comments as $comment)--}}
                    {{--<h3>{{$comment->body}}</h3>--}}
                    {{--@endforeach--}}
                {{--@endif--}}
                </td>
            </tr>
            @empty
            <tr>
                <td>No notification founded!</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
