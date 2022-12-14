<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\Api\Resource;

use Payum\Core\Model\ModelAggregateInterface;
use Payum\Core\Model\ModelAwareInterface;
use Payum\Core\Security\TokenAggregateInterface;

interface CreateInterface extends ResourceAwareInterface, OptionsAwareInterface, ModelAwareInterface, ModelAggregateInterface, TokenAggregateInterface
{
    public function getParameters(): array;

    public function setParameters(array $parameters): void;
}
