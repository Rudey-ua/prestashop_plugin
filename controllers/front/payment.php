<?php

require_once 'modules/mymodule/classes/OrderBuilder.php';

class MymodulePaymentModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        if (!Validate::isLoadedObject($customer))
            Tools::redirect('index.php?controller=order&step=1');

        $this->module->validateOrder
        (
            $cart->id,
            Configuration::get('PS_OS_BANKWIRE'),
            (float)$cart->getOrderTotal() * 100,
            $this->module->displayName,
            (int)$this->context->currency->id,
            $customer->secure_key
        );

        $order_id = $this->module->currentOrder;
        $customer_id = $customer->secure_key;

        $order = new OrderBuilder();
        $order = $order->createOrder($order_id, $_POST['issuer'], $this->module->id, $customer_id);

        $returnURl = current($order['transactions'])['payment_url'];

        Tools::redirect($returnURl);
    }
}
