<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\WebhookEvent;

use ArrayAccess;
use Workup\PayumStripe\Request\Api\WebhookEvent\WebhookEvent;
use Workup\PayumStripe\Token\TokenHashKeysInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetToken;
use Payum\Core\Request\Notify;
use Payum\Core\Security\TokenInterface;
use Stripe\StripeObject;

abstract class AbstractPaymentAction extends AbstractWebhookEventAction implements GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @param WebhookEvent $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        // 0. Retrieve the Session|PaymentIntent|SetupIntent into the WebhookEvent
        /** @var StripeObject $sessionModeObject it can't be null here, already checked by the `supports` method */
        $sessionModeObject = $this->retrieveSessionModeObject($request);

        // 1. Retrieve the token hash into the metadata
        /** @var string $tokenHash */
        $tokenHash = $this->retrieveTokenHash($sessionModeObject);

        // 2. Try to found the Token
        $token = $this->findTokenByHash($tokenHash);

        // 3. Execute a `Notify` with the retrieved token
        $this->gateway->execute(new Notify($token));
    }

    protected function retrieveSessionModeObject(WebhookEvent $request): ?StripeObject
    {
        $eventWrapper = $request->getEventWrapper();

        if (null === $eventWrapper) {
            return null;
        }

        /** @var StripeObject|null $stripeObject */
        $stripeObject = $eventWrapper->getEvent()->offsetGet('data');
        if (null === $stripeObject) {
            return null;
        }

        $sessionModeObject = $stripeObject->offsetGet('object');

        if (null === $sessionModeObject) {
            return null;
        }

        if (false === ($sessionModeObject instanceof StripeObject)) {
            return null;
        }

        return $sessionModeObject;
    }

    private function retrieveTokenHash(StripeObject $sessionModeObject): ?string
    {
        /** @var ArrayAccess|null $metadata */
        $metadata = $sessionModeObject->offsetGet('metadata');
        if (null === $metadata) {
            return null;
        }

        $tokenHashMetadataKeyName = $this->getTokenHashMetadataKeyName();
        /** @var string|null $tokenHash */
        $tokenHash = $metadata->offsetGet($tokenHashMetadataKeyName);
        if (null === $tokenHash) {
            return null;
        }

        return $tokenHash;
    }

    public function getTokenHashMetadataKeyName(): string
    {
        return TokenHashKeysInterface::DEFAULT_TOKEN_HASH_KEY_NAME;
    }

    private function findTokenByHash(string $tokenHash): TokenInterface
    {
        $getTokenRequest = new GetToken($tokenHash);

        $this->gateway->execute($getTokenRequest);

        return $getTokenRequest->getToken();
    }

    /**
     * @param WebhookEvent $request
     */
    public function supports($request): bool
    {
        if (false === parent::supports($request)) {
            return false;
        }

        $sessionModeObject = $this->retrieveSessionModeObject($request);
        if (null === $sessionModeObject) {
            return false;
        }

        $tokenHash = $this->retrieveTokenHash($sessionModeObject);
        return null !== $tokenHash;
    }
}
