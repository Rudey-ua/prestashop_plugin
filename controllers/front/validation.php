<?php
use classes\ClientBuilder;
use classes\Helper;

class MymoduleValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $client = new ClientBuilder();
        $client = $client->createClient();

        $gingerOrder = $client->getOrder($_GET['order_id']);

        $purchase_id = $gingerOrder['merchant_order_id'];
        $order_status = $gingerOrder['status'];

        $order = new Order($purchase_id);

        if($order_status == 'completed')
        {
            $order->setCurrentState(Helper::STATUSES['completed']);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$_GET['id_cart'].'&id_module='.$_GET['id_module'].'&id_order='.$purchase_id.'&key='.$_GET['id_customer']);
        }
            $order->setCurrentState(Helper::STATUSES['error']);

            parent::initContent();
            $this->context->smarty->assign([
                'module' => $this->module->name,
            ]);

            $this->setTemplate('module:mymodule/views/templates/hook/payment_return.tpl');
    }
}