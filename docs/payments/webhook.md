# Webhooks

## What is a Webhook

A WebHook is an HTTP callback: an HTTP POST that occurs when something happens; a simple event-notification via HTTP POST. A web application implementing WebHooks will POST a message to a URL when certain things happen.

Flutterwave sends webhooks events that notify your application any time a payment event happens on your account. This is very useful for events - like getting paid via mobile money or USSD where the transaction is completed outside your application- Recurring billing where an API call is not needed for subsequent billings.

In Flutterwave you can setup webhooks that would let us notify you anytime events- A user on a subscription is charged, a customer completes a payment, we update a pending payment to successful- happen in your account.

## When to use webhooks

Webhooks can be used for all kinds of payment methods, card, account, USSD, Mpesa, and Ghana Mobile money.

If you use Flutterwave to accept alternate payment methods like USSD, Mpesa, and Ghana mobile money, it is best practice to use webhooks so that your integration can be notified about changes the status of the payment once it is completed. This is because these payment methods are asynchronous and responses only come once the customer has completed the payment on their device.

You might also use webhooks to:

- Update a customer's membership record in your database when a subscription payment succeeds.

- Email a customer when a subscription payment fails.

- Update your database when the status of a pending payment is updated to successful.

NB: Not in all cases would you be able to rely completely on webhooks to get notified, an example is if your server is experiencing a downtime and your hook endpoints are affected, some customers might still be transacting independently of that and the hook call triggered would fail because your server was unreachable.

In such cases we advise that developers set up a re-query service that goes to poll for the transaction status at regular intervals e.g. every hour using the Verify Payment endpoint, till a successful or failed response is returned.

## How to setup webhooks on your dashboard.

<img src="https://files.readme.io/6fc5add-Screenshot_2018-01-19_11.45.24.png" style="margin: 0 auto;" >

<p style="text-align: center">Login to you Flutterwave dashboard then click on settings , on the setting page navigate to webhooks to add a webhook.</p>

<img src="https://files.readme.io/fd1589b-webhook.png" style="margin: 0 auto;" >

<p style="text-align: center">Once on the webhook page, click the input text to add your webhook and use the save action button to save it.</p>

## Receiving a webhook notification

### 1. Add your webhook secret hash to your `.env`

```bash
FLW_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
FLW_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
FLW_SECRET_HASH='MY_POTTY_PATTY'
```

### 2. Setup Routes

```php
Route::post('/webhook/flutterwave', [FlutterwaveController::class, 'webhook'])->name('webhook');
```

### 3. Grant CSRF Access to Flutterwave Webhook

Go to `app/Http/Middleware/VerifyCsrfToken.php` and add your webhook url to the `$except` array

```php
protected $except = [
    '/webhook/flutterwave',
];
```

### 4. Setup your Controller

> Setup your controller to handle the routes. I created the `FlutterwaveController`. Use the `Flutterwave`
> facade.

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{

  /**
   * Receives Flutterwave webhook
   * @return void
   */
  public function webhook(Request $request)
  {
    //This verifies the webhook is sent from Flutterwave
    $verified = Flutterwave::verifyWebhook();

    // if it is a charge event, verify and confirm it is a successful transaction
    if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
        $verificationData = Flutterwave::verifyPayment($request->data['id']);
        if ($verificationData['status'] === 'success') {
        // process for successful charge

        }

    }
  }

}

```

## Webhook Samples

### Successful Payment

```json
{
  "event": "charge.completed",
  "data": {
    "id": 285959875,
    "tx_ref": "Links-616626414629",
    "flw_ref": "PeterEkene/FLW270177170",
    "device_fingerprint": "a42937f4a73ce8bb8b8df14e63a2df31",
    "amount": 100,
    "currency": "NGN",
    "charged_amount": 100,
    "app_fee": 1.4,
    "merchant_fee": 0,
    "processor_response": "Approved by Financial Institution",
    "auth_model": "PIN",
    "ip": "197.210.64.96",
    "narration": "CARD Transaction ",
    "status": "successful",
    "payment_type": "card",
    "created_at": "2020-07-06T19:17:04.000Z",
    "account_id": 17321,
    "customer": {
      "id": 215604089,
      "name": "Yemi Desola",
      "phone_number": null,
      "email": "user@gmail.com",
      "created_at": "2020-07-06T19:17:04.000Z"
    },
    "card": {
      "first_6digits": "123456",
      "last_4digits": "7889",
      "issuer": "VERVE FIRST CITY MONUMENT BANK PLC",
      "country": "NG",
      "type": "VERVE",
      "expiry": "02/23"
    }
  }
}
```

### Successful Transfers

```json
{
  "event": "transfer.completed",
  "event.type": "Transfer",
  "data": {
    "id": 33286,
    "account_number": "0690000033",
    "bank_name": "ACCESS BANK NIGERIA",
    "bank_code": "044",
    "fullname": "Bale Gary",
    "created_at": "2020-04-14T16:39:17.000Z",
    "currency": "NGN",
    "debit_currency": "NGN",
    "amount": 30020,
    "fee": 26.875,
    "status": "SUCCESSFUL",
    "reference": "a0a827b1eca65311_PMCKDU_5",
    "meta": null,
    "narration": "lolololo",
    "approver": null,
    "complete_message": "Successful",
    "requires_approval": 0,
    "is_approved": 1
  }
}
```

## Best practices

If your webhook script performs complex logic, or makes network calls, it's possible that the script would time out before Flutterwave sees its complete execution. For that reason, you might want to have your webhook endpoint immediately acknowledge receipt by returning a 2xx HTTP status code, and then perform the rest of its duties.

Webhook endpoints might occasionally receive the same event more than once. We advise you to guard against duplicated event receipts by making your event processing [idempotent](https://en.wikipedia.org/wiki/Idempotence). One way of doing this is logging the events you've processed, and then checking if the status has changed before processing the identical event. Additionally, we recommend [verifying webhook signatures](/verify-payment.html) to confirm that received events are being sent from Flutterwave.
