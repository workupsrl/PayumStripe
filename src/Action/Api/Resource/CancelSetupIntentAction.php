<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CancelSetupIntent;
use Workup\PayumStripe\Request\Api\Resource\CustomCallInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\ApiResource;
use Stripe\SetupIntent;

final class CancelSetupIntentAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = SetupIntent::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof CancelSetupIntent;
    }

    /**
     * @param CustomCallInterface&RetrieveInterface $request
     */
    public function retrieveApiResource(RetrieveInterface $request): ApiResource
    {
        /** @var SetupIntent $apiResource */
        $apiResource = parent::retrieveApiResource($request);

        return $this->cancelSetupIntent($apiResource, $request);
    }

    public function cancelSetupIntent(SetupIntent $apiResource, CustomCallInterface $request): SetupIntent
    {
        return $apiResource->cancel(
            $request->getCustomCallParameters(),
            $request->getCustomCallOptions()
        );
    }
}
