<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: Arial, Helvetica, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 40px 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            border-bottom: 5px solid #667eea;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card h5 {
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .stat-card h1 {
            font-size: 4rem;
            font-weight: 700;
            color: #333;
            margin-top: 15px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="dashboard-header text-center">
        <h2 class="mb-0">📊 Mailchimp Admin Dashboard</h2>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="stat-card">
                    <h5>Total Active Subscribers</h5>
                    <h1>{{ $count }}</h1>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/" class="btn btn-outline-secondary px-4 py-2">
                ← Back to Newsletter Form
            </a>
        </div>
    </div>

</body>
</html>