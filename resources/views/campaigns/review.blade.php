@extends('base.backend')

@section('title')
Campaign Preview
@stop

@section('content-title')
Overview of {{ $campaign->title }}
@stop

@section('inner-content')
<p>
    Number of users that will be contacted: {{ $help->users }}
</p>
<div class="row">
    <div class="col-sm-6">
        <h2>With Help Block</h2>
        <ul>
            <li><b>Length: </b>{{ $help->length }}</li>
            <li><b>Cost per Sms: </b>{{ $help->cost }}</li>
            <li><b>Total Cost: </b>{{ $help->total }}</li>
        </ul>
        <div class="well well-lg">
            {!! nl2br(htmlentities($help->sms)) !!}  
        </div>
    </div>
    <div class="col-sm-6">
        <h2>Without Help Block</h2>
        <ul>
            <li><b>Length: </b>{{ $plain->length }}</li>
            <li><b>Cost per Sms: </b>{{ $plain->cost }}</li>
            <li><b>Total Cost: </b>{{ $plain->total }}</li>
        </ul>
        <div class="well well-lg">
            {!! nl2br(htmlentities($plain->sms)) !!}      
        </div>
    </div>
</div>
<br/>
<p class="text-center">
    <a href="{{ action('CampaignController@getNew', [$campaign->id]) }}" class="btn btn-primary">&larr; Edit</a>    
    <a href="" class="btn btn-success">Send With Help Block</a>
    <a href="" class="btn btn-success">Send Without Help Block</a>
    
</p>
@stop