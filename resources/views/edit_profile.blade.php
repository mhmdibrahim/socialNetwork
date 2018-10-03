@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        <form method="post" action="/profile/edit">
            {{csrf_field()}}
            <div class="form-group">
                <label>Name</label>
                <input name="name" value="{{auth()->user()->name}}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input name="email" disabled type="email" value="{{auth()->user()->email}}">
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
