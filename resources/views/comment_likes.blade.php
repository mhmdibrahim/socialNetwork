@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        <table class="table table-hover">
            <thead>
            <th>Names</th>
            </thead>
            <tbody>
            @foreach($likes as $like)
                <tr>
                    <td><b>@php
                            $user = \App\User::find($like->user_id);
                            @endphp
                            {{$user->name}}
                        </b></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
