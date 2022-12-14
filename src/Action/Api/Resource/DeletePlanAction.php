<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\DeleteInterface;
use Workup\PayumStripe\Request\Api\Resource\DeletePlan;
use Stripe\Plan;

final class DeletePlanAction extends AbstractDeleteAction
{
    protected $apiResourceClass = Plan::class;

    public function supportAlso(DeleteInterface $request): bool
    {
        return $request instanceof DeletePlan;
    }
}
