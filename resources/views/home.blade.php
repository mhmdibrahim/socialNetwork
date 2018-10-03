@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Members</div>
                    <div class="card-body">
                        @foreach($members as $member)
                            @if($member->id !== auth()->user()->id)
                                <a href="/{{$member->id}}/profile">{{$member->name}}</a>
                                @if(!(in_array($member->id,$requests)))
                                    <a href="/request/{{$member->id}}/sent">
                                        <button class="btn btn-primary float-lg-right">Add Friend</button>
                                    </a>
                                @else
                                    <a href="/request/{{$member->id}}/cancelRequest">
                                        <button class="btn btn-danger float-lg-right">Cancel Request</button>
                                    </a>
                                @endif
                                <hr>
                                <br>
                            @endif
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
