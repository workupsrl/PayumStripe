<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreateSetupIntent;
use Stripe\SetupIntent;

final class CreateSetupIntentAction extends AbstractCreateAction
{
    protected $apiResourceClass = SetupIntent::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateSetupIntent;
    }
}
