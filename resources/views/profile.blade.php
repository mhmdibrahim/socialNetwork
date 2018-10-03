@extends('layouts.app')
@section('content')
    <div class="container">
                <table class="table table-hover">
                    <tr>
                        <th >Name</th>
                        <th >email</th>
                    </tr>
                    <tbody>
                    @foreach($user as $value)
                     <tr>
                         <td>{{$value->name}}</td>
                         <td>{{$value->email}}</td>
                    </tr>

                    </tbody>
                </table>
            @if(auth()->user()->id == $value->id)
                <div class="form-group">
                    <a class="btn btn-info" href="/profile/edit">Edit Profile</a>
                </div>
            @endif
            @if(in_array($value->id,$myfriends))
                <div class="form-group">
                <a class="btn btn-info" href="/{{$value->id}}/posts">Show All Posts</a>
                </div>
            @endif
            @endforeach
    </div>

@endsection
