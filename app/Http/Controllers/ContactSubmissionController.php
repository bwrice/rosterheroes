<?php

namespace App\Http\Controllers;

use App\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
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
