<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\StripeJs;

use ArrayObject;
use Workup\PayumStripe\Action\AbstractCaptureAction;
use Workup\PayumStripe\Request\Api\Resource\CreatePaymentIntent;
use Workup\PayumStripe\Request\CaptureAuthorized;
use Workup\PayumStripe\Request\StripeJs\Api\RenderStripeJs;
use Payum\Core\Request\Generic;
use Stripe\ApiResource;

class CaptureAction extends AbstractCaptureAction
{
    protected function createApiResource(ArrayObject $model, Generic $request): ApiResource
    {
        $createRequest = new CreatePaymentIntent($model->getArrayCopy());
        $this->gateway->execute($createRequest);

        return $createRequest->getApiResource();
    }

    protected function render(ApiResource $captureResource, Generic $request): void
    {
        $token = $this->getRequestToken($request);
        $actionUrl = $token->getAfterUrl();

        $renderRequest = new RenderStripeJs($captureResource, $actionUrl);
        $this->gateway->execute($renderRequest);
    }

    protected function processNotNew(ArrayObject $model, Generic $request): void
    {
        parent::processNotNew($model, $request);

        // Specific case of authorized payments being captured
        // If it isn't an authorized PaymentIntent then nothing is done
        $captureAuthorizedRequest = new CaptureAuthorized($this->getRequestToken($request));
        $captureAuthorizedRequest->setModel($model);
        $this->gateway->execute($captureAuthorizedRequest);
    }
}
