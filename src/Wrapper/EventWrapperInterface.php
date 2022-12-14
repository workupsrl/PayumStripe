<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Wrapper;

use Stripe\Event;

interface EventWrapperInterface
{
    public function getEvent(): Event;

    public function getUsedWebhookSecretKey(): string;
}
