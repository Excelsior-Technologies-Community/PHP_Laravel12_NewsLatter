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
            margin: 25px 0;
            border: none;
            border-top: 1px solid #eee;
        }

    </style>

</head>
<body>

<div class="container">

    <h1>Newsletter</h1>
    <p class="subtitle">Subscribe to receive latest updates</p>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif


    {{-- Subscribe Form --}}
    <form method="POST" action="{{ route('subscribe') }}">

        @csrf

        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="subscribe-btn">
            Subscribe
        </button>

    </form>


    <hr>


    {{-- Unsubscribe Form --}}
    <form method="POST" action="{{ route('unsubscribe') }}">

        @csrf

        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="unsubscribe-btn">
            Unsubscribe
        </button>

    </form>

</div>

</body>
</html>
