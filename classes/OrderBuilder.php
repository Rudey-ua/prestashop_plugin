<?php

class OrderBuilder
{
    private $context;
    private $module;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->module = Module::getInstanceByName('mymodule');
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getCurrency()
    {
        return $this->context->currency;
    }

    public function getAmount()
    {
        return $this->context->cart->getOrderTotal() * 100;
    }

    public function getDescription()
    {
        return 'Your order №' . $this->getModule()->currentOrder . ' at ' . $this->context->shop->name;
    }

    public function getCart()
    {
        return $this->context->cart;
    }

    public function getCustomer()
    {
        return new Customer($this->getCart()->id_customer);
    }
}