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
/** @var Prtpe @inject */
public $prtpe;

// init settings prtple payment gate
$this->prtpe->setTest(true|false);  // : void

// is test mode?
$this->prtpe->isTestMode(); // : bool

// get detail payment
$this->prtpe->getStatus($checkoutId); // : Response

// new credit card
$card = new Card($number, $holder, $expiryMonth, $expiryYear, $cvv);    // : Card

// set part custom descriptor
$prtpe = $this->prtpe->setDescriptor('vs: XXXYYY'); // : Prtpe

// send payment
$pay = $prtpe->payment($card, $price, 'VISA', $currency); // : Response

// recurect payment, first store card, in result response is ID registration ID
$this->prtpe->storePaymentData($card); // : Response

// recurent payment, delete payment
$prtpe->deleteStorePaymentData($idRegistration); // : Response;

// recurent payment, send payment
$prtpe->sendRepeatedPayment($idRegistration, $price,'VISA', $currency); // : Response


$pay // instance of Response
$pay->isSuccess()   // payment is success
$pay->getId()   // checkout id or registration id payment
$pay->getResult()   // array from payment gateway
$pay->getResult('amount')   // value from array payment gateway
```
