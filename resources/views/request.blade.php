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
                <td>
                    <form class="d-inline" action="/requests/{{$user->id}}/accept" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Accept</button>
                    </form>
                    <form class="d-inline" action="/requests/{{$user->id}}/reject" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
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
