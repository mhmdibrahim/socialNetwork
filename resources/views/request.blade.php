@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-8">

    <div class="row">
        <table class="table table-info">
            <tr>
                <th>Friend</th>
                <th colspan="2">Action</th>
            </tr>
            <tbody>
            <tr>
                @forelse($users as $user)
                <td>{{$user->name}}</td>
                <td><a href="#"><button class=" btn btn-primary">Accept</button></a>
                    <a href="#"><button class="btn btn-danger">Remove</button></a></td>
                    <hr>
            </tr>
                @empty
                    <tr>
                        <td>No Frind Requests</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    </div>
</div>
    <div class="row">

    </div>
@endsection
