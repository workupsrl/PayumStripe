<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Workup\PayumStripe\Request\Api\Resource\AllTaxRate;
use Stripe\TaxRate;

final class AllTaxRateAction extends AbstractAllAction
{
    protected $apiResourceClass = TaxRate::class;

    public function supportAlso(AllInterface $request): bool
    {
        return $request instanceof AllTaxRate;
    }
}
