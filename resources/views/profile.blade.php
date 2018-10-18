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
            @if(auth()->user()->id == $user->id)
                <div class="form-group">
                    <a class="btn btn-info" href="/profile/edit">Edit Profile</a>
                </div>
            @endif
            @if(in_array($user->id,$myfriends))
                <div class="form-group">
                <a class="btn btn-info" href="/{{$user->id}}/posts">Show All Posts</a><b> ({{$posts}} Posts)</b>
                </div>
            @endif
    </div>

@endsection
