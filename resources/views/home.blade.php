@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Members</div>
                    <div class="card-body">
                        @foreach($members as $member)
                            <a href="/{{$member->id}}/profile">{{$member->name}}</a>
                            @if(!(in_array($member->id,$requests)))
                                <a class="btn btn-primary float-lg-right" href="/request/{{$member->id}}/sent">
                                    Add Friend
                                </a>
                            @else
                                <a class="btn btn-danger float-lg-right" href="/request/{{$member->id}}/cancelRequest">
                                    Cancel Request
                                </a>
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
