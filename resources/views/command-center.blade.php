@extends('layouts.app')

@section('content')
    <div id="app">
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/commandCenter.js') }}"></script>
@endsection
