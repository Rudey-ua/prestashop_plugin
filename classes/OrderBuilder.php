<?php

class OrderBuilder
{
    public function createOrder($order_id, $issuer, $module_id, $customer_id)
    {
        $client = new Client();

        $context = Context::getContext();
        $currency = $context->currency->getFields()['iso_code'];
        $amount = $context->cart->getOrderTotal() * 100;
        $description = 'Your order №' . $order_id . ' at ' . $context->shop->name;

        return $client->createClient()->createOrder(
            [
                'merchant_order_id' => (string)$order_id,
                'currency' => $currency,
                'amount' => $amount,
                'description' => $description,
                'return_url' => 'http://localhost/module/mymodule/validation?id_cart=' . $context->cart->id . '&id_module=' . $module_id . '&id_customer=' . $customer_id,
                'webhook_url' => 'http://localhost/module/mymodule/webhook',
                'transactions' => [
                    [
                        'payment_method' => 'ideal',
                        'payment_method_details' => [
                            'issuer_id' => $issuer
                        ]
                    ]
                ]
            ]
        );
    }
}