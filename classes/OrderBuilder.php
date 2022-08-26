<?php

namespace classes;

class OrderBuilder
{
    private $context;
    private $module;

    public function __construct()
    {
        $this->context = \Context::getContext();
        $this->module = \Module::getInstanceByName('mymodule');
    }

    public function getMerchantOrderId()
    {
        return $this->getModule()->currentOrder;
    }

    public function getCurrencyISO()
    {
        return $this->context->currency->iso_code;
    }

    public function getCurrencyId()
    {
        return $this->context->currency->id;
    }

    public function getAmount()
    {
        return $this->context->cart->getOrderTotal() * 100;
    }

    public function getDescription()
    {
        return 'Your order №' . $this->getMerchantOrderId() . ' at ' . $this->context->shop->name;
    }

    public function getReturnUrl()
    {
        return 'http://localhost/module/mymodule/validation?id_cart=' . $this->getCart()->id . '&id_module=' . $this->getModule()->id . '&id_customer=' . $this->getCustomer()->secure_key;
    }

    public function getModule()
    {
        return $this->module;
    }
    
    public function getCart()
    {
        return $this->context->cart;
    }

    public function getCustomer()
    {
        return new \Customer($this->getCart()->id_customer);
    }

    public function buildOrder($issuer)
    {
        $customer = $this->getCustomerInfo();

        return [
            'merchant_order_id' => (string)$this->getMerchantOrderId(),
            'currency' => $this->getCurrencyISO(),
            'amount' => $this->getAmount(),
            'description' => $this->getDescription(),
            'return_url' => $this->getReturnUrl(),
            'customer' => [
                'address' => $customer['address'],
                'address_type' => 'customer',
                'birthdate' => $customer['birthdate'],
                'country' => $customer['country_iso'],
                'email_address' => $customer['email'],
                'first_name' => $customer['first_name'],
                'ip_address' => $customer['ip_address'],
                'last_name' => $customer['last_name'],
                'locale' => mb_strtolower($customer['country_iso'])
            ],
            'webhook_url' => 'http://localhost/module/mymodule/webhook',
            'transactions' => [
                [
                    'payment_method' => 'ideal',
                    'payment_method_details' => [
                        'issuer_id' => $issuer
                    ]
                ]
            ]
        ];
    }

    public function getCustomerInfo()
    {
        $customer_data = [];

        $id_address = current($this->context->customer->getAddresses($this->context->language->id))['id_address'];
        $customer_info = $this->context->customer->getSimpleAddress($id_address);

        $customer_data['address'] = $customer_info['address1'] . str_replace(' ', '', $customer_info['postcode']) . ' ' . $customer_info['city'];
        $customer_data['country_iso'] = $customer_info['country_iso'];
        $customer_data['email'] = $this->context->customer->email;
        $customer_data['first_name'] = $this->context->customer->firstname;
        $customer_data['last_name'] = $this->context->customer->lastname;
        $customer_data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $customer_data['birthdate'] = $this->context->customer->birthday;

        return $customer_data;
    }
}