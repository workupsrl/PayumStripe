<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CancelPaymentIntent;
use Workup\PayumStripe\Request\Api\Resource\CustomCallInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\ApiResource;
use Stripe\PaymentIntent;

final class CancelPaymentIntentAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = PaymentIntent::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof CancelPaymentIntent;
    }

    /**
     * @param CustomCallInterface&RetrieveInterface $request
     */
    public function retrieveApiResource(RetrieveInterface $request): ApiResource
    {
        /** @var PaymentIntent $apiResource */
        $apiResource = parent::retrieveApiResource($request);

        return $this->cancelPaymentIntent($apiResource, $request);
    }

    public function cancelPaymentIntent(PaymentIntent $apiResource, CustomCallInterface $request): PaymentIntent
    {
        return $apiResource->cancel(
            $request->getCustomCallParameters(),
            $request->getCustomCallOptions()
        );
    }
}
