<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\ApiResource;

interface RetrieveResourceActionInterface extends ResourceActionInterface
{
    public function retrieveApiResource(RetrieveInterface $request): ApiResource;

    public function supportAlso(RetrieveInterface $request): bool;
}
