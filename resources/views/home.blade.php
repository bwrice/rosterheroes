@extends('layouts.basic-bootstrap')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-5 offset-md-3">
                <ul class="list-group">
                    @if($user)
                        <a href="/squads/create" class="btn btn-outline-primary list-group-item">
                            Create Squad
                        </a>
                        @foreach($squads as $squad)
                            <a href="/command-center/{{$squad->slug}}" class="btn btn-outline-primary list-group-item">{{$squad->name}}</a>
                        @endforeach

                        <form action="/logout" method="post">
                            <button type="submit" class="btn btn-outline-primary list-group-item">Logout</button>
                        </form>
                    @else
                        <a href="/register" class="btn btn-outline-success list-group-item">
                            Register
                        </a>
                        <a href="/login" class="btn btn-outline-primary list-group-item">
                            Login
                        </a>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection