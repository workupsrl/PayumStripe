<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Workup\PayumStripe\Request\Api\Resource\AllSession;
use Stripe\Checkout\Session;

final class AllSessionAction extends AbstractAllAction
{
    protected $apiResourceClass = Session::class;

    public function supportAlso(AllInterface $request): bool
    {
        return $request instanceof AllSession;
    }
}
