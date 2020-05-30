@extends('layouts.tailwind')

@section('body')
    <body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

    <div class="min-h-screen flex flex-col">
        <form  method="POST" action="/contact" class="container max-w-2xl mx-auto flex-1 flex flex-col items-center justify-center px-2">
            @csrf
            <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">
                <h1 class="mb-8 text-3xl text-center text-teal-900 uppercase">{{$type}}</h1>
                <label class="block uppercase tracking-wide text-teal-800 text-xs font-bold mb-2 mx-1" for="name">
                    Name
                </label>
                <input
                    id="name"
                    type="text"
                    class="block border w-full p-3 rounded mb-4 {{ $errors->has('name') ? 'border-red-700' : 'border-gray-500' }}"
                    name="name"
                    placeholder="Name"
                    value="{{ old('name') ?: $userName }}"
                    required
                    {{$errors->has('name') ? '' : 'autofocus'}}
                >
                @if ($errors->has('name'))
                    <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $errors->first('name') }}</span>
                    </div>
                @endif

                <label class="block uppercase tracking-wide text-teal-800 text-xs font-bold mb-2 mx-1" for="email">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    class="block border w-full p-3 rounded mb-4 {{ $errors->has('email') ? 'border-red-700' : 'border-gray-500' }}"
                    name="email"
                    placeholder="Email for us to respond to"
                    value="{{ old('email') ?: $userEmail}}"
                    required
                >
                @if ($errors->has('email'))
                    <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <label class="block uppercase tracking-wide text-teal-800 text-xs font-bold mb-2 mx-1" for="message">
                    Message
                </label>
                <textarea
                    id="message"
                    class="block border w-full p-3 rounded mb-4 h-48 {{ $errors->has('message') ? 'border-red-700' : 'border-gray-500' }}"
                    name="message"
                    placeholder="{{$messagePlaceHolder}}"
                    required
                ></textarea>
                @if ($errors->has('reason'))
                    <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $errors->first('message') }}</span>
                    </div>
                @endif
                <input
                    hidden
                    value="{{$type}}"
                    name="type"
                >
                <button
                    type="submit"
                    class="w-full text-center py-3 rounded bg-teal-700 text-white hover:bg-teal-600 focus:outline-none my-1"
                >{{$buttonText}}</button>
            </div>
        </form>
    </div>

    <script>
        let formGroups = document.getElementsByTagName('input');
        for (let i = 0; i < formGroups.length; i++) {
            formGroups[i].addEventListener('focus', function (event) {
                let input = event.target;
                if (input.classList.contains('border-red-700')) {
                    console.log(input);
                    input.classList.remove('border-red-700');
                    input.classList.add('border-gray-500');

                    let errorMessage = input.nextElementSibling;
                    console.log("Error");
                    console.log(errorMessage);
                    if (errorMessage.classList.contains('rh-error')) {
                        errorMessage.parentNode.removeChild(errorMessage);
                    }
                }
            });
        }
    </script>
    </body>


@endsection
