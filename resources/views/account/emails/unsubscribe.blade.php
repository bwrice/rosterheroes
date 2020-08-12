@extends('layouts.tailwind')

@section('body')
    <body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

    <div class="min-h-screen flex flex-col">
        <div class="container max-w-sm mx-auto flex-1 flex flex-col items-center justify-center px-2">
            <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">
                <h1 class="mb-3 text-3xl text-center text-teal-900">Unsubscribed</h1>


                <p class="py-4 text-center">You've successfully unsubscribed from {{$subName}} emails for {{$email}}</p>

                <a href="{{$linkHref}}" class="text-center p-3 block rounded bg-teal-700 text-white hover:bg-teal-600 focus:outline-none"
                >{{$linkText}}</a>
            </div>
        </div>
    </div>
    </body>

@endsection
