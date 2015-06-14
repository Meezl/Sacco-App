@extends('base.master')

@section('title')
Test URL Callback
@stop

@section('content')
<form method="post" action="#" class="form" style="max-width: 400px">
    <input type="hidden" name="from" value="from value" />
    <input type="hidden" name="to" value="to value" />
    <input type="hidden" name="text" value="text content" />
    <input type="hidden" name="date" value="sent date" />
    <input type="hidden" name="id" value="message id" />
    <button class="btn btn-success" type="submit">Test</button>
</form>
