@extends('base.backend')

@section('title')
New Campaign
@stop

@section('content-title')
@if($campaign->id)
Editing Campaign
@else 
new Campaign
@endif
@stop

@section('inner-content')
<p>
    <a href="{{ action('CampaignController@getIndex') }}" class="btn btn-default">Campaigns</a>
</p>
<br />

<form id="campaign-form-new" method="post" action="{{ action('CampaignController@postNew', [$campaign->id]) }}" class="form">
    {!! Form::token() !!}
    <div class="form-group">
        <label>Group</label>
        <select name="group" class="form-control">
            @foreach(App\Models\Group::ordered() as $g)
            <option value="{{ $g->id }}" {{ $g->id == $campaign->group_id?'selected':'' }}>{{ $g->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Title*</label>
        <input value="{{ $campaign->title }}" type="text" name="title" class="form-control" />
        {!! $errors->first('title', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Description(optional)</label>
        <textarea class="form-control" name="description">{{ $campaign->description }}</textarea>
        <p class="help-block small">What is the purpose of this campaign</p>
        {!! $errors->first('description', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Message*</label>
        <textarea class="form-control" name="message">{{ $campaign->message }}</textarea>
        <p class="help-block small">The Text you want to send to members. max of 900 characters but who likes reading long texts? <b class="text-info">Do not include your response options here.</b></p>
        {!! $errors->first('message', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Possible Responses</label>
        <input value="{{ $campaign->possible_responses or 0}}" size="2" type="text" name="possible_responses" />
        <p class="help-block small">Number of possible answers members can choose from and use to reply to your text. Set to zero to allow users to respond with anything</p>
        {!! $errors->first('possible_responses', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Associated Category</label>
        <select name="category" class="form-control">
            <option value="-1">None. Manually Select Contacts</option>
            <option {{ ((!is_null($campaign->category_id))&& $campaign->category_id == 0 )?'selected':'' }} value="0">None. Send To Everyone</option>
            @foreach(App\Models\Category::all() as $cat)
            <option value="{{ $cat->id }}" {{ $cat->id == $campaign->category_id?'selected':'' }}  >{{ $cat->title }}</option>
            @endforeach
        </select>
        {!! $errors->first('category', '<p class="text-danger small">:message</p>') !!}
        <p class="help-block small">You can decide to send texts to only contacts in a given group</p>
    </div>

    <div class="form-group">        
        <p>Send Help Text?</p>
        <label>
            <input type="radio" name="help_text" value="1" {{ $campaign->help_text?'checked':'' }} /> Yes
        </label>
        <br />
        <label>
            <input type="radio" name="help_text" value="0" {{ !$campaign->help_text?'checked':'' }} /> No
        </label>
        <p class="help-block small">e.g Reply for free to {{ Config::get('sms.system_number') }} in the format( <b>EGERS X0005 A</b> )where A is your reply </p>
        {!! $errors->first('help_text', '<p class="text-danger small">:message</p>') !!}
    </div>    
    <button class="btn btn-success pull-right">Save</button>
    <div class="clearfix"></div>    
</form>
@stop