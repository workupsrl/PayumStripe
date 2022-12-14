<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreatePlan;
use Stripe\Plan;

final class CreatePlanAction extends AbstractCreateAction
{
    protected $apiResourceClass = Plan::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreatePlan;
    }
}
