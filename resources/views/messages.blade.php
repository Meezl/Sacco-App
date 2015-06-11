@extends('base.backend')

@section('title')
Inbox-messages
@stop

@section('content-title')
    Inbox
@stop


@section('inner-content')
<p>
    <a href="" class="btn btn-default">Archive</a>
    <a href="" class="btn btn-default">Delete</a>
    <a href="" class="btn btn-default">Spam</a>
    <a href="" class="btn btn-default">New</a>
</p>
<table class="table table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Details</td>            
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < 10; $i++)
        <tr>
            <td style="padding-top:50px"><input type="checkbox" name="check" /></td>
            <td>
                <h4>James Kamau</h4>
                <h5>Theories Of Design</h5>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
            </td>
        </tr>
        @endfor
    </tbody>
</table>
<div class="text-center">
<ul class="pagination pagination-lg">
    <li>
      <a aria-label="Previous" href="#">
        <span aria-hidden="true">«</span>
      </a>
    </li>
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a aria-label="Next" href="#">
        <span aria-hidden="true">»</span>
      </a>
    </li>
  </ul>
  </div>
@stop