<p align="center"><img src="https://i.imgur.com/j3fZ4yK.png"></p>
<h1 align="center">Laravel-Chargily-ePay</h1>

**Laravel-Chargily-ePay** is a Laravel package that provides an easy interface to [Chargily ePay gateway](https://epay.chargily.com.dz/ "Chargily ePay gateway").

## Features
- Integrating ePayment never was that fast and easy.
- Creating invoices is as easy as calling a function.
- Comes with a Migration that creates `epay_invoices` table with a `user_id` foreign id.
- A trait for the User model that gives you some very useful functions like `$user->charge()` to create an invoice for a user, and `$user->invoices()` to get all user's invoices.
- It comes with a payment webhook handler out of the box, so you just have to add your logic of what happens after a user performs a payment or cancels it.
- It has a webhook tester built in, it's like a sandbox, it's a simulation of a user paying an invoice so you don't have to test your application with real money.

## Installation
### Step 1 - Require the package
    composer require thehocinesaad/laravel-chargily-epay

### Step 2: Publish migrations
	php artisan vendor:publish --provider="TheHocineSaad\LaravelChargilyEPay\LaravelChargilyEPayServiceProvider" --tag="migrations"

### Step 3: Run migrations
    php artisan migrate

### Step 4: Edit `.env` file
This package requires these keys:
- **CHARGILY_EPAY_KEY**: You can get it from [Chargily Epay's dashboard](https://epay.chargily.com.dz/secure/admin/epay-api "Chargily Epay's dashboard").
- **CHARGILY_EPAY_SECRET**: same as the first one.

- **CHARGILY_EPAY_BACK_URL**: This is the URL where the user will be redirected after payment processing.

- **CHARGILY_EPAY_WEBHOOK_URL**: This is the URL of your webhook where Chargily ePay will notify you after payment processing, we will talk about it in a bit.

**Important**: `CHARGILY_EPAY_BACK_URL` and `CHARGILY_EPAY_WEBHOOK_URL` should be real URLs, so you can't put `http://127.0.0.1:8000`, use [Ngrok](https://ngrok.com/ "Ngrok").

### Step 5: Epayable trait
Add the `Epayable` trait to your User model:

```php
use  TheHocineSaad\LaravelChargilyEPay\Traits\Epayable;

class User extends Authenticatable
{
    use Epayable
}
```

### Step 6 (optional): Publishing
#### Config File: 

    php artisan vendor:publish --provider="JohnDoe\BlogPackage\BlogPackageServiceProvider" --tag="config"

#### Models

    php artisan vendor:publish --provider="TheHocineSaad\LaravelChargilyEPay\LaravelChargilyEPayServiceProvider" --tag="models"

## Using the package
### Creating an Epay Invoice
To create an Invoice, you can te use the static `Make()` function from the `Epay_Invoice` model:
```php
use TheHocineSaad\LaravelChargilyEPay\Models\Epay_Invoice;

$configurations = [
        'user_id' => 1, // (optional) This is the user ID to be added as a foreign key, it's optional, if it's not provided its value will be NULL
        'mode' => 'CIB', // Payment method must be 'CIB' or 'EDAHABIA'
        'payment' => [
         'client_name' => 'client name here', // Client name
         'client_email' => 'hello@email.com', // This is where client receives payment receipt after confirmation
            'amount' => 2500, // Must be = or > than 75 
            'discount' => 0, // This is discount percentage, between 0 and 99
            'description' => 'payment for product', // This is the payment description
        ]
    ];

    $checkout_url = Epay_Invoice::make($configurations);
```
The `Make()` function returns the checkout URL, where the user should be redirected to make a payment, If any error occurs, it will return to the home page, so make sure to check this before redirecting the user.

The `Make()` also creates an invoice in your database using the info from the provided **$configurations** array, so if you added columns to the migration file of the invoices table that comes with the package, you have to add the corresponding values as a second array, example:

```php
    $checkout_url = Epay_Invoice::make($configurations, ['product_id' => 1]);
```
As you saw in the example above, we added the value of `product_id`, that's because you may be added `product_id` field in the migration file.

### Creating an Epay Invoice for a User
The other way (Recommended way) to create Epay Invoices is to use the `charge()` custom model method which is provided by the `Epayable` trait, this method will automatically add the name of the client, the email of the client, and the `client_id` foreign key:
```php
$configurations = [
    'mode' => 'CIB',
    'payment' => [
        'amount' => 1000,
        'discount' => 0,
        'description' => 'payment for product',
    ]
];

$checkout_url = $request->user()->charge($configurations);
```
The `charge()` method calls the `Make()` static function at a certain point, so they act the same way, it returns the checkout URL, or the home page URL if any error occurs.

Just like the `Make()` function, you can add a second array to pass any added columns to the `invoices` table.

### Payment Webhook
After a user complete the payment of an invoice, Chargily ePay will notify you by sending a post request to your Webhook, so you can handle the things you would like to happen after a successful or a failed payment.

So, go ahead and create a `POST` Route (ex: "/webhook"), by the way, this is the URL you should add to the `CHARGILY_EPAY_WEBHOOK_URL` key in the `.env` file.

**Important**: You have to exclude this URL from CSRF verification, to do so, add it to the `except` method in your applications's `App\Http\Middleware\VerifyCsrfToken` middleware:

```php
protected $except = [
        'webhook'
];
```

Here is the code you should put in this route as a starting point:

```php
use TheHocineSaad\LaravelChargilyEPay\Epay_Webhook;

$webhookHandler = new Epay_Webhook;

if($webhookHandler -> invoiceIsPaied) {
    // Put here the logic you want to happen if the user actually made the payment.
}else {
    // Put here the logic you want to happen if the user canceled the payment.
}
```
**Note**: You can access the ID of the Invoice by: `$webhookHandler -> invoice['invoice_number']`

Here is an example of the data that comes with the post request from Chargily ePay:
```json
{
   "invoice":{
      "id":100000,
      "client":"Test Client",
      "client_email":"testclient@mail.com",
      "invoice_number":"I-123456789",
      "status":"paid",
      "amount":5000,
      "fee":75,
      "discount":0,
      "due_amount":5075,
      "comment":"Payment for T-Shirt",
      "mode":"CIB",
      "new":1,
      "tos":1,
      "back_url":"https://www.domain.com/",
      "invoice_token":"random_token_here",
      "api_key_id":null,
      "meta_data":null,
      "due_date":"2022-04-27 00:00:00",
      "created_at":"2022-04-27 20:59:07",
      "updated_at":"2022-04-27 21:01:09"
   }
}
```

#### Testing your Webhook
I added an internal feature to simulate a payment of an invoice to test your webhook without the need to use Postman, after installing this package, you will automatically have a new route `/epay-webhook-tester`, navigate to it, put the ID of the invoice you want to simulate its payment and click on PAY.

**Important: ** When you run your application using the local server `php artisan serve`, it will work on a single thread, so making post requests to itself will give you a timeout error, on a server, this is not a problem because it will use Apache or Nginx, the solution is to start another local server on another port (ex: 8001) and use this feature from there.
Example: 
1. Run `php artisan serve` so you will have your app on `http://127.0.0.1:8000`.
2. In another terminal instance, run `php artisan serve --port=8001` then visit `http://127.0.0.1:8001/epay-webhook-tester`, not `http://127.0.0.1:8000/epay-webhook-tester`

**Note: **for security purposes, this feature works only on the local environment (`APP_ENV=local`), and am sure it's not needed in production.

![Test Chargily ePay](https://i.imgur.com/k2yaVSt.png)

## Info
This Laravel package is built on top of [this PHP package](https://github.com/Chargily/chargily-epay-php "this PHP package"), so have a look at it to know more about what's happening under the hood.

### Security
If you discover any security related issues, please email theHocineSaad@gmail.com instead of using the issue tracker.

## License
Laravel-Chargily-ePay project is open-sourced software licensed under the [MIT license](https://github.com/theHocineSaad/laravel-chargily-epay/blob/main/LICENSE.md "MIT license").