<?php
require_once(\_PS_MODULE_DIR_ . '/mymodule/vendor/autoload.php');

use classes\ClientBuilder;
use Prestashop\Prestashop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends PaymentModule
{
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Max Kostenko';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module');
        $this->description = $this->l('Description test module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        DB::getInstance()->execute('CREATE TABLE IF NOT EXISTS `ps_example` (`id` INT NOT NULL AUTO_INCREMENT , `testTitle` VARCHAR(255) NOT NULL , `testDescription` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;');

        return (
            parent::install()
            && $this->registerHook('header')
            && $this->registerHook('paymentReturn')
            && $this->registerHook('paymentOptions')
        );
    }

    public function uninstall()
    {
        return (
            parent::uninstall()
            && Configuration::deleteByName('MYMODULE_NAME')
        );
    }

    public function getContent()
    {
        if (Tools::getValue('API_KEY')) {
            Configuration::updateValue('API_KEY', Tools::getValue('API_KEY'));
            $message = $this->displayConfirmation($this->l('Settings updated'));
        }

        $api_key = Configuration::get('API_KEY');

        if(Tools::isSubmit('btnSubmit')){

            $value = Tools::getValue('certificate') == 'yes';

            Configuration::updateValue('assets', (int)$value);
        }

        $this->context->smarty->assign([
            'API_KEY' => $api_key,
            'message' => $message ?? '',
            'status' => Configuration::get('assets')
        ]);

        return $this->fetch("module:mymodule/views/templates/admin/configuration.tpl");
    }

    public function hookPaymentOptions()
    {
        $formAction = $this->context->link->getModuleLink($this->name, 'payment', array(), true);

        $client = new ClientBuilder();
        $idealIssuers = $client->createClient()->getIdealIssuers();

        $this->smarty->assign([
                'action' => $formAction,
                'idealIssuers' => $idealIssuers
            ]);

        $paymentForm = $this->fetch('module:mymodule/views/templates/hook/payment_options.tpl');

        $iDeal = new PaymentOption();

        $iDeal->setModuleName($this->name)
            ->setCallToActionText('iDeal')
            ->setAction($this->context->link->getModuleLink($this->name, 'payment'))
            ->setForm($paymentForm);

        return [$iDeal];
    }
}