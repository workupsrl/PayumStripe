<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CapturePaymentIntent;
use Workup\PayumStripe\Request\Api\Resource\CustomCallInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\ApiResource;
use Stripe\PaymentIntent;

final class CapturePaymentIntentAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = PaymentIntent::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof CapturePaymentIntent;
    }

    /**
     * @param CustomCallInterface&RetrieveInterface $request
     */
    public function retrieveApiResource(RetrieveInterface $request): ApiResource
    {
        /** @var PaymentIntent $apiResource */
        $apiResource = parent::retrieveApiResource($request);

        return $this->capturePaymentIntent($apiResource, $request);
    }

    public function capturePaymentIntent(PaymentIntent $apiResource, CustomCallInterface $request): PaymentIntent
    {
        return $apiResource->capture(
            $request->getCustomCallParameters(),
            $request->getCustomCallOptions()
        );
    }
}
