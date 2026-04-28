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

    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {

            if (Newsletter::isSubscribed($request->email)) {
                return back()->with('success', 'Email is already subscribed!');
            } else {
                return back()->with('error', 'Email is NOT subscribed!');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function dashboard()
    {
        try {
            $members = Newsletter::getMembers();
            $count = $members['total_items'] ?? 0;

            return view('dashboard', compact('count'));
        } catch (\Exception $e) {
            return view('dashboard', ['count' => 0]);
        }
    }
}
