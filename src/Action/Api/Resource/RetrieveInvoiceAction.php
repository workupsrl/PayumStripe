<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInvoice;
use Stripe\Invoice;

final class RetrieveInvoiceAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Invoice::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveInvoice;
    }
}
