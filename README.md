# PHP_Laravel12_NewsLatter


## Project Description

PHP_Laravel12_NewsLatter is a Laravel 12 based web application that demonstrates how to implement a complete Newsletter Subscription System using the Spatie Laravel Newsletter package and Mailchimp integration.

This project allows users to subscribe and unsubscribe their email addresses to a newsletter through a simple and modern user interface. When a user subscribes, their email is automatically added to a Mailchimp Audience List. When they unsubscribe, the email is removed from the list.

The main purpose of this project is to show how Laravel applications can integrate with external email marketing services like Mailchimp to manage newsletter subscribers efficiently.


## Project Objectives

The objectives of this project are:

- To integrate Spatie Laravel Newsletter package in Laravel 12

- To connect Laravel with Mailchimp using API Key and Audience List ID

- To create a newsletter subscription and unsubscription system

- To build a clean and user-friendly interface using Blade and CSS

- To demonstrate real-world newsletter functionality in Laravel


## Features:

- Subscribe email to Mailchimp
- Unsubscribe email from Mailchimp
- Modern UI using Blade and CSS
- Laravel 12 compatible
- Uses official Spatie Newsletter package


## Requirements

- PHP 8.2+
- Laravel 12
- Composer
- Mailchimp account
- XAMPP / MySQL (optional)


---


#  Laravel 12 + NewsLatter

---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_NewsLatter "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_NewsLatter

```

#### Explanation:

This command creates a new Laravel 12 project folder with all required Laravel files.




## STEP 2: Database Setup (Optional - Not required for Mailchimp)

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_newslatter
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_newslatter

```

#### Explanation:

Database is optional because Mailchimp stores emails, not your local database.




## STEP 3: Install Newsletter Package

### Run:

```
composer require spatie/laravel-newsletter

```

### Install Mailchimp API:

```
composer require drewm/mailchimp-api

```

#### Explanation:

This installs the package that connects Laravel with Mailchimp to manage subscribers.



## STEP 4: Publish Config File

### Run:

```
php artisan vendor:publish --tag="newsletter-config"

```

### File created:

```
config/newsletter.php

```

#### Explanation:

This creates config/newsletter.php where Mailchimp API settings are defined.




## STEP 5: Configure newsletter.php

### Open:

```
config/newsletter.php

```

### Replace full code with:

```

<?php

use Spatie\Newsletter\Drivers\MailChimpDriver;

return [

    'driver' => MailChimpDriver::class,

    'driver_arguments' => [
        'api_key' => env('NEWSLETTER_API_KEY'),
        'endpoint' => env('NEWSLETTER_ENDPOINT'),
    ],

    'default_list_name' => 'subscribers',

    'lists' => [
        'subscribers' => [
            'id' => env('NEWSLETTER_LIST_ID'),
        ],
    ],

];

```

#### Explanation:

This tells Laravel which Mailchimp audience list to use for storing emails.




## STEP 6: Configure .env file

### STEP 6.1: Create Free Mailchimp Account

1. Go to:

```
https://mailchimp.com/

```

2. Sign up → verify email → login

3. Mailchimp is provided by Mailchimp.


### STEP 6.2: Get Mailchimp API Key

After login:

1. Click Profile icon (top right)

2. Click Account & Billing

3. Click Extras

4. Click API Keys

5. Click Create A Key

6. Copy the API key

#### Example:

```
abcd123456789-us21

```

### STEP 6.3: Create Audience List and Get List ID

1. Click Audience

2. Click All contacts

3. Click Settings

4. Click Audience name and defaults

5. Copy Audience ID

#### Example:

```
a1b2c3d4e5

```

This is your LIST ID.

### STEP 6.4: Clear Cache and Configure .env

#### Open .env and add:

```
NEWSLETTER_API_KEY=your_mailchimp_api_key
NEWSLETTER_LIST_ID=your_mailchimp_list_id

```
#### Example:

```
NEWSLETTER_API_KEY=abcd1234-us21
NEWSLETTER_LIST_ID=a1b2c3d4e5

```
#### Now run:

```
php artisan config:clear

php artisan cache:clear

php artisan optimize:clear

composer dump-autoload

```


#### Explanation:

API Key connects Laravel to Mailchimp and List ID specifies where emails are saved.



## STEP 7: Create Controller

### Run:

```
php artisan make:controller NewsletterController

```

### File: app/Http/Controllers/NewsletterController.php

```

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

```

#### Explanation:

Controller handles subscribe and unsubscribe logic.





## STEP 8: Create View File

### Create file: resources/views/newsletter.blade.php

```
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

```

#### Explanation:

This file creates the user interface form for email subscription.




## STEP 9: routes/web.php (FULL CODE)

### Open: routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsletterController;

Route::get('/', [NewsletterController::class, 'index']);

Route::post('/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('subscribe');

Route::post('/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->name('unsubscribe');

```


#### Explanation:

Routes connect the form with controller functions.




## STEP 10: Launch the Server

### Run:

```
php artisan serve

```
### Then open your browser:

```
http://localhost:8000

```

#### Explanation:

Starts Laravel server so you can open project in browser.




## So you can see this type Output:


### Newsletter Subscription Form


<img width="1919" height="967" alt="Screenshot 2026-02-16 123655" src="https://github.com/user-attachments/assets/edcdfa97-4206-4fd8-8212-a8c0e18d06e1" />


### Successful Subscription


<img width="1919" height="965" alt="Screenshot 2026-02-16 123707" src="https://github.com/user-attachments/assets/62777f02-f05a-42d8-9d6c-a1769fc885f9" />


### Mailchimp Audience List


<img width="1919" height="832" alt="Screenshot 2026-02-16 123748" src="https://github.com/user-attachments/assets/f53c3002-cd42-42fb-a5d5-3c8c7512d7bf" />


### Newsletter UnSubscription Form


<img width="1919" height="963" alt="Screenshot 2026-02-16 123817" src="https://github.com/user-attachments/assets/599366f0-c176-4334-a4bc-3ce577d4a0a8" />


### Successful Unsubscription


<img width="1919" height="959" alt="Screenshot 2026-02-16 123825" src="https://github.com/user-attachments/assets/eeb7577a-6b17-4668-b248-5b85d8e7ad3a" />


### Mailchimp Audience List After Unsubscribe


<img width="1919" height="833" alt="Screenshot 2026-02-16 124002" src="https://github.com/user-attachments/assets/3926b2b6-ad65-499d-80c0-d49aa30f15cb" />



---

# Project Folder Structure:

```
PHP_Laravel12_NewsLatter
│
├── app
│   └── Http
│       └── Controllers
│           └── NewsletterController.php
│
├── config
│   └── newsletter.php
│
├── resources
│   └── views
│       └── newsletter.blade.php
│
├── routes
│   └── web.php
│
├── .env
│
└── composer.json

```
