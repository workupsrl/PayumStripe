# Stripe Checkout Session : `subscription`

# Subscription handling

Payum don't have php `Model` `Interface`s to handle subscriptions, that's why subscriptions should be
managed by yourself. There is maybe a composer packages which fit your need,
but you will have to build the interface between your subscription `Model` class and `Payum`.

Usually you will have to build a `ConvertPaymentAction` like this one : [ConvertPaymentAction.php](https://github.com/workup/SyliusPayumStripePlugin/blob/master/src/Action/ConvertPaymentAction.php)
customizing the `supports` method to fit your need and provide the right `$details` array.

Example : https://stripe.com/docs/payments/checkout/subscriptions/starting#create-checkout-session (`$details` is the array given to create a `Session`)
