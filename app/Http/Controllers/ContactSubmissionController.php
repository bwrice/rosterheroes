<?php

namespace App\Http\Controllers;

use App\ContactSubmission;
use App\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactSubmissionController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->type;
        if (! in_array($type, ['contact', 'support'])) {
            $type = 'contact';
        }

        if ($type === 'contact') {
            $buttonText = 'Contact Us';
            $messagePlaceHolder = 'What would you like to say?';
        } else {
            $buttonText = 'Contact Support';
            $messagePlaceHolder = 'What can we help you with?';
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $userEmail = $user->email;
            $userName = $user->name;
        } else {
            $userEmail = null;
            $userName = null;
        }

        return view('contact', [
            'type' => $type,
            'buttonText' => $buttonText,
            'messagePlaceHolder' => $messagePlaceHolder,
            'userEmail' => $userEmail,
            'userName' => $userName
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'reason' => 'required',
            'message' => 'required',
            'type' => 'required',
        ]);

        ContactSubmission::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'reason' => $request->reason,
            'message' => $request->message,
            'type' => $request->type
        ]);

        return redirect('/');
    }
}
