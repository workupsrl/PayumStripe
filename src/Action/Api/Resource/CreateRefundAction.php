<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreateRefund;
use Stripe\Refund;

final class CreateRefundAction extends AbstractCreateAction
{
    protected $apiResourceClass = Refund::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateRefund;
    }
}
