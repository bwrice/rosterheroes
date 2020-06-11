@extends('layouts.tailwind')

@section('body')

    <body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">
    <div class="min-h-screen flex flex-col">
        @yield('content')
    </div>
    </body>
@endsection
