@if(Session::has('error'))
<p class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('error') }}
</p>
@endif

<!--success messgae -->
@if(Session::has('success'))
<p class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('success') }}
</p>
@endif
<!--info message -->
@if(Session::has('info'))
<p class="alert alert-info alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('info') }}
</p>
@endif

<!--gritter messages -->
@if(Session::has('gritter'))
<p class="hidden" id="gritter-msg">{{Session::get('gritter')}}</p>
@endif