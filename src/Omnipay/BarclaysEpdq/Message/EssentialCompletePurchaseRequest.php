<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * BarclaysEpdq Complete Purchase Request
 */
class EssentialCompletePurchaseRequest extends EssentialPurchaseRequest
{
    public function getData()
    {
        // Barclays allows GET or POST methods for the sending of parameters..
        $requestData = $this->httpRequest->request->all();
        if ($this->getCallbackMethod() == 'GET') {
            $requestData = $this->httpRequest->query->all();
        }

        // Calculate the SHA and verify if it is a legitimate request
        if ($this->getShaOut() && array_key_exists('SHASIGN', $requestData)) {
            $barclaysSha = (string) $requestData['SHASIGN'];
            unset($requestData['SHASIGN']);

            $ourSha = $this->calculateSha($this->cleanParameters($requestData), $this->getShaOut());

            if ($ourSha !== $barclaysSha) {
                throw new InvalidResponseException("Hashes do not match, request is faulty or has been tampered with.");
            }
        }

        return $requestData;
    }

    public function sendData($data)
    {
        return $this->response = new EssentialCompletePurchaseResponse($this, $data);
    }
}
