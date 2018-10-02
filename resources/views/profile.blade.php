@extends('layouts.app')
@section('content')
    <div class="container">
        @foreach($user as $value)
        <form method="post" action="/{{$value->id}}/profile">
            {{csrf_field()}}
        <div class="row">
            <label class="col-form-label-lg">user Name</label>
            <input class="input-group-lg text-lg-center" type="text" name="name" value="{{$value->name}}">
        </div>
        <div class="row align-content-center">
            <button class="btn btn-info">Update Profile</button>
            </div>
        </form>
        @endforeach

    </div>
@endsection
