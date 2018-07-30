# Payment Plans

> These methods creates payment plans which can be used in the form, list all plans, fetches a single plan, cancel a plan and edits a plan.

## Form Values

| name        | required           | description  |
| ------------- |:-------------:| -----:|
| amount      |  true | This is the amount for the plan.
| name      |  true | This is what would appear on the subscription reminder email
| interval      |  true | This are the charge intervals possible values are: daily; weekly; monthly; yearly; quarterly; bi-anually; every 2 days; every 90 days; every 5 weeks; every 12 months; every 6 years; every x y (where x is a number and y is the period e.g. every 5 months) e.g. interval: "daily"
| duration      |  false | This is the frequency, it is numeric, e.g. if set to 5 and intervals is set to monthly you would be charged 5 months, and then the subscription stops.


## Methods

1. Rave::createPaymentPlan()

This creates a payment plan

> ### Sample

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


returns Object


2. Rave::editPaymentPlan($id)

- $id - This is the id of the payment plan

This edits a payment plan

> ### Sample

#### Form

```html
<form method="POST" action="{{ route('editpaymentplan') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="text" name="name" placeholder="Plan Name" />
    <input type="text" name="status" placeholder="Status" />
    <input type="submit" value="Edit"  />
</form>
```

#### Route

```php
Route::post('/paymentplans/:id/edit', 'RaveController@editPaymentPlan')->name('editpaymentplan');
```


#### Controller

```php
public function editPaymentPlan($id)
  {
    $data = Rave::editPaymentPlan($id);

    dd($data);
  }
```

returns Object


3. Rave::cancelPaymentPlan($id)

- $id - This is the id of the payment plan

This cancels a payment plan

> ### Sample

#### Route

```php
Route::post('/paymentplans/:id/cancel', 'RaveController@cancelPaymentPlan')->name('cancelPaymentPlan');
```


#### Controller

```php
public function cancelPaymentPlan($id)
  {
    $data = Rave::cancelPaymentPlan();

    dd($data);
  }
```


returns Object


4. Rave::listPaymentPlans()

This lists all payment plans

> ### Sample

#### Route

```php
Route::get('/paymentplans', 'RaveController@getPaymentPlans')->name('getPaymentPlans');
```


#### Controller

```php
  public function getPaymentPlans()
  {
    $data = Rave::listPaymentPlans();

    dd($data);
  }
```

returns Object


5. Rave::fetchPaymentPlan($id='', $q='')

- $id - This is the id of the payment plan
- $q - This is the name of the payment plan

This fetches all payment plans

> ### Sample

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

returns Object

# Subscriptions

> These provide methods to list all subscriptions, fetching a subscription, cancelling a subscription and activating a subscription.


## Methods

1. Rave::listSubscriptions()
   
This lists all subscriptions

2. Rave::fetchSubscription($id = '', $email = '')
- $id - This is the id of the subscription
- $q - This is the email of the user
   
This searches a subscription

3. Rave::cancelSubscription($id)
- $id - This is the id of the subscription
  
This cancels a subscription

3. Rave::activateSubscription($id)
- $id - This is the id of the subscription
  
This activates a subscription