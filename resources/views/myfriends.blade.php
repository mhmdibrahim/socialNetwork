@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Friends</div>
                    <div class="card-body">
                        @forelse($friends as $friend)
                                <a href="/{{$friend->id}}/profile" class="text-primary">{{$friend->name}}</a>
                                <a href="/{{$friend->id}}/friend/delete" class="btn btn-danger float-md-right">Delete Friend</a>
                            <br>
                            <hr>
                            @empty
                            <label class="text-primary">No Friends Founded!</label>
                            <br>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
