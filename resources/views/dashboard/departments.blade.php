@extends('layout.dashboard_template')
@section('content')

    <h1>Users</h1>

    @if(session('status'))
        <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif

    <table class="table table-bordered ">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
            <th></th>
        </tr>
        </thead>
        @isset($users)
            @foreach ($users as $user)
{{--                {{ var_dump($user) }}--}}
                <tbody>
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a type="button" class="btn btn-outline-primary" href="{{ route('edit.user', ['id' => $user->id]) }}" >Edit</a>
                        <a type="button" class="btn btn-outline-danger" href=" {{ route('delete',['id' => $user->id]) }}" >Delete</a>
                    </td>
                </tr>
                </tbody>
            @endforeach
        @endisset
    </table>
    {{$users->links('pagination::bootstrap-4')}}


@stop
