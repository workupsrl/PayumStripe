<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\DeleteInterface;
use Stripe\ApiResource;

interface DeleteResourceActionInterface extends ResourceActionInterface
{
    public function deleteApiResource(DeleteInterface $request): ApiResource;

    public function supportAlso(DeleteInterface $request): bool;
}
