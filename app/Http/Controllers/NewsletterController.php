<?php
// app/Http/Controllers/NewsletterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Facades\Newsletter;
use App\Models\Subscriber;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    // Show newsletter form
    public function index()
    {
        return view('newsletter');
    }

    // Subscribe email with name
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255'
        ]);

        try {
            // Check if already subscribed in Mailchimp
            if (Newsletter::isSubscribed($request->email)) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Email is already subscribed!'], 400);
                }
                return back()->with('error', 'Email is already subscribed!');
            }

            // Subscribe to Mailchimp
            if (Newsletter::subscribe($request->email, [
                'FNAME' => $request->name ?? '',
                'LNAME' => ''
            ])) {
                // Save to local database
                Subscriber::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'name' => $request->name,
                        'status' => 'active',
                        'subscribed_at' => Carbon::now(),
                        'unsubscribed_at' => null
                    ]
                );

                if ($request->ajax()) {
                    return response()->json(['success' => 'Email subscribed successfully!']);
                }
                return back()->with('success', 'Email subscribed successfully!');
            } else {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Failed to subscribe.'], 400);
                }
                return back()->with('error', 'Failed to subscribe.');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Unsubscribe email
    public function unsubscribe($email)
    {
        try {
            if (Newsletter::isSubscribed($email)) {
                Newsletter::unsubscribe($email);
                
                // Update local database
                $subscriber = Subscriber::where('email', $email)->first();
                if ($subscriber) {
                    $subscriber->update([
                        'status' => 'unsubscribed',
                        'unsubscribed_at' => Carbon::now()
                    ]);
                }
                
                return view('unsubscribe-success', ['email' => $email]);
            }
            
            return view('unsubscribe-failed', ['email' => $email]);
        } catch (\Exception $e) {
            return view('unsubscribe-error', ['error' => $e->getMessage()]);
        }
    }

    // Check subscription status
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $isSubscribed = Newsletter::isSubscribed($request->email);
            $localSubscriber = Subscriber::where('email', $request->email)->first();
            
            $status = $isSubscribed ? 'subscribed' : 'not subscribed';
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => $status,
                    'mailchimp_status' => $isSubscribed ? 'Active' : 'Inactive',
                    'local_status' => $localSubscriber ? $localSubscriber->status : 'No record found',
                    'subscribed_date' => $localSubscriber ? $localSubscriber->subscribed_at : null
                ]);
            }
            
            return back()->with('status_result', [
                'email' => $request->email,
                'is_subscribed' => $isSubscribed,
                'local_subscriber' => $localSubscriber
            ]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    // Dashboard with statistics
    public function dashboard()
    {
        try {
            // Get Mailchimp data
            $members = Newsletter::getMembers();
            $mailchimpCount = $members['total_items'] ?? 0;
            
            // Get local database data
            $localActiveCount = Subscriber::active()->count();
            $localUnsubscribedCount = Subscriber::unsubscribed()->count();
            $totalLocalCount = Subscriber::count();
            
            // Get recent subscribers
            $recentSubscribers = Subscriber::orderBy('created_at', 'desc')->take(10)->get();
            
            // Get statistics by month
            $monthlyStats = Subscriber::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get();
            
            return view('dashboard', compact(
                'mailchimpCount', 
                'localActiveCount', 
                'localUnsubscribedCount',
                'totalLocalCount',
                'recentSubscribers',
                'monthlyStats'
            ));
        } catch (\Exception $e) {
            return view('dashboard', [
                'mailchimpCount' => 0,
                'localActiveCount' => Subscriber::active()->count(),
                'localUnsubscribedCount' => Subscriber::unsubscribed()->count(),
                'totalLocalCount' => Subscriber::count(),
                'recentSubscribers' => Subscriber::orderBy('created_at', 'desc')->take(10)->get(),
                'monthlyStats' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }

    // Export subscribers to CSV
    public function exportSubscribers()
    {
        $subscribers = Subscriber::all();
        
        $filename = 'subscribers_' . date('Y-m-d') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($handle, ['ID', 'Email', 'Name', 'Status', 'Subscribed At', 'Unsubscribed At', 'Created At']);
        
        // Add data rows
        foreach ($subscribers as $subscriber) {
            fputcsv($handle, [
                $subscriber->id,
                $subscriber->email,
                $subscriber->name,
                $subscriber->status,
                $subscriber->subscribed_at,
                $subscriber->unsubscribed_at,
                $subscriber->created_at
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function() use ($subscribers) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'Email', 'Name', 'Status', 'Subscribed At', 'Unsubscribed At', 'Created At']);
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->id,
                        $subscriber->email,
                        $subscriber->name,
                        $subscriber->status,
                        $subscriber->subscribed_at,
                        $subscriber->unsubscribed_at,
                        $subscriber->created_at
                    ]);
                }
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    // Bulk unsubscribe
    public function bulkUnsubscribe(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email'
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($request->emails as $email) {
            try {
                if (Newsletter::isSubscribed($email)) {
                    Newsletter::unsubscribe($email);
                    Subscriber::where('email', $email)->update([
                        'status' => 'unsubscribed',
                        'unsubscribed_at' => Carbon::now()
                    ]);
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        return back()->with('success', "Unsubscribed: $successCount, Failed: $failCount");
    }
}