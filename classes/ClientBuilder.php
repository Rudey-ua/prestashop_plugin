<?php

require_once(\_PS_MODULE_DIR_ . '/mymodule/vendor/autoload.php');

use Ginger\Ginger;

class ClientBuilder
{
    private $apiKey;
    private $endPoint;

    public function __construct()
    {
        $this->apiKey = Configuration::get('API_KEY');
        $this->endPoint = 'https://api.online.emspay.eu/';
    }

    public function createClient()
    {
        if(Configuration::get('caCert') == 1){
            return Ginger::createClient($this->endPoint, $this->apiKey, [
                CURLOPT_CAINFO => __DIR__ . '/caCert/cacert.pem'
            ]);
        }
        return Ginger::createClient($this->endPoint, $this->apiKey);
    }
}