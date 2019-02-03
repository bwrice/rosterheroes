@extends('layouts.app')

@section('content')
    @if($squad)
        <create-squad :squad="{{$squad}}"></create-squad>
    @else
        <create-squad></create-squad>
    @endif
@endsection