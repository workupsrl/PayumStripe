<?php

declare(strict_types=1);

namespace Workup\PayumStripe;

use Workup\PayumStripe\Action\StripeJs\Api\RenderStripeJsAction;
use Workup\PayumStripe\Action\StripeJs\AuthorizeAction;
use Workup\PayumStripe\Action\StripeJs\CaptureAction;
use Workup\PayumStripe\Action\StripeJs\ConvertPaymentAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Workup\PayumStripe\Api\StripeCheckoutSessionApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use Stripe\PaymentIntent;

final class StripeJsGatewayFactory extends AbstractStripeGatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            // Factories
            'payum.factory_name' => 'stripe_js',
            'payum.factory_title' => 'Stripe JS',

            // Templates
            'payum.template.render_stripe_js.payment_intent' => '@FluxSEPayumStripe/Action/stripeJsPaymentIntent.html.twig',

            // Actions
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.render_stripe_js.payment_intent' => function (ArrayObject $config) {
                return new RenderStripeJsAction(
                    $config['payum.template.render_stripe_js.payment_intent'],
                    PaymentIntent::class
                );
            },
        ]);

        parent::populateConfig($config);
    }

    protected function initApi(ArrayObject $config): KeysAwareInterface
    {
        return new StripeCheckoutSessionApi(
            $config['publishable_key'],
            $config['secret_key'],
            $config['webhook_secret_keys']
        );
    }
}
