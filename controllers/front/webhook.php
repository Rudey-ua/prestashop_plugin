<?php
use classes\ClientBuilder;
use classes\Helper;

class MymoduleWebhookModuleFrontController extends ModuleFrontController {

    public function postProcess()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if($data['event'] == 'status_changed') {
            $client = new ClientBuilder();
            $client = $client->createClient();
            $order = $client->getOrder($data['order_id']);

            $merchant_order = new Order($order['merchant_order_id']);
            $merchant_order->setCurrentState(Helper::STATUSES[$order['status']]);
        }
    }
}

