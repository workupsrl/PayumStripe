<?php

declare(strict_types=1);

namespace Workup\PayumStripe;

use Workup\PayumStripe\Action\StripeCheckoutSession\Api\RedirectToCheckoutAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\Api\WebhookEvent\CheckoutSessionAsyncPaymentFailedAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\Api\WebhookEvent\CheckoutSessionAsyncPaymentSucceededAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\Api\WebhookEvent\CheckoutSessionCompletedAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\AuthorizeAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\CancelAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\CaptureAction;
use Workup\PayumStripe\Action\StripeCheckoutSession\ConvertPaymentAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Workup\PayumStripe\Api\StripeCheckoutSessionApi;
use Workup\PayumStripe\Api\StripeCheckoutSessionApiInterface;
use Workup\PayumStripe\Extension\StripeCheckoutSession\CancelUrlCancelPaymentIntentExtension;
use Workup\PayumStripe\Extension\StripeCheckoutSession\CancelUrlCancelSetupIntentExtension;
use Workup\PayumStripe\Extension\StripeCheckoutSession\CancelUrlExpireSessionExtension;
use Payum\Core\Bridge\Spl\ArrayObject;

final class StripeCheckoutSessionGatewayFactory extends AbstractStripeGatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            // Factories
            'payum.factory_name' => 'stripe_checkout_session',
            'payum.factory_title' => 'Stripe Checkout Session',

            // Webhook event resolver
            'payum.action.checkout_session_completed' => new CheckoutSessionCompletedAction(),
            'payum.action.checkout_session_async_payment_failed' => new CheckoutSessionAsyncPaymentFailedAction(),
            'payum.action.checkout_session_async_payment_succeeded' => new CheckoutSessionAsyncPaymentSucceededAction(),

            // Actions
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.redirect_to_checkout' => new RedirectToCheckoutAction(),
            'payum.action.cancel.payment_intent.automatic' => new CancelAction(),

            // Extensions
            'payum.extension.after_capture_cancel_payment_intent' => new CancelUrlCancelPaymentIntentExtension(),
            'payum.extension.after_capture_cancel_setup_intent' => new CancelUrlCancelSetupIntentExtension(),
            'payum.extension.after_capture_expire_session' => new CancelUrlExpireSessionExtension(),
        ]);

        parent::populateConfig($config);
    }

    protected function getStripeDefaultOptions(): array
    {
        $defaultOptions = parent::getStripeDefaultOptions();
        $defaultOptions['payment_method_types'] = StripeCheckoutSessionApiInterface::DEFAULT_PAYMENT_METHOD_TYPES;

        return $defaultOptions;
    }

    protected function initApi(ArrayObject $config): KeysAwareInterface
    {
        return new StripeCheckoutSessionApi(
            $config['publishable_key'],
            $config['secret_key'],
            $config['webhook_secret_keys'],
            $config['payment_method_types']
        );
    }
}
