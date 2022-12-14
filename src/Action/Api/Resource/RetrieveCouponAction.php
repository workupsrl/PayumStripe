<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveCoupon;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\Coupon;

final class RetrieveCouponAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Coupon::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveCoupon;
    }
}
