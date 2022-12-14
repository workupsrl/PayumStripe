<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\Api\Resource;

abstract class AbstractDelete extends AbstractRetrieve implements DeleteInterface
{
    use OptionsAwareTrait;
    use ResourceAwareTrait;

    public function __construct(string $id, array $retrieveOptions = [])
    {
        parent::__construct($id);
        $this->setOptions($retrieveOptions);
    }

    public function getId(): string
    {
        /** @var string $model */
        $model = $this->getModel();
        return $model;
    }

    public function setId(string $id): void
    {
        $this->setModel($id);
    }
}
