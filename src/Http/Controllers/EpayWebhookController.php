<?php

namespace TheHocineSaad\LaravelChargilyEPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class EpayWebhookController
{
    public function test()
    {
        if (! App::environment(['local', 'testing'])) {
            abort(403);
        }

        return view('laravel-chargily-epay::epay_webhook.test');
    }

    public function test_store(Request $request)
    {
        if (! App::environment(['local', 'testing'])) {
            abort(403);
        }

        $body_content = [
            'invoice' => [
                'id' => 100000,
                'client' => 'Test Client',
                'client_email' => 'testclient@mail.com',
                'invoice_number' => $request->invoice_id,
                'status' => 'paid',
                'amount' => 5000,
                'fee' => 75,
                'discount' => 0,
                'due_amount' => 5075,
                'comment' => 'Payment for T-Shirt',
                'mode' => 'CIB',
                'new' => 1,
                'tos' => 1,
                'back_url' => 'https://www.domain.com/',
                'invoice_token' => 'random_token_here',
                'api_key_id' => null,
                'meta_data' => null,
                'due_date' => '2022-04-27 00:00:00',
                'created_at' => '2022-04-27 20:59:07',
                'updated_at' => '2022-04-27 21:01:09',
            ],
        ];

        $signature = hash_hmac('sha256', collect($body_content)->toJson(), config('laravel-chargily-epay.secret'));

        $response = Http::withHeaders([
            'Content-Type' => 'text/plain',
            'Signature' => $signature,
        ])->post(config('laravel-chargily-epay.webhook_url'), $body_content);

        $response->onError(function () {
            return 'an error has been occured, please try again';
        });

        return back()->withInput()->with('paid_invoice', $request->invoice_id);
    }
}
