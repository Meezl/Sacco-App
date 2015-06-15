@extends('base.backend')

@section('title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('content-title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('inner-content')
<ul>
    <li><b>Code: </b>{{ $campaign->getIdString() }}</li>
    <li><b>Estimated Cost:</b> {{ $campagin->cost }}</li>
    <li><b>Responses: </b><a href="">View {{ $campaign->total_responses }} Campaign Responses</a></li>
</ul>
<h3>Description:</h3>
<p>
    {{ $campaign->description }}
</p>
<br />
<h3>Actual Text Sent</h3>
<div class="well well-lg">
    {{ nl2br(htmlentities($campaign->getSms())) }}
</div>


@stop