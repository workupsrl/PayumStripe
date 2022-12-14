<?php

namespace Tests\Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Action\Api\Resource\AbstractCreateAction;
use Workup\PayumStripe\Action\Api\Resource\CreateCouponAction;
use Workup\PayumStripe\Action\Api\Resource\CreateCustomerAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePaymentIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePaymentMethodAction;
use Workup\PayumStripe\Action\Api\Resource\CreatePlanAction;
use Workup\PayumStripe\Action\Api\Resource\CreateRefundAction;
use Workup\PayumStripe\Action\Api\Resource\CreateResourceActionInterface;
use Workup\PayumStripe\Action\Api\Resource\CreateSessionAction;
use Workup\PayumStripe\Action\Api\Resource\CreateSetupIntentAction;
use Workup\PayumStripe\Action\Api\Resource\CreateSubscriptionAction;
use Workup\PayumStripe\Action\Api\Resource\CreateTaxRateAction;
use Workup\PayumStripe\Api\KeysAwareInterface;
use Workup\PayumStripe\Request\Api\Resource\AbstractCreate;
use Workup\PayumStripe\Request\Api\Resource\CreateCoupon;
use Workup\PayumStripe\Request\Api\Resource\CreateCustomer;
use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreatePaymentIntent;
use Workup\PayumStripe\Request\Api\Resource\CreatePaymentMethod;
use Workup\PayumStripe\Request\Api\Resource\CreatePlan;
use Workup\PayumStripe\Request\Api\Resource\CreateRefund;
use Workup\PayumStripe\Request\Api\Resource\CreateSession;
use Workup\PayumStripe\Request\Api\Resource\CreateSetupIntent;
use Workup\PayumStripe\Request\Api\Resource\CreateSubscription;
use Workup\PayumStripe\Request\Api\Resource\CreateTaxRate;
use LogicException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;
use Stripe\ApiResource;
use Stripe\Checkout\Session;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\Issuing\CardDetails;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Plan;
use Stripe\Refund;
use Stripe\SetupIntent;
use Stripe\Subscription;
use Stripe\TaxRate;
use Tests\Workup\PayumStripe\Action\Api\ApiAwareActionTestTrait;
use Tests\Workup\PayumStripe\Stripe\StripeApiTestHelper;

final class CreateActionTest extends TestCase
{
    use StripeApiTestHelper;
    use ApiAwareActionTestTrait;

    /**
     * @dataProvider requestList
     */
    public function testShouldImplements(string $createActionClass): void
    {
        $action = new $createActionClass();

        $this->assertInstanceOf(ApiAwareInterface::class, $action);
        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertNotInstanceOf(GatewayInterface::class, $action);
        $this->assertInstanceOf(CreateResourceActionInterface::class, $action);
    }

    /**
     * @dataProvider requestList
     *
     * @param class-string|ApiResource $createClass
     */
    public function testShouldCreateAnApiRessource(
        string $createActionClass,
        string $createRequestClass,
        string $createClass
    ): void {
        $model = [];

        $apiMock = $this->createApiMock();

        /** @var AbstractCreateAction $action */
        $action = new $createActionClass();
        $action->setApiClass(KeysAwareInterface::class);
        $this->assertEquals($createClass, $action->getApiResourceClass());
        $action->setApi($apiMock);

        /** @var AbstractCreate $request */
        $request = new $createRequestClass($model);
        $this->assertTrue($action->supportAlso($request));

        $this->stubRequest(
            'post',
            $createClass::classUrl(),
            [],
            null,
            false,
            [
                'object' => $createClass::OBJECT_NAME,
                'id' => 'test_id_0',
            ]
        );

        $supportAlso = $action->supportAlso($request);
        $this->assertTrue($supportAlso);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $action->execute($request);
        $this->assertInstanceOf($createClass, $request->getApiResource());
    }

    public function testShouldThrowExceptionIfApiResourceClassIsNotCreatable(): void
    {
        $model = [];
        $action = new class() extends AbstractCreateAction {
            public function supportAlso(CreateInterface $request): bool
            {
                return true;
            }
        };

        $action->setApiResourceClass(CardDetails::class);
        $this->assertEquals(CardDetails::class, $action->getApiResourceClass());

        $request = new class($model) extends AbstractCreate {
        };

        $this->assertTrue($action->supportAlso($request));
        $this->expectException(LogicException::class);
        $action->execute($request);
    }

    public function requestList(): array
    {
        return [
            [CreateCouponAction::class, CreateCoupon::class, Coupon::class],
            [CreateCustomerAction::class, CreateCustomer::class, Customer::class],
            [CreateSessionAction::class, CreateSession::class, Session::class],
            [CreatePaymentIntentAction::class, CreatePaymentIntent::class, PaymentIntent::class],
            [CreatePaymentMethodAction::class, CreatePaymentMethod::class, PaymentMethod::class],
            [CreatePlanAction::class, CreatePlan::class, Plan::class],
            [CreateRefundAction::class, CreateRefund::class, Refund::class],
            [CreateSetupIntentAction::class, CreateSetupIntent::class, SetupIntent::class],
            [CreateSubscriptionAction::class, CreateSubscription::class, Subscription::class],
            [CreateTaxRateAction::class, CreateTaxRate::class, TaxRate::class],
        ];
    }
}
