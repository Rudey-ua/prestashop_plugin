<?php
use classes\OrderBuilder;
use classes\ClientBuilder;

class MymodulePaymentModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $orderBuilder = new OrderBuilder();
        $cart = $orderBuilder->getCart();

        if (!$cart->id_customer || !$cart->id_address_delivery || !$cart->id_address_invoice || !$this->module->active || !Validate::isLoadedObject($orderBuilder->getCustomer())) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        
        $this->module->validateOrder
        (
            $orderBuilder->getCart()->id,
            Configuration::get('PS_OS_BANKWIRE'),
            (float)$orderBuilder->getAmount(),
            $orderBuilder->getModule()->displayName,
            null,
            null,
            $orderBuilder->getCurrencyId(),
            false,
            $orderBuilder->getCustomer()->secure_key
        );

        $client = (new ClientBuilder())->createClient();

        $ginger_order = $client->createOrder($orderBuilder->buildOrder($_POST['issuer']));

        $payment_url = current($ginger_order['transactions'])['payment_url'];
        Tools::redirect($payment_url);
    }
}
