@extends('layouts.app')
@section('content')
    <div class="container">
                <table class="table table-hover">
                    <tr>
                        <th >Name</th>
                        <th >email</th>
                    </tr>
                    <tbody>
                     <tr>
                         <td>{{$user->name}}</td>
                         <td>{{$user->email}}</td>
                    </tr>
                </table>
            @if($isMe)
                <div class="form-group">
                    <a class="btn btn-info" href="/profile/edit">Edit Profile</a>
                </div>
            @endif
            @if($isMyFriend || $isMe)
                <div class="form-group">
                <a class="btn btn-info" href="/posts/{{$user->id}}">Show All Posts</a><b> ({{$user->postsCount}} Posts)</b>
                </div>
            @endif
    </div>
@endsection
