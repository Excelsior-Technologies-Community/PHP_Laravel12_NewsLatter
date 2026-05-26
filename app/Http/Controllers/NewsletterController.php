<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Facades\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('newsletter');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            if (Newsletter::isSubscribed($request->email)) {
                return response()->json(['error' => 'Email is already subscribed!'], 400);
            }

            if (Newsletter::subscribe($request->email)) {
                return response()->json(['success' => 'Email subscribed successfully!']);
            } else {
                return response()->json(['error' => 'Failed to subscribe.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function unsubscribe($email)
    {
        try {
            if (Newsletter::isSubscribed($email)) {
                Newsletter::unsubscribe($email);
                return "<h1>✅ You have been successfully unsubscribed. We will miss you!</h1>";
            }
            
            return "<h1>⚠️ You are already unsubscribed or email not found.</h1>";
        } catch (\Exception $e) {
            return "<h1>❌ Error: " . $e->getMessage() . "</h1>";
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