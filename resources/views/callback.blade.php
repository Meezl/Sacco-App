@extends('base.master')

@section('title')
Test URL Callback
@stop

@section('content')
<form method="post" action="{{ action('MessageController@postHandleCallback') }}" class="form" style="max-width: 400px">
    <input type="hidden" name="from" value="+254734741807" />
    <input type="hidden" name="to" value="20880" />
    <input type="hidden" name="text" value="A0003 i do not know" />
    <input type="hidden" name="date" value="2015-06-14T06:34:44Z" />
    <input type="hidden" name="id" value="666d69d1-1cc9-41fe-bd81-5d63163d9078" />
    <input type="hidden" name="linkid" value="14093444075901564521" />
    <button class="btn btn-success" type="submit">Test</button>
</form>
