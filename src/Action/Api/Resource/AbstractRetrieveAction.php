<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Action\Api\StripeApiAwareTrait;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Stripe\ApiOperations\Retrieve;
use Stripe\ApiResource;
use Stripe\Stripe;

abstract class AbstractRetrieveAction implements RetrieveResourceActionInterface
{
    use StripeApiAwareTrait;
    use ResourceAwareActionTrait;

    /**
     * @param RetrieveInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $apiResource = $this->retrieveApiResource($request);

        $request->setApiResource($apiResource);
    }

    public function retrieveApiResource(RetrieveInterface $request): ApiResource
    {
        $apiResourceClass = $this->getApiResourceClass();
        if (false === method_exists($apiResourceClass, 'retrieve')) {
            throw new LogicException(sprintf('This class "%s" is not an instance of "%s" !', $apiResourceClass, Retrieve::class));
        }

        Stripe::setApiKey($this->api->getSecretKey());

        /* @see Retrieve::retrieve() */
        return $apiResourceClass::retrieve(
            $request->getId(),
            $request->getOptions()
        );
    }

    public function supports($request): bool
    {
        return
            $request instanceof RetrieveInterface &&
            $this->supportAlso($request)
        ;
    }
}
