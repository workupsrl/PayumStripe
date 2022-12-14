<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\Api\Resource;

interface OptionsAwareInterface
{
    public function setOptions(array $options): void;

    public function getOptions(): array;
}
