@extends('base.backend')

@section('title')
    Contact Transactions
@stop

@section('content-title')
 All Transactions From {{ $number }}
@stop

@section('inner-content')
<h3>Associated Contact</h3>
<p>
@if(is_null($contact))
None: <a href="{{ action('ContactController@getNew', [$number])}}">Add to Contacts</a>
@else 
    {{ $contact->getFullName() }}
@endif
</p>

<h3>Messages</h3>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <td>From</td>
            <td>To</td>
            <td>Details</td>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i< count($messages); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $messages[$i]->sender == $number ? 'inbound': 'outbound' }}</td>
            <td>{{ $messages[$i]->sender }}</td>
            <td>{{ $messages[$i]->receiver }}</td>
            <td>
                <p>
                    <b>Message: </b> {{ $messages[$i]->text }}
                </p>
                <b>Sent: </b> {{ $messages[$i]->created_at }}
            </td>
        </tr>
        @endfor
    </tbody>
</table>
{!! $messages->render() !!}

@if($messages->isEmpty())
<p class="alert alert-info">Whoops! Looks Like there is nothing to show for {{ $number }} </p>
@endif
@stop