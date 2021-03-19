# Sub Account

## Introduction
This page shows you how to create a subaccount on rave.


#### eg

```html
<input type="hidden" name="subaccount" value="362" />
```

## Form values

| name        | required           | description  |
| ------------- |:-------------:| -----:|
| account_bank | true | This is the sub-accounts bank ISO code, use the List of Banks for Transfer endpoint to retrieve a list of bank codes.
|account_number | true | This is the customer's account number.
| business_name | true | This is the sub-account business name.
| business_email | false | This is the sub-account business email.
| business_contact | false | This is the contact person for the sub-account e.g. Richard Hendrix
| business_contact_mobile | false | Business contact number.
| business_mobile | true | Primary business contact number.
| meta | false | This allows you pass extra/custom information about the sub-account. it can be passed this way: "meta": [{"metaname": "MarketplaceID", "metavalue": "ggs-920900"}]
| seckey | true | This is the merchants secret key.
| split_type | true | This can be set as percentage or flat when set as percentage it means you want to take a percentage fee on all transactions, and vice versa for flat this means you want to take a flat fee on every transaction.
| split_value | true | This can be a percentage value or flat value depending on what was set on split_type.


## Sample implementation

```html
<form method="POST" action="/subaccount">
    {!! Form::open(['action' => 'RaveController@create', 'method' => 'POST']) !!} 
    {{-- {{ csrf_field() }}
    <input type="hidden" name="account_bank" value="044" />
    <input type="hidden" name="account_number" value="0690000035" />
    <input type="hidden" name="business_name" value=" Store" />
    <input type="hidden" name="business_email" value="example@gmail.com" />
    <input type="hidden" name="business_contact" value="Somebody" />
    <input type="hidden" name="business_contact_mobile" value="080XXXXXXXX" />
    <input type="hidden" name="business_mobile" value="080XXXXXXXX" />
    <input type="hidden" name="meta" value="[{"metaname": "MarketplaceID", "metavalue": "ggs-920900"}]"/> 
    <input type="submit" value="create" />
</form>
```


## Sub Account methods

These methods creates sub account which can be used in the form, list all accounts, fetches sub accounts

### 1. Rave::createSubAccount()

> This creates a sub account

return Object

### 2. Rave::ListSubAccount()

> This lists all sub accounts

return Object


### 3. Rave::fetchSubAccount($id, $q)

- $id - This is the id of the sub account
- $q - This is the name of the sub account

This fetches all sub accounts

returns Object

<br><br><br>


# 1. Create a sub account

#### Route

```php
Route::post('/subaccount/create', 'RaveController@createSubAccount')->name('createSubAccount');
```

#### Controller

```php
  public function createSubAccount()
  {

    $data = Rave::createSubAccount();

    dd($data);
  }
  ```

# 2. List all sub accounts

### Route

```php
Route::get('/subaccount/list', 'RaveController@ListSubAccount')->name('listSubAccount');
```

### Controller

```php
public function ListSubAccount()
  {
    $data = Rave::ListSubAccount();

    dd($data);
  }
  ```

  # 3. Fetch all sub accounts

  ### Route

  ```php 
Route::post('/subaccount/fetch', 'RaveController@fetchSubAccount')->name('fetchSubAccount');
```

### Contoller

```php
public function fetchSubAccount($id)
  {

    $data = Rave::fetchSubAccount($id);

    dd($data);
  }
  ```





