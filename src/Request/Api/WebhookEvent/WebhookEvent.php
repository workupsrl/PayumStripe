<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\Api\WebhookEvent;

use Workup\PayumStripe\Wrapper\EventWrapperInterface;
use Payum\Core\Request\Generic;

final class WebhookEvent extends Generic
{
    public function __construct(EventWrapperInterface $model)
    {
        parent::__construct($model);
    }

    public function setEventWrapper(EventWrapperInterface $eventWrapper): void
    {
        parent::setModel($eventWrapper);
    }

    public function getEventWrapper(): ?EventWrapperInterface
    {
        if ($this->getModel() instanceof EventWrapperInterface) {
            return $this->getModel();
        }

        return null;
    }
}
