<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreateSession;
use Stripe\Checkout\Session;

final class CreateSessionAction extends AbstractCreateAction
{
    protected $apiResourceClass = Session::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateSession;
    }
}
