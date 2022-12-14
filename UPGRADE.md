# UPGRADE FROM `v2.0.5` TO `v2.0.6`

_[STRIPE_CHECKOUT_SESSION]_

**BC BREAK**: `Workup\PayumStripe\Action\StatusSubscriptionAction` has been removed because it doesn't reflect
if a subscription has been paid or not, the `Session` will be used instead to know if the payment is paid.

To complete this removal two Stripe webhook events need to be listen now (only for modes : "payment" and "subscription") :

 - `checkout.session.async_payment_failed`
 - `checkout.session.async_payment_succeeded`

NB: The mode "setup" still need `setup_intent.canceled` and `setup_intent.succeeded`

# UPGRADE FROM `v2.0.4` TO `v2.0.5`

**BC BREAK**: `@FluxSEPayumStripe/Action/stripeJsPaymentIntent.html.twig` is now using the new Payment Element instead
of the card element. See [this documentation](https://stripe.com/docs/payments/payment-element/migration) to migrate if
you made some JS customisations on this template.

# UPGRADE FROM `v2.0.3` TO `v2.0.4`

- twig template `@FluxSEPayumStripe/Action/redirectToCheckout.html.twig` has been removed, the library will now make 
  a php redirect from the `\Stripe\Checkout\Session::$url` provided by Stripe after creating a Checkout Session.

# UPGRADE FROM `v1.2.3` TO `v2.0.0`

**BC BREAK**: The class `Workup\PayumStripe\CaptureAction` has been moved and split into two classes :

- `Workup\PayumStripe\AbstractCaptureAction` the global Abstract class to handle all `CaptureAction`
- `Workup\PayumStripe\StripeCheckoutSession\CaptureAction` the dedicated `stripe_checkout_session` gateway capture action

**BC BREAK**: Those classes have been moved to a sub folder :

- `Workup\PayumStripe\CaptureAction` to `Workup\PayumStripe\StripeCheckoutSession\CaptureAction`
- `Workup\PayumStripe\Api\RedirectToCheckoutAction` to`Workup\PayumStripe\Action\StripeCheckoutSession\Api\RedirectToCheckoutAction`
- `Workup\PayumStripe\Request\Api\RedirectToCheckout` to`Workup\PayumStripe\Request\StripeCheckoutSession\Api\RedirectToCheckout`
- `Workup\PayumStripe\Api\WebhookEvent\CheckoutSessionCompletedAction` to`Workup\PayumStripe\Action\StripeCheckoutSession\Api\WebhookEvent\CheckoutSessionCompletedAction`

**BC BREAK**: Those classes have been moved or renamed to a sub folder :

- `Workup\PayumStripe\JsCaptureAction` to `Workup\PayumStripe\StripeJs\CaptureAction`
- `Workup\PayumStripe\JsConvertPaymentAction` to `Workup\PayumStripe\StripeJs\ConvertPaymentAction`
- `Workup\PayumStripe\Api\PayAction` to`Workup\PayumStripe\Action\StripeJs\Api\RenderStripeJsAction`
- `Workup\PayumStripe\Request\Api\Pay` to`Workup\PayumStripe\Request\StripeJs\Api\RenderStripeJs`

**BC BREAK**: Those config keys have been renamed :

- `payum.action.pay` to `payum.action.render_stripe_js.payment_intent`
- `payum.template.pay` to `payum.template.render_stripe_js.payment_intent`

**BC BREAK**: Those files keys have been renamed :

- `pay.html.twig` to `stripeJsPaymentIntent.html.twig`

# UPGRADE FROM `v1.2.0` TO `v1.2.1`

**BC BREAK**: Those interfaces have been renamed :

 - `RetrieveActionInterface` to `RetrieveResourceActionInterface`
 - `DeleteActionInterface` to `DeleteResourceActionInterface`
 
 # UPGRADE FROM `v1.1.2` TO `v1.2.0`

**BC BREAK**: The vendor name of this lib has change from `Prometee` to `FluxSE`
