<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Workup\PayumStripe\Request\Api\Resource\AllInvoice;
use Stripe\Invoice;

final class AllInvoiceAction extends AbstractAllAction
{
    protected $apiResourceClass = Invoice::class;

    public function supportAlso(AllInterface $request): bool
    {
        return $request instanceof AllInvoice;
    }
}
