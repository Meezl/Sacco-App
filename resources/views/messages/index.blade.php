@extends('base.backend')

@section('title')
Inbox
@stop

@section('content-title')
Inbox
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li class="active"><a href="{{ action('MessageController@getIndex') }}">Inbox</a></li>
    <li><a href="{{ action('MessageController@getOutbox') }}">Outbox</a></li>
</ul>


<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>From</th>
            <th>Details</th>
            <th>Actions</th>
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
                <a href="{{action('ContactController@getNew', [$messages[$i]->sender]) }}" class="tooltips" title="Add Contact To Database">
                {{ $messages[$i]->sender }}
                </a>
                @endif                
            </td>
            <td>
                <h5>Message: {!! nl2br(htmlentities($messages[$i]->text)) !!}</h5>
                received: {{ $messages[$i]->created_at }}
            </td>
            <td>
                <a href="{{ action('ContactController@getTransactions', [$messages[$i]->sender])}}" class="tooltips" title="All Activies from {{ $messages[$i]->sender }}">Transactions</a>
            </td>
        </tr>
        @endfor
    </tbody>
</table>
<br />
@if($messages->isEmpty())
<p class="alert alert-info">
    Whoops! Looks like you have not sent any text
</p>
@endif


{!! $messages->render() !!}

@stop