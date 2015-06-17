@extends('base.backend')

@section('title')
Active System Users
@stop

@section('content-title')
All System Users
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li class="active"><a href="{{ action('UserController@getIndex') }}">Users</a></li>
    <li><a href="{{ action('UserController@getRegister') }}">New</a></li>
</ul>
<br />

<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Profile Image</th>
            <th>Details</th>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($users); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                @if($users[$i]->getAvatar())
                <a href="{{ asset('uploads/images/'. $users[$i]->getAvatar()->filename) }}">
                    <img src="{{ asset('uploads/images/'. $users[$i]->getAvatar()->getCropped()) }}" alt="{{ $users[$i]->getFullName() }}" class="img-responsive" />
                </a>
                @else
                <span class="user-image glyphicon glyphicon-user fa-5x"></span>
                @endif
            </td>
            <td>
                <h5>{{ $users[$i]->getFullName() }}</h5>
                <h6>Email: <a href="mailto:{{ $users[$i]->email }}">{{ $users[$i]->email }}</a></h6>
                @if($users[$i]->is_admin)
                User Type: <small class="text-success">Admin</small>
                @else
                 User Type: <small class="text-warning">Regular</small>
                @endif
            </td>
            <td>
                @if(Auth::user()->is_admin)
                    @if($users[$i]->is_locked)
                    <a href="{{ action('UserController@getUnblock', [$users[$i]->id]) }}" class="btn btn-xs btn-warning tooltips" title="The user will now be able to login" data-toggle="tooltip" data-placement="bottom">UnBlock</a>
                    @else
                        <a href="{{ action('UserController@getBlock', [$users[$i]->id]) }}" class="btn btn-xs btn-warning tooltips" title="The user will not be able to login" data-toggle="tooltip" data-placement="bottom">Block</a>
                    @endif
                    <a href="" class="btn btn-xs btn-danger delete">Delete</a>
                @endif
            </td>
        </tr>
        @endfor
    </tbody>
</table>
    
@if($users->isEmpty())
<p class="alert alert-info">Whoops! Looks like there are no registered users other than you</p>
@endif
    {!! $users->render() !!}
@stop
