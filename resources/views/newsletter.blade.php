<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel 12 Newsletter</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            color: #333;
        }

        p.subtitle {
            color: #777;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="email"]:focus {
            border-color: #667eea;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 5px;
        }

        .subscribe-btn {
            background: #667eea;
            color: white;
        }

        .subscribe-btn:hover {
            background: #5563d6;
        }

        .unsubscribe-btn {
            background: #e74c3c;
            color: white;
        }

        .unsubscribe-btn:hover {
            background: #c0392b;
        }

        .status-btn {
            background: #2ecc71;
            color: white;
        }

        .status-btn:hover {
            background: #27ae60;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
    </style>

</head>
<body>

<div class="container">

    <h1>Newsletter</h1>
    <p class="subtitle">Subscribe to receive latest updates</p>

    <div id="messageBox"></div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif


    <form id="subscribeForm">
        @csrf
        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="subscribe-btn">
            Subscribe
        </button>
    </form>

    <hr>

    <form id="unsubscribeForm">
        @csrf
        <div class="form-group">
            <input type="email" name="email" id="unsubEmail" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="unsubscribe-btn">
            Unsubscribe
        </button>
    </form>

    <hr>

    <form method="POST" action="{{ route('check.status') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" placeholder="Check subscription status" required>
        </div>
        <button type="submit" class="status-btn">
            Check Status
        </button>
    </form>

</div>

<script>
    document.getElementById('subscribeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let btn = this.querySelector('button');
        let messageBox = document.getElementById('messageBox');
        let formData = new FormData(this);
        
        btn.innerHTML = 'Subscribing...';
        btn.disabled = true;

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
                messageBox.innerHTML = '<div class="success">' + data.success + '</div>';
                this.reset();
            } else {
                let errorMsg = data.error || 'Something went wrong';
                messageBox.innerHTML = '<div class="error">' + errorMsg + '</div>';
            }
            btn.innerHTML = 'Subscribe';
            btn.disabled = false;
        })
        .catch(error => {
            messageBox.innerHTML = '<div class="error">Server Error!</div>';
            btn.innerHTML = 'Subscribe';
            btn.disabled = false;
        });
    });

    document.getElementById('unsubscribeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let email = document.getElementById('unsubEmail').value;
        window.location.href = '/unsubscribe/' + encodeURIComponent(email);
    });
</script>

</body>
</html>