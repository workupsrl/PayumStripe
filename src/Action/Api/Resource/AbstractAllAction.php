<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Action\Api\StripeApiAwareTrait;
use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Stripe\ApiOperations\All;
use Stripe\Collection;
use Stripe\Stripe;

abstract class AbstractAllAction implements AllResourceActionInterface
{
    use StripeApiAwareTrait;
    use ResourceAwareActionTrait;

    /**
     * @param AllInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $apiResources = $this->allApiResource($request);

        $request->setApiResources($apiResources);
    }

    /**
     * @throws LogicException
     */
    public function allApiResource(AllInterface $request): Collection
    {
        $apiResourceClass = $this->getApiResourceClass();
        if (false === method_exists($apiResourceClass, 'all')) {
            throw new LogicException(sprintf('This class "%s" is not an instance of "%s" !', $apiResourceClass, All::class));
        }

        Stripe::setApiKey($this->api->getSecretKey());

        /* @see All::all() */
        return $apiResourceClass::all(
            $request->getParameters(),
            $request->getOptions()
        );
    }

    public function supports($request): bool
    {
        return
            $request instanceof AllInterface &&
            $this->supportAlso($request)
        ;
    }
}
