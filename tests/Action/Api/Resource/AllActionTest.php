<?php

declare(strict_types=1);

namespace Tests\Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Action\Api\Resource\AbstractAllAction;
use Workup\PayumStripe\Action\Api\Resource\AllCouponAction;
use Workup\PayumStripe\Action\Api\Resource\AllCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\AllInvoiceAction;
use Workup\PayumStripe\Action\Api\Resource\AllResourceActionInterface;
use Workup\PayumStripe\Action\Api\Resource\AllSessionAction;
use Workup\PayumStripe\Action\Api\Resource\AllTaxRateAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Workup\PayumStripe\Request\Api\Resource\AbstractAll;
use Workup\PayumStripe\Request\Api\Resource\AllCoupon;
use Workup\PayumStripe\Request\Api\Resource\AllCustomer;
use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Workup\PayumStripe\Request\Api\Resource\AllInvoice;
use Workup\PayumStripe\Request\Api\Resource\AllSession;
use Workup\PayumStripe\Request\Api\Resource\AllTaxRate;
use LogicException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;
use Stripe\ApiResource;
use Stripe\Checkout\Session;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Issuing\CardDetails;
use Stripe\TaxRate;
use Tests\Workup\PayumStripe\Action\Api\ApiAwareActionTestTrait;
use Tests\Workup\PayumStripe\Stripe\StripeApiTestHelper;

final class AllActionTest extends TestCase
{
    use StripeApiTestHelper;
    use ApiAwareActionTestTrait;

    /**
     * @dataProvider requestList
     */
    public function testShouldImplements(string $allActionClass): void
    {
        $action = new $allActionClass();

        $this->assertInstanceOf(ApiAwareInterface::class, $action);
        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertNotInstanceOf(GatewayInterface::class, $action);
        $this->assertInstanceOf(AllResourceActionInterface::class, $action);
    }

    /**
     * @dataProvider requestList
     *
     * @param class-string|ApiResource $allClass
     */
    public function testShouldAllAPaymentIntent(
        string $allActionClass,
        string $allRequestClass,
        string $allClass
    ): void {
        $apiMock = $this->createApiMock();

        /** @var AbstractAllAction $action */
        $action = new $allActionClass();
        $action->setApiClass(KeysAwareInterface::class);
        $action->setApi($apiMock);
        $this->assertEquals($allClass, $action->getApiResourceClass());

        /** @var AbstractAll $request */
        $request = new $allRequestClass();
        $this->assertTrue($action->supportAlso($request));

        $classUrl = $allClass::classUrl();
        $this->stubRequest(
            'get',
            $classUrl,
            [],
            null,
            false,
            [
                'object' => 'list',
                'data' => [
                    [
                        'object' => $allClass::OBJECT_NAME,
                        'id' => 'test_id_1',
                    ],
                    [
                        'object' => $allClass::OBJECT_NAME,
                        'id' => 'test_id_2',
                    ],
                ],
                'has_more' => false,
                'url' => $classUrl,
            ]
        );

        $supportAlso = $action->supportAlso($request);
        $this->assertTrue($supportAlso);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $action->execute($request);
        $this->assertContainsOnlyInstancesOf($allClass, $request->getApiResources());
    }

    public function testShouldThrowExceptionIfApiResourceClassIsNotCreatable(): void
    {
        $action = new class() extends AbstractAllAction {
            public function supportAlso(AllInterface $request): bool
            {
                return true;
            }
        };

        $action->setApiResourceClass(CardDetails::class);
        $this->assertEquals(CardDetails::class, $action->getApiResourceClass());

        $request = new class() extends AbstractAll {
        };

        $supportAlso = $action->supportAlso($request);
        $this->assertTrue($supportAlso);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $this->expectException(LogicException::class);
        $action->execute($request);
    }

    public function requestList(): array
    {
        return [
            [AllCouponAction::class, AllCoupon::class, Coupon::class],
            [AllCustomerAction::class, AllCustomer::class, Customer::class],
            [AllInvoiceAction::class, AllInvoice::class, Invoice::class],
            [AllTaxRateAction::class, AllTaxRate::class, TaxRate::class],
            [AllSessionAction::class, AllSession::class, Session::class],
        ];
    }
}
