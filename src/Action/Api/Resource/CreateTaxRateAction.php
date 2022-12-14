<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreateTaxRate;
use Stripe\TaxRate;

final class CreateTaxRateAction extends AbstractCreateAction
{
    protected $apiResourceClass = TaxRate::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateTaxRate;
    }
}
