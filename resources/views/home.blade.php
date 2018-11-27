@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Members</div>
                    <div class="card-body">
                        @foreach($users as $user)
                            <a href="/profile/{{$user->id}}">{{$user->name}}</a>
                            @if($user->user_from !== auth()->id())
                                <form class="d-inline" action="/requests/{{$user->id}}/send" method="POST">
                                    @csrf
                                    <button class="btn btn-primary float-lg-right" type="submit">
                                        Add friend
                                    </button>
                                </form>

                            @else
                                <form class="d-inline" action="/requests/{{$user->id}}/cancel" method="POST">
                                    @csrf
                                    <button class="btn btn-danger float-lg-right" type="submit">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                            <hr>
                            <br>
                        @endforeach
                    </div>
                    <div class="card-body">
                        {{$users->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
