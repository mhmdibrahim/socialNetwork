@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Friends</div>
                    <div class="card-body">
                        @forelse($friends as $friend)
                                <label class="text-primary">{{$friend->name}}</label>
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
