@extends('base.backend')

@section('title')
Add Answers
@stop

@section('content-title')
Add possible responses to {{ $campaign->title }}
@stop

@section('inner-content')
<p>
    <a href="{{ action('CampaignController@getIndex') }}" class="btn btn-default">Campaigns</a>
</p>
<br />

<p>
    Total Responses required: {{ $campaign->possible_responses }}
</p>
<div class="row">
    <div class="col-sm-6">
        <h2>Add new</h2>
        @if($campaign->possible_responses - count($campaign->getAnswers()))
        <form method="post" action="{{ action('CampaignController@postAnswers', [$campaign->id]) }}" class="form">
            {!! Form::token() !!}
            @for($i = 0; $i < $campaign->possible_responses - count($campaign->getAnswers()) ; $i++)
            <div class="form-group">
                <label>Response {{ $i + 1 }}</label>
                <input value="{{ empty($ignored)?'':$ignored[$i] }}" type="text" name="responses[]" class="form-control" />
                <p class="help-block small">make it short and descriptive</p>
            </div>
            @endfor
            <button class="btn btn-primary pull-right">Save</button>
            <div class="clearfix"></div>
        </form>
        @else
        <p class="alert alert-info">There is Nothing To add</p>
        @endif
    </div>
    <div class="col-sm-6">
        <h2>Saved Answers</h2>
        @if($campaign->getAnswers()->isEmpty())
        <p class="alert alert-info">Currently There are none</p>
        @else
        <form id="answers-form-existing" method="post" action="{{ action('CampaignController@postRemoveResponses', [$campaign->id]) }}" class="form">
            {!! Form::token() !!}
            <p>
                <button id="select-all" class="btn btn-info btn-xs">Select All</button>
            </p>            
            @foreach($campaign->getAnswers() as $a)
            <div class="form-group">
                <label>
                    <input type="checkbox" name="responses[]" value="{{ $a->id }}" />
                    {{$a->message}}
                </label>
            </div>
            @endforeach
            <button class="btn btn-danger pull-right">Remove</button>
            <div class="clearfix"></div>
        </form>
        @endif
    </div>    
</div>
<p>

    <a href="{{ action('CampaignController@getContacts', [$campaign->id]) }}" class="btn btn-default pull-right">Next &rarr;</a>
    <a href="{{ action('CampaignController@getNew', [$campaign->id]) }}" class="btn btn-default">&larr; Back</a>
    <span class="clearfix"></span>
</p>

@stop