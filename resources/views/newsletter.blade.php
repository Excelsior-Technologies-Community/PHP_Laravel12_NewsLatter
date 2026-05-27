<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscription</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-info {
            background: #3498db;
            color: white;
        }

        .btn-info:hover {
            background: #2980b9;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 12px;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.3);
        }

        .status-result {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 14px;
        }

        .status-result p {
            margin: 5px 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Newsletter</h1>
            <p class="subtitle">Subscribe to receive latest updates</p>

            <div id="messageBox"></div>

            @if(session('success'))
                <div class="alert alert-success fade-in">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error fade-in">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Subscribe Form -->
            <form id="subscribeForm">
                @csrf
                <div class="form-group">
                    <label>Full Name (Optional)</label>
                    <input type="text" name="name" placeholder="Enter your name" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required autocomplete="off">
                </div>
                <button type="submit" class="btn-primary" id="subscribeBtn">
                    Subscribe Now
                </button>
            </form>

            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Check Status Form -->
            <form id="checkStatusForm">
                @csrf
                <div class="form-group">
                    <label>Check Subscription Status</label>
                    <input type="email" name="email" placeholder="Enter email to check" required>
                </div>
                <button type="submit" class="btn-info" id="checkStatusBtn">
                    Check Status
                </button>
            </form>

            <div id="statusDisplay" style="display: none;" class="status-result fade-in"></div>
        </div>

        <div class="nav-links">
            <a href="/dashboard" class="nav-link">View Dashboard</a>
        </div>
    </div>

    <script>
        // Subscribe Form Handler
        document.getElementById('subscribeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let btn = document.getElementById('subscribeBtn');
            let messageBox = document.getElementById('messageBox');
            let formData = new FormData(this);
            
            btn.innerHTML = 'Subscribing...';
            btn.disabled = true;
            messageBox.innerHTML = '';

            fetch('{{ route('subscribe') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                let data = await response.json();
                if(response.ok) {
                    messageBox.innerHTML = '<div class="alert alert-success fade-in">' + data.success + '</div>';
                    document.getElementById('subscribeForm').reset();
                } else {
                    let errorMsg = data.error || 'Something went wrong';
                    messageBox.innerHTML = '<div class="alert alert-error fade-in">' + errorMsg + '</div>';
                }
                btn.innerHTML = 'Subscribe Now';
                btn.disabled = false;
                
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.fade-in');
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 3000);
            })
            .catch(error => {
                messageBox.innerHTML = '<div class="alert alert-error fade-in">Server Error! Please try again.</div>';
                btn.innerHTML = 'Subscribe Now';
                btn.disabled = false;
            });
        });

        // Check Status Form Handler
        document.getElementById('checkStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let btn = document.getElementById('checkStatusBtn');
            let statusDisplay = document.getElementById('statusDisplay');
            let formData = new FormData(this);
            let email = this.querySelector('input[name="email"]').value;
            
            btn.innerHTML = 'Checking...';
            btn.disabled = true;

            fetch('{{ route('check.status') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                let data = await response.json();
                if(response.ok) {
                    statusDisplay.style.display = 'block';
                    statusDisplay.innerHTML = `
                        <p><strong>Email:</strong> ${email}</p>
                        <p><strong>Mailchimp Status:</strong> ${data.mailchimp_status}</p>
                        <p><strong>Local Database Status:</strong> ${data.local_status}</p>
                        ${data.subscribed_date ? `<p><strong>Subscribed Date:</strong> ${new Date(data.subscribed_date).toLocaleDateString()}</p>` : ''}
                    `;
                } else {
                    statusDisplay.style.display = 'block';
                    statusDisplay.innerHTML = `<p style="color: #e74c3c;"><strong>Error:</strong> ${data.error}</p>`;
                }
                btn.innerHTML = 'Check Status';
                btn.disabled = false;
                
                setTimeout(() => {
                    statusDisplay.style.opacity = '0';
                    setTimeout(() => {
                        statusDisplay.style.display = 'none';
                        statusDisplay.style.opacity = '1';
                    }, 500);
                }, 5000);
            })
            .catch(error => {
                statusDisplay.style.display = 'block';
                statusDisplay.innerHTML = '<p style="color: #e74c3c;"><strong>Error:</strong> Server Error! Please try again.</p>';
                btn.innerHTML = 'Check Status';
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>