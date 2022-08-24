<?php

require_once 'modules/mymodule/classes/OrderBuilder.php';

class MymoduleWebhookModuleFrontController extends ModuleFrontController {

    public function postProcess()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $status_codes = [
            'completed' => 2,
            'error' => 8,
            'cancelled' => 6,
            'expired' => 15
        ];

        if($data['event'] == 'status_changed') {

            $client = new ClientBuilder();
            $client = $client->createClient();
            $order = $client->getOrder($data['order_id']);

            $merchant_order = new Order($order['merchant_order_id']);
            $merchant_order->setCurrentState($status_codes[$order['status']]);
        }
    }
}

