Prtpe
======
PRTPE payment gateway

API: https://docs.prtpe.com/

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

COPY&PAY.
---------
```php
// get detail checkout
$pay = $prtpe->getStatusCheckout($resourcePath) : Response

// send checkout
$prtpe->checkout($price, 'VISA', $currency) : Response;


$prtpe->getPaymentWidgetsScript($checkoutId) : string;

$prtpe->getPaymentWidgetsForm($shopperResultUrl, ['VISA', 'MASTER']) : string;
```

Customization: https://docs.prtpe.com/tutorials/integration-guide/customisation

Advanced Options: https://docs.prtpe.com/tutorials/integration-guide/advanced-options

Server-to-Server.
-----------------
```php
// get detail payment
$pay = $prtpe->getStatusPayment($checkoutId) : Response

// new credit card
$card = new Card($number, $holder, $expiryMonth, $expiryYear, $cvv) : Card

// set part custom descriptor
$prtpe = $prtpe->setDescriptor('vs: XXXYYY') : Prtpe

// send payment
$pay = $prtpe->payment($card, $price, 'VISA', $currency) : Response

// recurent payment

// recurect payment, first store card, in result response is ID registration ID
$pay = $prtpe->storePaymentData($card) : Response

// recurent payment, delete payment
$pay = $prtpe->deleteStorePaymentData($idRegistration) : Response;

// recurent payment, send payment
$pay = $prtpe->sendRepeatedPayment($idRegistration, $price,'VISA', $currency) : Response


$pay : Response                     // instance of Response
$pay->isSuccess() : bool            // payment is success
$pay->getId() : int                 // checkout id or registration id payment
$pay->getResult() : array           // array from payment gateway
$pay->getResult('amount') : string  // value from array payment gateway
```
