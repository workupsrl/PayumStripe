<?php

declare(strict_types=1);

namespace Workup\PayumStripe;

use Workup\PayumStripe\Action\Api\ConstructEventAction;
use Workup\PayumStripe\Action\Api\ResolveWebhookEventAction;
use Workup\PayumStripe\Action\Api\Resource\AllCouponAction;
use Workup\PayumStripe\Action\Api\Resource\AllCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\AllInvoiceAction;
use Workup\PayumStripe\Action\Api\Resource\AllSessionAction;
use Workup\PayumStripe\Action\Api\Resource\AllTaxRateAction;
use Workup\PayumStripe\Action\Api\Resource\CancelPaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CancelSetupIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CancelSubscriptionAction;
use Workup\PayumStripe\Action\Api\Resource\CapturePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CreateCouponAction;
use Workup\PayumStripe\Action\Api\Resource\CreateCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePaymentMethodAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePlanAction;
use Workup\PayumStripe\Action\Api\Resource\CreateRefundAction;
use Workup\PayumStripe\Action\Api\Resource\CreateSessionAction;
use Workup\PayumStripe\Action\Api\Resource\CreateSetupIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CreateSubscriptionAction;
use Workup\PayumStripe\Action\Api\Resource\CreateTaxRateAction;
use Workup\PayumStripe\Action\Api\Resource\DeleteCouponAction;
use Workup\PayumStripe\Action\Api\Resource\DeletePlanAction;
use Workup\PayumStripe\Action\Api\Resource\ExpireSessionAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveChargeAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveCouponAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveInvoiceAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePaymentMethodAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePlanAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveProductAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSessionAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSetupIntentAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSubscriptionAction;
use Workup\PayumStripe\Action\Api\Resource\UpdateCouponAction;
use Workup\PayumStripe\Action\Api\Resource\UpdatePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\UpdateSubscriptionAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\AuthorizedPaymentIntentCanceledAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\AuthorizedPaymentIntentManuallyCanceledAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\AuthorizedPaymentIntentSucceededAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\PaymentIntentCanceledAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\PaymentIntentCanceledFromAuthorizeAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\PaymentIntentSucceededAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\PaymentIntentSucceededFromAuthorizeAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\SetupIntentCanceledAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\SetupIntentSucceededAction;
use Workup\PayumStripe\Action\Api\WebhookEvent\StripeWebhookTestAction;
use Workup\PayumStripe\Action\CancelAction;
use Workup\PayumStripe\Action\CaptureAuthorizedAction;
use Workup\PayumStripe\Action\NotifyAction;
use Workup\PayumStripe\Action\RefundAction;
use Workup\PayumStripe\Action\StatusAction;
use Workup\PayumStripe\Action\StatusPaymentIntentAction;
use Workup\PayumStripe\Action\StatusRefundAction;
use Workup\PayumStripe\Action\StatusSessionAction;
use Workup\PayumStripe\Action\StatusSetupIntentAction;
use Workup\PayumStripe\Action\SyncAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

abstract class AbstractStripeGatewayFactory extends GatewayFactory
{
    protected function getDefaultActions(): array
    {
        return [
            'payum.action.cancel.payment_intent.manual' => new CancelAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.capture_authorized' => new CaptureAuthorizedAction(),
            'payum.action.notify_unsafe' => new NotifyAction(),
            'payum.action.session_status' => new StatusSessionAction(),
            'payum.action.payment_intent_status' => new StatusPaymentIntentAction(),
            'payum.action.setup_intent_status' => new StatusSetupIntentAction(),
            'payum.action.refund_status' => new StatusRefundAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),
        ];
    }

    protected function getDefaultApiResources(): array
    {
        return [
            'payum.action.all_customer' => new AllCustomerAction(),
            'payum.action.all_invoice' => new AllInvoiceAction(),
            'payum.action.all_coupon' => new AllCouponAction(),
            'payum.action.all_tax_rate' => new AllTaxRateAction(),
            'payum.action.all_session' => new AllSessionAction(),
            'payum.action.cancel_payment_intent' => new CancelPaymentIntentAction(),
            'payum.action.cancel_setup_intent' => new CancelSetupIntentAction(),
            'payum.action.cancel_subscription' => new CancelSubscriptionAction(),
            'payum.action.capture_payment_intent' => new CapturePaymentIntentAction(),
            'payum.action.create_coupon' => new CreateCouponAction(),
            'payum.action.create_customer' => new CreateCustomerAction(),
            'payum.action.create_payment_intent' => new CreatePaymentIntentAction(),
            'payum.action.create_payment_method' => new CreatePaymentMethodAction(),
            'payum.action.create_plan' => new CreatePlanAction(),
            'payum.action.create_refund' => new CreateRefundAction(),
            'payum.action.create_setup_intent' => new CreateSetupIntentAction(),
            'payum.action.create_session' => new CreateSessionAction(),
            'payum.action.create_subscription' => new CreateSubscriptionAction(),
            'payum.action.create_tax_rate' => new CreateTaxRateAction(),
            'payum.action.delete_coupon' => new DeleteCouponAction(),
            'payum.action.delete_plan' => new DeletePlanAction(),
            'payum.action.expire_session' => new ExpireSessionAction(),
            'payum.action.retrieve_charge' => new RetrieveChargeAction(),
            'payum.action.retrieve_coupon' => new RetrieveCouponAction(),
            'payum.action.retrieve_customer' => new RetrieveCustomerAction(),
            'payum.action.retrieve_invoice' => new RetrieveInvoiceAction(),
            'payum.action.retrieve_payment_intent' => new RetrievePaymentIntentAction(),
            'payum.action.retrieve_payment_method' => new RetrievePaymentMethodAction(),
            'payum.action.retrieve_plan' => new RetrievePlanAction(),
            'payum.action.retrieve_product' => new RetrieveProductAction(),
            'payum.action.retrieve_session' => new RetrieveSessionAction(),
            'payum.action.retrieve_setup_intent' => new RetrieveSetupIntentAction(),
            'payum.action.retrieve_subscription' => new RetrieveSubscriptionAction(),
            'payum.action.update_coupon' => new UpdateCouponAction(),
            'payum.action.update_payment_intent' => new UpdatePaymentIntentAction(),
            'payum.action.update_subscription' => new UpdateSubscriptionAction(),
        ];
    }

    protected function getDefaultWebhooks(): array
    {
        return [
            'payum.action.resolve_webhook_event' => new ResolveWebhookEventAction(),
            'payum.action.construct_event' => new ConstructEventAction(),
        ];
    }

    protected function getDefaultWebhookEventResolvers(): array
    {
        return [
            'payum.action.stripe_webhook_test' => new StripeWebhookTestAction(),
            'payum.action.payment_intent_succeeded' => new PaymentIntentSucceededAction(),
            'payum.action.payment_intent_canceled' => new PaymentIntentCanceledAction(),
            'payum.action.payment_intent_succeeded_from_authorize' => new PaymentIntentSucceededFromAuthorizeAction(),
            'payum.action.payment_intent_canceled_from_authorize' => new PaymentIntentCanceledFromAuthorizeAction(),
            'payum.action.authorized_payment_intent_succeeded' => new AuthorizedPaymentIntentSucceededAction(),
            'payum.action.authorized_payment_intent_canceled' => new AuthorizedPaymentIntentCanceledAction(),
            'payum.action.authorized_payment_intent_manually_canceled' => new AuthorizedPaymentIntentManuallyCanceledAction(),
            'payum.action.setup_intent_succeeded' => new SetupIntentSucceededAction(),
            'payum.action.setup_intent_canceled' => new SetupIntentCanceledAction(),
        ];
    }

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults($this->getDefaultActions());
        $config->defaults($this->getDefaultApiResources());
        $config->defaults($this->getDefaultWebhooks());
        $config->defaults($this->getDefaultWebhookEventResolvers());

        if (false === $config->offsetExists('payum.api')) {
            $config->offsetSet('payum.default_options', $this->getStripeDefaultOptions());
            $config->defaults($config['payum.default_options']);
            $config->offsetSet('payum.required_options', $this->getStripeRequiredOptions());

            $config->offsetSet('payum.api', function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return $this->initApi($config);
            });
        }

        $config->offsetSet('payum.paths', array_replace([
            'FluxSEPayumStripe' => __DIR__ . '/Resources/views',
        ], $config['payum.paths'] ?: []));
    }

    abstract protected function initApi(ArrayObject $config): KeysAwareInterface;

    protected function getStripeDefaultOptions(): array
    {
        return [
            'publishable_key' => '',
            'secret_key' => '',
            'webhook_secret_keys' => [],
        ];
    }

    /**
     * @return string[]
     */
    protected function getStripeRequiredOptions(): array
    {
        return [
            'publishable_key',
            'secret_key',
            'webhook_secret_keys',
        ];
    }
}
