<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\UpdateCoupon;
use Workup\PayumStripe\Request\Api\Resource\UpdateInterface;
use Stripe\Coupon;

final class UpdateCouponAction extends AbstractUpdateAction
{
    protected $apiResourceClass = Coupon::class;

    public function supportAlso(UpdateInterface $request): bool
    {
        return $request instanceof UpdateCoupon;
    }
}
