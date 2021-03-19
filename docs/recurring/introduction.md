# Recurring Payments

## Introduction
Rave helps you collect payments recurrently from your customers using a payment plan. Payment plans allow you create a subscription for your customers.

When you have created a payment plan you can subscribe a customer to it by simply passing the plan ID in your form value to charge the customers card.

::: warning
Payment Plans require [webhook url](/webhooks.html)
:::


#### eg

```html
<input type="hidden" name="paymentplan" value="362" />
```

## Form Values

| name        | required           | description  |
| ------------- |:-------------:| -----:|
| amount      |  true | This is the amount for the plan.
| name      |  true | This is what would appear on the subscription reminder email
| interval      |  true | This are the charge intervals possible values are: daily; weekly; monthly; yearly; quarterly; bi-anually; every 2 days; every 90 days; every 5 weeks; every 12 months; every 6 years; every x y (where x is a number and y is the period e.g. every 5 months) e.g. interval: "daily"
| duration      |  false | This is the frequency, it is numeric, e.g. if set to 5 and intervals is set to monthly you would be charged 5 months, and then the subscription stops.

## Sample implementation

```html
<form method="POST" action="{{ route('createpaymentplan') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="text" name="name" placeholder="Plan Name" />
    <input type="text" name="amount" placeholder="Amount" />
    <input type="text" name="interval" placeholder="Interval" />
    {{-- <input type="text" name="duration" placeholder="Duration" /> <!-- Uncomment if you want to add a duration --> --}}
    <input type="submit" value="Create"  />
</form>
```

#### Route

```php
Route::post('/paymentplans/create', 'RaveController@createPaymentPlan')->name('createpaymentplan');
```


#### Controller

```php
public function createPaymentPlan()
  {
    $data = Rave::createPaymentPlan();

    dd($data);

    // $data
    // {
    //   "id": 354,
    //   "name": "test plan",
    //   "amount": 500,
    //   "interval": "daily",
    //   "duration": 0,
    //   "status": "active",
    //   "currency": "NGN",
    //   "plan_token": "rpp_0621cdded016449f6267",
    //   "date_created": "2018-07-30T10:08:43.000Z"
    // }
  }
```

```html
<h3>Subscribe to Linda Ikeji TV - N500.00 per month</h3>
<form method="POST" action="{{ route('pay') }}">
    {{ csrf_field() }}
    <input type="hidden" name="amount" value="500" /> <!-- Replace the value with your transaction amount -->
    <input type="hidden" name="email" value="test@test.com" /> <!-- Replace the value with your customer email -->
    <input type="hidden" name="firstname" value="Oluwole" /> <!-- Replace the value with your customer firstname -->
    <input type="hidden" name="lastname" value="Adebiyi" /> <!-- Replace the value with your customer lastname -->
    <input type="hidden" name="phonenumber" value="090929992892" /> <!-- Replace the value with your customer phonenumber -->
    <input type="hidden" name="paymentplan" value="354" /> <!-- Replace the value with the payment plan id -->
    <input type="submit" value="Buy"  />
</form>
```

## Payment Plan Methods

These methods creates payment plans which can be used in the form, list all plans, fetches a single plan, cancel a plan and edits a plan.

### 1. Rave::createPaymentPlan()

> This creates a payment plan

returns Object


### 2. Rave::editPaymentPlan($id)

- $id - This is the id of the payment plan

> This edits a payment plan

returns Object


### 3. Rave::cancelPaymentPlan($id)

- $id - This is the id of the payment plan

> This cancels a payment plan

returns Object


### 4. Rave::listPaymentPlans()

> This lists all payment plans

returns Object


### 5. Rave::fetchPaymentPlan($id, $q)

- $id - This is the id of the payment plan
- $q - This is the name of the payment plan

This fetches all payment plans

returns Object

<br><br><br>

## Subscription Methods

 These provide methods to list all subscriptions, fetching a subscription, cancelling a subscription and activating a subscription.

### 1. Rave::listSubscriptions()
   
This lists all subscriptions

### 2. Rave::fetchSubscription($id, $email)
- $id - This is the id of the subscription
- $q - This is the email of the user
   
This searches a subscription

### 3. Rave::cancelSubscription($id)
- $id - This is the id of the subscription
  
This cancels a subscription

### 4. Rave::activateSubscription($id)
- $id - This is the id of the subscription
  
This activates a subscription


## Samples

### 1. Rave::createPaymentPlan

#### Form

```html
<form method="POST" action="{{ route('createpaymentplan') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="text" name="name" placeholder="Plan Name" />
    <input type="text" name="amount" placeholder="Amount" />
    <input type="text" name="interval" placeholder="Interval" />
    {{-- <input type="text" name="duration" placeholder="Duration" /> <!-- Uncomment if you want to add a duration --> --}}
    <input type="submit" value="Create"  />
</form>
```

#### Route

```php
Route::post('/paymentplans/create', 'RaveController@createPaymentPlan')->name('createpaymentplan');
```


#### Controller

```php
public function createPaymentPlan()
  {
    $data = Rave::createPaymentPlan();

    dd($data);
  }
```


### 2. Rave::fetchPaymentPlan($id, $q)

#### Route

```php
Route::get('/paymentplans', 'RaveController@fetchPaymentPlan')->name('fetchPaymentPlan');
```


#### Controller

```php
  public function fetchPaymentPlan($id, $q)
  {
    $data = Rave::fetchPaymentPlan($id, $q);

    dd($data);
  }
``` 