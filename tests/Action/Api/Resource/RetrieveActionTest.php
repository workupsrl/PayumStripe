<?php

namespace Tests\Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Action\Api\Resource\AbstractRetrieveAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveChargeAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveCouponAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveInvoiceAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePaymentMethodAction;
use Workup\PayumStripe\Action\Api\Resource\RetrievePlanAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveProductAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveResourceActionInterface;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSessionAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSetupIntentAction;
use Workup\PayumStripe\Action\Api\Resource\RetrieveSubscriptionAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Workup\PayumStripe\Request\Api\Resource\AbstractRetrieve;
use Workup\PayumStripe\Request\Api\Resource\RetrieveCharge;
use Workup\PayumStripe\Request\Api\Resource\RetrieveCoupon;
use Workup\PayumStripe\Request\Api\Resource\RetrieveCustomer;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInvoice;
use Workup\PayumStripe\Request\Api\Resource\RetrievePaymentIntent;
use Workup\PayumStripe\Request\Api\Resource\RetrievePaymentMethod;
use Workup\PayumStripe\Request\Api\Resource\RetrievePlan;
use Workup\PayumStripe\Request\Api\Resource\RetrieveProduct;
use Workup\PayumStripe\Request\Api\Resource\RetrieveSession;
use Workup\PayumStripe\Request\Api\Resource\RetrieveSetupIntent;
use Workup\PayumStripe\Request\Api\Resource\RetrieveSubscription;
use LogicException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;
use Stripe\ApiResource;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Issuing\CardDetails;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Plan;
use Stripe\Product;
use Stripe\SetupIntent;
use Stripe\Subscription;
use Tests\Workup\PayumStripe\Action\Api\ApiAwareActionTestTrait;
use Tests\Workup\PayumStripe\Stripe\StripeApiTestHelper;

final class RetrieveActionTest extends TestCase
{
    use StripeApiTestHelper;
    use ApiAwareActionTestTrait;

    /**
     * @dataProvider requestList
     */
    public function testShouldImplements(string $retrieveActionClass): void
    {
        $action = new $retrieveActionClass();

        $this->assertInstanceOf(ApiAwareInterface::class, $action);
        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertNotInstanceOf(GatewayInterface::class, $action);
        $this->assertInstanceOf(RetrieveResourceActionInterface::class, $action);
    }

    /**
     * @dataProvider requestList
     *
     * @param class-string|ApiResource $retrieveClass
     */
    public function testShouldBeRetrieved(
        string $retrieveActionClass,
        string $retrieveRequestClass,
        string $retrieveClass
    ): void {
        $id = 'pi_1';

        $apiMock = $this->createApiMock();

        /** @var AbstractRetrieveAction $action */
        $action = new $retrieveActionClass();
        $action->setApiClass(KeysAwareInterface::class);
        $action->setApi($apiMock);
        $this->assertEquals($retrieveClass, $action->getApiResourceClass());

        /** @var AbstractRetrieve $request */
        $request = new $retrieveRequestClass($id);
        $this->assertTrue($action->supportAlso($request));

        $this->stubRequest(
            'get',
            $retrieveClass::resourceUrl($id),
            [],
            null,
            false,
            [
                'object' => $retrieveClass::OBJECT_NAME,
                'id' => $id,
            ]
        );

        $supportAlso = $action->supportAlso($request);
        $this->assertTrue($supportAlso);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $action->execute($request);
        $this->assertInstanceOf($retrieveClass, $request->getApiResource());
    }

    public function testShouldThrowExceptionIfApiResourceClassIsNotCreatable(): void
    {
        $id = 'test_1';
        $action = new class() extends AbstractRetrieveAction {
            public function supportAlso(RetrieveInterface $request): bool
            {
                return true;
            }
        };

        $action->setApiResourceClass(CardDetails::class);
        $this->assertEquals(CardDetails::class, $action->getApiResourceClass());

        $request = new class($id) extends AbstractRetrieve {
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
            [RetrieveChargeAction::class, RetrieveCharge::class, Charge::class],
            [RetrieveCouponAction::class, RetrieveCoupon::class, Coupon::class],
            [RetrieveCustomerAction::class, RetrieveCustomer::class, Customer::class],
            [RetrieveInvoiceAction::class, RetrieveInvoice::class, Invoice::class],
            [RetrievePaymentIntentAction::class, RetrievePaymentIntent::class, PaymentIntent::class],
            [RetrievePaymentMethodAction::class, RetrievePaymentMethod::class, PaymentMethod::class],
            [RetrievePlanAction::class, RetrievePlan::class, Plan::class],
            [RetrieveProductAction::class, RetrieveProduct::class, Product::class],
            [RetrieveSessionAction::class, RetrieveSession::class, Session::class],
            [RetrieveSetupIntentAction::class, RetrieveSetupIntent::class, SetupIntent::class],
            [RetrieveSubscriptionAction::class, RetrieveSubscription::class, Subscription::class],
        ];
    }
}
