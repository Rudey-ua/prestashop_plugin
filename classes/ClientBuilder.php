<?php

namespace classes;

use Ginger\Ginger;

class ClientBuilder
{
    const BANK_ENDPOINT = 'https://api.online.emspay.eu/';

    public function createClient()
    {
        return Ginger::createClient( self::BANK_ENDPOINT, \Configuration::get('API_KEY'),
(\Configuration::get('assets')) ? [ CURLOPT_CAINFO => realpath(_PS_MODULE_DIR_ . 'mymodule/assets/cacert.pem') ] : []);
    }
}