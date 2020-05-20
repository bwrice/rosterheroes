@extends('layouts.tailwind')

@section('body')
    <body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

    <div class="min-h-screen flex flex-col">
        <form  method="POST" action="{{ route('password.email') }}" class="container max-w-sm mx-auto flex-1 flex flex-col items-center justify-center px-2">
            @csrf
            <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">

                @if (session('status'))
                    <span class="text-center text-teal-700 flex-auto mb-4">{{ session('status') }}</span>
                @endif

                <h1 class="mb-8 text-3xl text-center text-teal-900">Reset Password</h1>
                <input
                    id="email"
                    type="email"
                    class="block border w-full p-3 rounded mb-4 {{ $errors->has('email') ? 'border-red-700' : 'border-gray-500' }}"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                >
                @if ($errors->has('email'))
                    <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <button
                    type="submit"
                    class="w-full text-center py-3 rounded bg-teal-700 text-white hover:bg-teal-600 focus:outline-none my-1"
                >
                    Send Password Reset Link
                </button>
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
