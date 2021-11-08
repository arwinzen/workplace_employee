@extends('layout.dashboard_template')
@section('content')

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="" method="post">
        @csrf
        <input type="hidden" name="id" id="id" value="{{$user->id}}">
        <input type="text" name="name" id="name" value="{{$user->name}}">
        <input type="email" name="email" id="email" value="{{$user->email}}">
        <input class="btn btn-dark" type="submit">
    </form>

@stop
