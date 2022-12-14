<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveSetupIntent;
use Stripe\SetupIntent;

final class RetrieveSetupIntentAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = SetupIntent::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveSetupIntent;
    }
}
