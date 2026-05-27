<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #999;
            font-size: 12px;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: #f8f9fa;
            color: #555;
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-unsubscribed {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .chart-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 30px;
            justify-content: center;
            min-height: 200px;
            padding: 20px 0;
        }

        .bar {
            flex: 1;
            text-align: center;
        }

        .bar-fill {
            background: linear-gradient(135deg, #667eea, #764ba2);
            width: 100%;
            border-radius: 8px 8px 0 0;
            transition: height 0.5s;
        }

        .bar-label {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        .bar-value {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
            
            .bar-chart {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Newsletter Dashboard</h1>
        <p>Manage and monitor your newsletter subscribers</p>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Mailchimp Subscribers</h3>
                <div class="stat-number">{{ $mailchimpCount ?? 0 }}</div>
                <div class="stat-label">Total in Mailchimp</div>
            </div>

            <div class="stat-card">
                <h3>Active Subscribers</h3>
                <div class="stat-number">{{ $localActiveCount ?? 0 }}</div>
                <div class="stat-label">Active in Local DB</div>
            </div>

            <div class="stat-card">
                <h3>Unsubscribed</h3>
                <div class="stat-number">{{ $localUnsubscribedCount ?? 0 }}</div>
                <div class="stat-label">Unsubscribed Users</div>
            </div>

            <div class="stat-card">
                <h3>Total Records</h3>
                <div class="stat-number">{{ $totalLocalCount ?? 0 }}</div>
                <div class="stat-label">Total in Local DB</div>
            </div>
        </div>

        @if(isset($monthlyStats) && $monthlyStats->count() > 0)
        <div class="section">
            <h2>Monthly Subscription Trends</h2>
            <div class="chart-container">
                <div class="bar-chart">
                    @php
                        $maxCount = $monthlyStats->max('total') ?: 1;
                    @endphp
                    @foreach($monthlyStats as $stat)
                        @php
                            $height = ($stat->total / $maxCount) * 150;
                        @endphp
                        <div class="bar">
                            <div class="bar-value">{{ $stat->total }}</div>
                            <div class="bar-fill" style="height: {{ $height }}px;"></div>
                            <div class="bar-label">{{ $stat->month }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="section">
            <h2>Recent Subscribers</h2>
            @if(isset($recentSubscribers) && $recentSubscribers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Subscribed Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubscribers as $subscriber)
                    <tr>
                        <td>{{ $subscriber->id }}</td>
                        <td>{{ $subscriber->email }}</td>
                        <td>{{ $subscriber->name ?? '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ $subscriber->status }}">
                                {{ ucfirst($subscriber->status) }}
                            </span>
                        </td>
                        <td>{{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('Y-m-d H:i') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>No subscribers found.</p>
            @endif
        </div>

        <div class="section">
            <h2>Actions</h2>
            <div class="button-group">
                <a href="/" class="btn btn-secondary">Back to Newsletter</a>
                <a href="{{ route('export.subscribers') }}" class="btn btn-primary">Export to CSV</a>
            </div>
        </div>
    </div>
</body>
</html>