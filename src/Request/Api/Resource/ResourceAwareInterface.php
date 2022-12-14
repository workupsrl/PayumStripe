<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\Api\Resource;

use Stripe\ApiResource;

interface ResourceAwareInterface
{
    public function getApiResource(): ApiResource;

    public function setApiResource(ApiResource $apiResource): void;
}
