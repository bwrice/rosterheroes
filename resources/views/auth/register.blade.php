@extends('layouts.tailwind')

@section('body')
<body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

<div class="min-h-screen flex flex-col">
    <form  method="POST" action="{{ route('register') }}" class="container max-w-sm mx-auto flex-1 flex flex-col items-center justify-center px-2">
        @csrf
        <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">
            <h1 class="mb-8 text-3xl text-center text-teal-900">Register</h1>
            <input
                id="name"
                type="text"
                class="block border w-full p-3 rounded mb-4 {{ $errors->has('name') ? 'border-red-700' : 'border-gray-500' }}"
                name="name"
                placeholder="Full Name"
                value="{{ old('name') }}"
                required
                {{$errors->has('name') ? '' : 'autofocus'}}
            >
            @if ($errors->has('name'))
                <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $errors->first('name') }}</span>
                </div>
            @endif

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

            <input
                type="password"
                class="block border w-full p-3 rounded mb-4 {{ $errors->has('password') ? 'border-red-700' : 'border-gray-500' }}"
                name="password"
                placeholder="Password"
                required
            >
            @if ($errors->has('password'))
                <div class="rh-error bg-red-100 border border-red-400 text-red-700 px-4 py-4 mb-4 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $errors->first('password') }}</span>
                </div>
            @endif

            <input
                id="password-confirm"
                type="password"
                class="block border w-full p-3 rounded mb-4 border-gray-500"
                name="password_confirmation"
                placeholder="Confirm Password"
                required
            >

            <button
                type="submit"
                class="w-full text-center py-3 rounded bg-teal-700 text-white hover:bg-teal-600 focus:outline-none my-1"
            >Create Account</button>

            <div class="text-center text-sm text-grey-dark mt-4">
                By registering, you agree to the
                <a class="no-underline border-b border-teal-900 text-teal-900" href="/terms">
                    Terms of Service
                </a> and
                <a class="no-underline border-b border-teal-900 text-teal-900" href="/privacy">
                    Privacy Policy
                </a>
            </div>

            <div class="w-full mt-4 mb-1">
                <div class="h-1 mx-auto bg-teal-800 opacity-50 my-0 py-0 rounded"></div>
            </div>

            <div class="flex pt-2 flex-col w-full justify-center">

                <a class="mx-auto w-2/3 sm:w-3/4" href="/login/google">
                    <img alt="signup through google" src="{{asset('/images/btn_google_signin_dark_normal_web@2x.png')}}">
                </a>
            </div>
        </div>

        <div class="text-gray-400 mt-6">
            Already have an account?
            <a class="no-underline border-b border-teal-300 text-teal-300" href="/login">
                Log in
            </a>.
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
