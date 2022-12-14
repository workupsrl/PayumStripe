<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Stripe\ApiResource;

interface CreateResourceActionInterface extends ResourceActionInterface
{
    public function createApiResource(CreateInterface $request): ApiResource;

    public function supportAlso(CreateInterface $request): bool;
}
