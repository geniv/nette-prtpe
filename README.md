Prtpe
======
PRTPE payment gateway

API: https://docs.prtpe.com/

Account: https://4cash.eu/live/

Installation
------------

```sh
$ composer require geniv/nette-prtpe
```
or
```json
"geniv/nette-prtpe": ">=1.0.0"
```

require:
```json
"php": ">=5.6.0",
"curl/curl": "^1.6"
```

Include in application
----------------------
neon configure:
```neon
# prtpe
prtpe:
    entityId: 'xxx'
    password: 'yyy'
    userId: 'zzz'
```

neon configure extension:
```neon
extensions:
    prtpe: Prtpe\Bridges\Nette\Extension
```

presenters:
```php
use Prtpe\Prtpe;
/** @var Prtpe @inject */
public $prtpe;

// init settings prtple payment gate
$prtpe->setTest(true|false) : void

// is test mode?
$prtpe->isTestMode() : bool
```

**API Reference and test data: https://docs.prtpe.com/reference/parameters**

COPY&PAY && Server-to-Server
```php
// set part custom descriptor
$prtpe->setDescriptor($text = 'vs: XXXYYY') : Prtpe

// manual enable create registration token
$prtpe->setStorePayment($state = true) : Prtpe

// add registration code for select storage payment
$prtpe->addRegistration($registrationId = '##id##') : Prtpe


// payment is success or not
$pay->isSuccess() : bool

// result prtpe status code (https://docs.prtpe.com/reference/resultCodes)
$pay->getResultCode(): string

// checkout id or payment id
$pay->getId() : strign

// registration id registred payment
$pay->getRegistrationId() : string

// array from payment gateway
$pay->getResult() : array

// value from array payment gateway
$pay->getResult('amount') : string
```

COPY&PAY
--------
```php
// send checkout
$checkout = $prtpe->checkout($price, 'VISA', $currency) : Response

// get inline script
$prtpe->getPaymentWidgetsScript($checkoutId) : string

// get url form
$prtpe->getPaymentWidgetsForm($shopperResultUrl = $this->link('success'), $brands = ['VISA', 'MASTER']) : string

// get status checkout
$status = $prtpe->getStatusCheckout($resourcePath) : Response
```

Customization: https://docs.prtpe.com/tutorials/integration-guide/customisation

Advanced Options: https://docs.prtpe.com/tutorials/integration-guide/advanced-options

Server-to-Server
----------------
```php
// new credit card
$card = new Card($number, $holder, $expiryMonth, $expiryYear, $cvv) : Card

// send payment
$pay = $prtpe->payment($card, $price, 'VISA', $currency) : Response

// get status payment
$pay = $prtpe->getStatusPayment($checkoutId = '##id##') : Response

// store card
$pay = $prtpe->storePaymentData($card) : Response
```

Recurent payment (COPY&PAY + Server-to-Server)
----------------
```php
// recurent payment, send payment
$pay = $prtpe->sendRepeatedPayment($idRegistration, $price, 'VISA', $currency) : Response

// recurent payment, delete payment
$pay = $prtpe->deleteStorePaymentData($idRegistration) : Response
```


usage:
```php
// checkout

try {
    $prtpe = $this->prtpe->setDescriptor('vs:123'));

    $checkout = $prtpe->checkout(10);
    if ($checkout->isSuccess()) {
        $paymentWidgetsScript = $prtpe->getPaymentWidgetsScript($checkout->getId());
        $paymentWidgetsForm = $prtpe->getPaymentWidgetsForm($this->link('//success'));
    }
} catch (Exception $e) {
    $this->flashMessage($e->getMessage(), 'danger');
}
```

```php
try {
    $statusCheckout = $this->prtpe->getStatusCheckout($resourcePath);
    if ($statusCheckout->isSuccess()) {
        // save $statusCheckout
    }
} catch (Exception $e) {
    $this->flashMessage($e->getMessage(), 'danger');
}
```

```php
$pay = $this->prtpe
    ->setDescriptor('vs:123')
    ->sendRepeatedPayment($idRegistration, 10);
```
