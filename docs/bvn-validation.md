# BVN Validation (KYC)

BVN Validation is only available for Nigerian customers. It allows you verify BVN supplied by a customer and can also be used for customer KYC methods such as; validating date of birth supplied by the customer, validating the mobile number, first name & last name etc.

::: tip
 BVN API calls cost N50 per call. To use this service, you would need to fund your rave balance, by navigating to transfers on the dashboard and using the top up balance option. To top up use the access bank account payment option and use this test account 0690000031.
:::

## Pre-requisites for using the BVN validation service.

1. Sign-up for a test account [here](https://ravesandbox.flutterwave.com/), and for a live account [here](https://rave.flutterwave.com/) .

2. Retrieve your secret key to make authenticated calls to the BVN API.

## Steps

1. Collect BVN from customer

2. create a method to pass the bvn

### Controller

```php
public function validateBVN()
  {
    $bvn = "12345678901";

    $data = Rave::validateBVN($bvn);

    dd($data);

    // {
    //     "status": "success",
    //     "message": "BVN-DETAILS",
    //     "data": {
    //         "bvn": "12345678901",
    //         "first_name": "Wendy",
    //         "middle_name": "Chucky",
    //         "last_name": "Rhoades",
    //         "date_of_birth": "01-01-1905",
    //         "phone_number": "08012345678",
    //         "registration_date": "01-01-1921",
    //         "enrollment_bank": "044",
    //         "enrollment_branch": "Idejo"
    //     }
    // }
  }
```