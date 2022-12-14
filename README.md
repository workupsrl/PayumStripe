## Payum Stripe gateways

This library is design to add gateways to Payum to support Stripe checkout session (with SCA support)
and Stripe JS using Stripe JS Elements.

Apart from the gateways you can use this library to make calls to the Stripe API directly
using `Request` classes : [(All|Create|Delete|Retrieve|Update)*.php](./src/Request/Api/Resource)
which are using the related actions : [(All|Create|Delete|Retrieve|Update)*Action.php](./src/Action/Api/Resource).
You can also build your own `Request/Action` classes to fit your need.

## Installation

Install using Composer :

```bash
composer require workup/payum-stripe
```

Choose one of [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation),
the most used is [php-http/guzzle6-adapter](https://packagist.org/packages/php-http/guzzle6-adapter)

```bash
composer require  php-http/guzzle6-adapter
```

## Gateways configuration

 - [Stripe Checkout Session](docs/stripe-checkout-session/README.md)

   Support :
   - ["One-time payments"](https://stripe.com/docs/payments/accept-a-payment)
   - ["Place a hold on a card" (Authorize)](https://stripe.com/docs/payments/capture-later)
   - ["Subscription"](https://stripe.com/docs/payments/checkout/subscriptions/starting)
   - ["Set up future payments"](https://stripe.com/docs/payments/save-and-reuse#checkout)

   > Canceling a `PaymentIntent` is also available using `Payum\Core\Request\Cancel`.    
   > Refunding a `PaymentIntent` is also available using `Payum\Core\Request\Refund`.    

 - [Stripe JS](docs/stripe-js/README.md)

   Support :
   - ["Accept a payment"](https://stripe.com/docs/payments/accept-a-payment?integration=elements)
   - ["Place a hold on a card" (Authorize)](https://stripe.com/docs/payments/capture-later)

   > Canceling a `PaymentIntent` is also available using `Payum\Core\Request\Cancel`.    
   > Refunding a `PaymentIntent` is also available using `Payum\Core\Request\Refund`.
