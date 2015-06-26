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
            <th>To</th>
            <th>Details</th>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($messages); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                @if($messages[$i]->contact)
                {{ $messages[$i]->contact->getFullName() }}
                @else
                <a href="{{action('ContactController@getNew', [$messages[$i]->receiver]) }}" class="tooltips" title="Add Contact To Database">
                {{ $messages[$i]->receiver }}
                </a>
                @endif
            </td>
            <td>
                <h5>Message: {!! nl2br(htmlentities($messages[$i]->text)) !!}</h5>
                <h6>Status: {{ $messages[$i]->status }}</h6>
                received: {{ $messages[$i]->created_at }}
            </td>
            <td>
                <a href="{{ action('ContactController@getTransactions', [$messages[$i]->receiver])}}" class="tooltips" title="All Activies from {{ $messages[$i]->receiver }}">Transactions</a>
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