@extends('base.backend')

@section('title')
Sent Messages
@stop

@section('content-title')
Sent Messages
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('MessageController@getIndex') }}">Inbox</a></li>
    <li class="active"><a href="{{ action('MessageController@getOutbox') }}">Outbox</a></li>
</ul>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>From</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($messages); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $messages[$i]->sender }}</td>
            <td>
                <h5>Message: {!! nl2br(htmlentities($messages[$i]->text)) !!}</h5>
                <h6>Status: {{ $messages[$i]->status }}</h6>
                received: {{ $messages[$i]->created_at }}
            </td>
        </tr>
        @endfor
    </tbody>
</table>
@if($messages->isEmpty())
<p class="alert alert-info">
    Whoops! Looks like you have not sent any text
</p>
@endif

{!! $messages->render() !!}
@stop