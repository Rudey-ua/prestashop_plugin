<?php

require_once 'modules/mymodule/classes/OrderBuilder.php';

class MymodulePaymentModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $order = new OrderBuilder();
        $cart = $order->getCart();

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        if (!Validate::isLoadedObject($order->getCustomer()))
            Tools::redirect('index.php?controller=order&step=1');

        $this->module->validateOrder
        (
            $order->getCart()->id,
            Configuration::get('PS_OS_BANKWIRE'),
            (float)$order->getAmount(),
            $order->getModule()->displayName,
            null,
            null,
            $order->getCurrency()->id,
            false,
            $order->getCustomer()->secure_key
        );

        $client = new ClientBuilder();

        $merchant_order = $client->createClient()->createOrder(
            [
            'merchant_order_id' => (string)$order->getModule()->currentOrder,
            'currency' => $order->getCurrency()->iso_code,
            'amount' => $order->getAmount(),
            'description' => $order->getDescription(),
            'return_url' => 'http://localhost/module/mymodule/validation?id_cart=' . $order->getCart()->id . '&id_module=' . $order->getModule()->id . '&id_customer=' . $order->getCustomer()->secure_key,
            'webhook_url' => 'http://localhost/module/mymodule/webhook',
            'transactions' => [
                [
                    'payment_method' => 'ideal',
                    'payment_method_details' => [
                        'issuer_id' => $_POST['issuer']
                    ]
                ]
            ]
        ]);

        $returnURl = current($merchant_order['transactions'])['payment_url'];
        Tools::redirect($returnURl);
    }
}
