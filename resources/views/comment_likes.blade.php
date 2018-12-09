@extends('layouts.app')
@section('content')
    <div class="text-md-center">
        <table class="table table-hover">
            <thead>
            <th>Names</th>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td><b>
                            {{$user->user_name}}
                        </b></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
