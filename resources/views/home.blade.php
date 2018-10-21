@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Members</div>
                    <div class="card-body">
                        @foreach($members as $member)
                            <a href="/profile/{{$member->id}}">{{$member->name}}</a>
                            @if(!(in_array($member->id,$requests)))
                                <form class="d-inline" action="/requests/{{$member->id}}/send" method="POST">
                                    @csrf
                                    <button class="btn btn-primary float-lg-right" type="submit">
                                        Add friend
                                    </button>
                                </form>

                            @else
                                <form class="d-inline" action="/requests/{{$member->id}}/cancel" method="POST">
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
                        {{$members->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
