<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveCharge;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\Charge;

final class RetrieveChargeAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Charge::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveCharge;
    }
}
