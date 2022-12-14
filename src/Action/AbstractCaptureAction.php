<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action;

use ArrayAccess;
use ArrayObject as BaseArrayObject;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Generic;
use Payum\Core\Request\Sync;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Stripe\ApiResource;

abstract class AbstractCaptureAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use EmbeddableTokenTrait;

    /**
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false === $model->offsetExists('id')) {
            // 0. Create another token to allow payment webhooks to use `Notify`
            $this->embedNotifyTokenHash($model, $request);

            // 1. Create a new `ApiResource` object
            //    - [stripe_checkout_session] : A `Session` object
            //    - [stripe_js] : A `PaymentIntent` object
            $apiResource = $this->createApiResource($model, $request);
            $model->exchangeArray($apiResource->toArray());

            // 2. Retrieve the `PaymentIntent`|`SetupIntent`|`Session` object and update it from Stripe API
            //    - [stripe_checkout_session] : A `Session` object synced to one of those objects :
            //      - mode = "payment" => `PaymentIntent`
            //      - mode = "setup" => `SetupIntent`
            //      - mode = "subscription" => `Session` (because there is no SubscriptionIntent)
            //    - [stripe_js] : A `PaymentIntent` object refreshed
            $this->gateway->execute(new Sync($model));

            // 3. Render a template or make a redirection :
            //    - [stripe_checkout_session] : Redirect to the Stripe portal
            //    - [stripe_js] : Render a template
            $this->render($apiResource, $request);
        } else {
            $this->processNotNew($model, $request);
        }
    }

    protected function processNotNew(BaseArrayObject $model, Generic $request): void
    {
        $this->gateway->execute(new Sync($model));
    }

    abstract protected function createApiResource(BaseArrayObject $model, Generic $request): ApiResource;

    abstract protected function render(ApiResource $captureResource, Generic $request): void;

    public function supports($request): bool
    {
        if (false === $request instanceof Capture) {
            return false;
        }

        return $request->getModel() instanceof ArrayAccess;
    }
}
