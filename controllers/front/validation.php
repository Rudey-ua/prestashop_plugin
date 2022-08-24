<?php

require_once 'modules/mymodule/classes/OrderBuilder.php';

class MymoduleValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $client = new ClientBuilder();
        $client = $client->createClient();

        $transaction = $client->getOrder($_GET['order_id']);

        $purchase_id = $transaction['merchant_order_id'];
        $order_status = $transaction['status'];

        if($order_status == 'completed')
        {
            $cart_id = $_GET['id_cart'];
            $module_id = $_GET['id_module'];
            $customer_id = $_GET['id_customer'];

            $order = new Order($purchase_id);
            $order->setCurrentState(2); // Completed

            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart_id.'&id_module='.$module_id.'&id_order='.$purchase_id.'&key='.$customer_id);
        }elseif($order_status == 'error')
        {
            $order = new Order($purchase_id);
            $order->setCurrentState(8); // Error

            parent::initContent();

            $this->context->smarty->assign([
                'module' => $this->module->name,
            ]);

            $this->setTemplate('module:mymodule/views/templates/hook/payment_return.tpl');
        }
    }
}