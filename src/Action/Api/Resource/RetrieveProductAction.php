<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveProduct;
use Stripe\Product;

final class RetrieveProductAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Product::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveProduct;
    }
}
