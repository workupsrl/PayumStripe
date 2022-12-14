<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\UpdateInterface;
use Stripe\ApiResource;

interface UpdateResourceActionInterface extends ResourceActionInterface
{
    public function updateApiResource(UpdateInterface $request): ApiResource;

    public function supportAlso(UpdateInterface $request): bool;
}
