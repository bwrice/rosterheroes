@extends('layouts.tailwind')

@section('body')
<body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

<div class="min-h-screen flex flex-col">

    <div class="container max-w-sm mx-auto flex-1 flex flex-col items-center justify-center px-2">
        <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">
            <h1 class="mb-6 text-3xl text-center text-teal-900 uppercase">Thank You</h1>
            <p class="text-center text-teal-800 mb-4">Thank you for contacting us. We will try to get back to you as soon as we can.</p>
            <p class="text-center text-teal-800 underline">
                <a href="/">Home</a>
            </p>
        </div>
    </div>
</div>

</body>
@endsection
