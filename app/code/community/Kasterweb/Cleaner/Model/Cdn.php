<?php

class Kasterweb_Cleaner_Model_Cdn extends Mage_Core_Model_Abstract
{
    const MEDIA_TYPE_FLASH_MEDIA_STREAMING = 2;
    const MEDIA_TYPE_HTTP_LARGE = 3;
    const MEDIA_TYPE_HTTP_SMALL = 8;
    const MEDIA_TYPE_APPLICATION_DELIVERY_NETWORK = 14;

    protected function _construct()
    {
        $this->_init('cleaner/cdn');
    }

    public function truncate($mediaPath = '*', $mediaType = self::MEDIA_TYPE_HTTP_LARGE)
    {
        $accountId = Mage::getStoreConfig('cleaner/cdn/account_id');
        $accessToken = Mage::getStoreConfig('cleaner/cdn/access_token');
        $baseUrl = Mage::getStoreConfig('cleaner/cdn/base_url');

        if (empty($accountId) || empty($accessToken) || empty($baseUrl)) {
            throw new InvalidArgumentException('Account ID, Access token or Base URL is missing. Please set it in System > Configuration > Kasterweb > Cleaner > CDN');
        }

        $response = $this->sendRequest($accountId, $accessToken, array(
            'MediaPath' => "{$baseUrl}/{$mediaPath}",
            'MediaType' => $mediaType
        ));

        if ($response->isError()) {
            $error = json_decode($response->getRawBody(), true);
            throw new CdnException($error['Message']);
        }

        return $response;
    }

    protected function sendRequest($accountId, $accessToken, $body) {
        $client = new Zend_Http_Client();
        $client->setUri("https://api.edgecast.com/v2/mcc/customers/{$accountId}/edge/purge");
        $client->setHeaders(array(
            'Authorization' => "TOK:{$accessToken}",
            'Content-Type' => 'application/json'
        ));
        $client->setRawData(json_encode($body));
        return $client->request(Zend_Http_Client::PUT);
    }
}

class CdnException extends Exception { }
