@extends('base.backend')

@section('title')
Users
@stop

@section('content-title')
Users
@stop

@section('inner-content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr> 
        </thead>

        <tbody>
            @for($i = 1; $i < 10; $i += 2)
            <tr>
                <td>{{ $i }}</td>
                <td>James Kamau</td>
                <td>(+254) 705 813 955</td>
                <td>
                    <a href="" class="btn btn-danger btn-xs">Delete</a>
                    <a href="" class="btn btn-primary btn-xs">Edit</a>
                    <a href="" class="btn btn-success btn-xs">Send Message</a>
                </td>
            </tr>
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>Hashim Madadi</td>
                <td>(+254) 706 805 838</td>
                <td>
                    <a href="" class="btn btn-danger btn-xs">Delete</a>
                    <a href="" class="btn btn-primary btn-xs">Edit</a>
                    <a href="" class="btn btn-success btn-xs">Send Message</a>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
    <nav>
        <ul class="pagination pull-right pagination-lg">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="active"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="clearfix"></div>
@stop