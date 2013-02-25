<?php
namespace Payum\Paypal\ExpressCheckout\Nvp\Action;

use Buzz\Message\Form\FormRequest;

use Payum\Bridge\Spl\ArrayObject;
use Payum\Exception\RequestNotSupportedException;
use Payum\Exception\LogicException;
use Payum\Paypal\ExpressCheckout\Nvp\Request\GetExpressCheckoutDetailsRequest;

class GetExpressCheckoutDetailsAction extends  ActionPaymentAware
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /** @var $request GetExpressCheckoutDetailsRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = new ArrayObject($request->getModel());
        if (false == $model['TOKEN']) {
            throw new LogicException('TOKEN must be set. Have you run SetExpressCheckoutAction?');
        }

        $buzzRequest = new FormRequest();
        $buzzRequest->setField('TOKEN', $model['TOKEN']);
        
        $response = $this->payment->getApi()->getExpressCheckoutDetails($buzzRequest);
        
        $model->replace($response);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return 
            $request instanceof GetExpressCheckoutDetailsRequest &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}