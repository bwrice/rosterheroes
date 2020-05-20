@extends('layouts.tailwind')

@section('body')
    <?php
    /** @var \App\Domain\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    ?>

    <body style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">

    <div class="min-h-screen container max-w-sm mx-auto flex-1 flex flex-col justify-center px-2">
        <div>
            <div class="px-6 py-8 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">

                <h1 class="mb-2 text-2xl text-center text-teal-900">Verify Your Email Address</h1>
                @if (session('resent'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">A fresh verification link has been sent to {{ $user->email }}</span>
                    </div>
                @endif
                <p class="text-center text-teal-900 mb-4">
                    Before proceeding, please check your email, <span class="text-teal-700">{{$user->email}}</span>, for a verification link.
                </p>
                <p class="text-center text-teal-900 mb-2">
                    If you did not receive the email
                </p>
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-center py-3 rounded bg-teal-700 text-white hover:bg-teal-600 focus:outline-none my-1"
                    >request another verification link
                    </button>

                </form>
            </div>

            <div class="text-gray-400 mt-6 flex justify-center">
                Here by mistake? &nbsp;&nbsp;
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="no-underline border-b border-teal-300 text-teal-300"
                    >Logout
                    </button>

                </form>
            </div>
        </div>
    </div>
    </body>
@endsection

