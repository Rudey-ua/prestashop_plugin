<?php

namespace classes;

use Ginger\Ginger;

class ClientBuilder
{
    const BANK_ENDPOINT = 'https://api.online.emspay.eu/';

    public function createClient()
    {
        if(\Configuration::get('assets')){
            $array = array(CURLOPT_CAINFO => 'modules/mymodule/assets/cacert.pem');
        }
        return Ginger::createClient(self::BANK_ENDPOINT, \Configuration::get('API_KEY'), $array ?? []);
    }
}