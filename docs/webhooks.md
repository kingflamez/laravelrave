# Webhooks

## What is a Webhook

A WebHook is an HTTP callback: an HTTP POST that occurs when something happens; a simple event-notification via HTTP POST. A web application implementing WebHooks will POST a message to a URL when certain things happen.

Rave sends webhooks events that notify your application any time a payment event happens on your account. This is very useful for events - like getting paid via mobile money or USSD where the transaction is completed outside your application- Recurring billing where an API call is not needed for subsequent billings.

In Rave you can setup webhooks that would let us notify you anytime events- A user on a subscription is charged, a customer completes a payment, we update a pending payment to successful- happen in your account.

## When to use webhooks

Webhooks can be used for all kinds of payment methods, card, account, USSD, Mpesa, and Ghana Mobile money.

If you use Rave to accept alternate payment methods like USSD, Mpesa, and Ghana mobile money, it is best practice to use webhooks so that your integration can be notified about changes the status of the payment once it is completed. This is because these payment methods are asynchronous and responses only come once the customer has completed the payment on their device.

You might also use webhooks to:

Update a customer's membership record in your database when a subscription payment succeeds.

Email a customer when a subscription payment fails.

Update your database when the status of a pending payment is updated to successful.

NB: Not in all cases would you be able to rely completely on webhooks to get notified, an example is if your server is experiencing a downtime and your hook endpoints are affected, some customers might still be transacting independently of that and the hook call triggered would fail because your server was unreachable.

In such cases we advise that developers set up a re-query service that goes to poll for the transaction status at regular intervals e.g. every hour using the Verify Payment endpoint, till a successful or failed response is returned.

## Sample Transaction Payload

On Rave, Webhooks can be configured for transactions. When a transaction is completed, a POST HTTP request is sent to the URL you have configured. The HTTP payload will contain

### 1. Card Transaction End Webhook Payload

```json
{
  "id": 126122,
  "txRef": "rave-pos-121775237991",
  "flwRef": "FLW-MOCK-72d0b2d66273fad0bb32fdea9f0fa298",
  "orderRef": "URF_1523185223111_833935",
  "paymentPlan": null,
  "createdAt": "2018-04-08T11:00:23.000Z",
  "amount": 1000,
  "charged_amount": 1000,
  "status": "successful",
  "IP": "197.149.95.62",
  "currency": "NGN",
  "customer": {
    "id": 22836,
    "phone": null,
    "fullName": "Anonymous customer",
    "customertoken": null,
    "email": "salesmode@ravepay.co",
    "createdAt": "2018-04-08T11:00:22.000Z",
    "updatedAt": "2018-04-08T11:00:22.000Z",
    "deletedAt": null,
    "AccountId": 134
  },
  "entity": {
    "card6": "539983",
    "card_last4": "8381"
  }
}
```

### 2. Account Transaction End Webhook Payload

```json
{
  "id": 125837,
  "txRef": "rave-pos-272519815315",
  "flwRef": "FLWACHMOCK-1523118279396",
  "orderRef": "URF_1523118277202_7343035",
  "paymentPlan": null,
  "createdAt": "2018-04-07T16:24:37.000Z",
  "amount": 200,
  "charged_amount": 200,
  "status": "successful",
  "IP": "197.149.95.62",
  "currency": "NGN",
  "customer": {
    "id": 5766,
    "phone": "N/A",
    "fullName": "Anonymous customer",
    "customertoken": null,
    "email": "salesmode@ravepay.co",
    "createdAt": "2017-10-16T10:03:19.000Z",
    "updatedAt": "2017-10-16T10:03:19.000Z",
    "deletedAt": null,
    "AccountId": 134
  },
  "entity": {
    "account_number": "0690000037",
    "first_name": "Dele Moruf",
    "last_name": "Quadri"
  }
}
```

### 3. Ghana Mobile Money End Webhook Payload

```json
{
  "id": 126090,
  "txRef": "rave-checkout-1523183226335",
  "flwRef": "flwm3s4m0c1523183355860",
  "orderRef": null,
  "paymentPlan": null,
  "createdAt": "2018-04-08T10:29:15.000Z",
  "amount": 2000,
  "charged_amount": 2000,
  "status": "successful",
  "IP": "197.149.95.62",
  "currency": "GHS",
  "customer": {
    "id": 22823,
    "phone": "0578922930",
    "fullName": "Anonymous Customer",
    "customertoken": null,
    "email": "user@example.com",
    "createdAt": "2018-04-08T10:28:01.000Z",
    "updatedAt": "2018-04-08T10:28:01.000Z",
    "deletedAt": null,
    "AccountId": 134
  },
  "entity": {
    "id": "NO-ENTITY"
  }
}
```

## How to setup webhooks on your dashboard.

<img src="https://files.readme.io/6fc5add-Screenshot_2018-01-19_11.45.24.png" style="margin: 0 auto;" >

<p style="text-align: center">Login to you Rave dashboard then click on settings , on the setting page navigate to webhooks to add a webhook.</p>

<img src="https://files.readme.io/6bda58d-Screenshot_2018-04-07_16.24.27.png" style="margin: 0 auto;" >

<p style="text-align: center">Once on the webhook page, click the input text to add your webhook and use the save action button to save it.</p>

## Receiving a webhook notification

### 1. Add your webhook secret hash to your `.env`
```bash
RAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_ENVIRONMENT="staging"
RAVE_SECRET_HASH='MY_POTTY_PATTY'
```

### 2. Setup Routes

```php
Route::post('/receivepayment', 'RaveController@webhook')->name('webhook');
```

### 3. Grant CSRF Access to Rave Webhook
Go to `app/Http/Middleware/VerifyCsrfToken.php` and add your webhook url to the `$except` array

```php
protected $except = [
    'rave/callback',
    'rave/receivepayment',
];
```

### 4. Setup your Controller
> Setup your controller to handle the routes. I created the `RaveController`. Use the `Rave`
facade. 

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use the Rave Facade
use Rave;

class RaveController extends Controller
{

  /**
   * Receives Rave webhook
   * @return void
   */
  public function webhook()
  {
    //This receives the webhook
    $data = Rave::receiveWebhook();
    Log::info(json_encode($data, true));
  }

}

```

## Best practices

If your webhook script performs complex logic, or makes network calls, it's possible that the script would time out before rave sees its complete execution. For that reason, you might want to have your webhook endpoint immediately acknowledge receipt by returning a 2xx HTTP status code, and then perform the rest of its duties.

Webhook endpoints might occasionally receive the same event more than once. We advise you to guard against duplicated event receipts by making your event processing [idempotent](https://en.wikipedia.org/wiki/Idempotence). One way of doing this is logging the events you've processed, and then checking if the status has changed before processing the identical event. Additionally, we recommend [verifying webhook signatures](/verify-payment.html) to confirm that received events are being sent from rave.

