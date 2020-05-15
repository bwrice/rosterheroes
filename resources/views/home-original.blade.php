@extends('layouts.basic-bootstrap')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-5 offset-md-3">
                <ul class="list-group">
                    <a href="/register" class="btn btn-outline-success list-group-item">
                        Register
                    </a>
                    <a href="/login" class="btn btn-outline-primary list-group-item">
                        Login
                    </a>
                </ul>
            </div>
        </div>
    </div>
@endsection
