<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Facades\Newsletter;

class NewsletterController extends Controller
{

    // Show form
    public function index()
    {
        return view('newsletter');
    }

    // Subscribe email
    public function subscribe(Request $request)
    {

        $request->validate([
            'email' => 'required|email'
        ]);

        try {

            if (Newsletter::subscribe($request->email)) {

                return back()->with('success', 'Email subscribed successfully!');

            } else {

                return back()->with('error', 'Email already subscribed or failed.');
            }

        } catch (\Exception $e) {

            return back()->with('error', 'Error: ' . $e->getMessage());
        }

    }

    // Unsubscribe email
    public function unsubscribe(Request $request)
    {

        $request->validate([
            'email' => 'required|email'
        ]);

        try {

            Newsletter::unsubscribe($request->email);

            return back()->with('success', 'Email unsubscribed successfully!');

        } catch (\Exception $e) {

            return back()->with('error', 'Error: ' . $e->getMessage());
        }

    }

}
