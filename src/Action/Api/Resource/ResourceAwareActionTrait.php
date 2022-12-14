<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

trait ResourceAwareActionTrait
{
    /** @var string */
    protected $apiResourceClass = '';

    public function getApiResourceClass(): string
    {
        return $this->apiResourceClass;
    }

    public function setApiResourceClass(string $apiResourceClass): void
    {
        $this->apiResourceClass = $apiResourceClass;
    }
}
