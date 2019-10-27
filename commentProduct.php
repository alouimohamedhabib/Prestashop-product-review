<?php

if (!defined('_PS_VERSION_'))
    return false;

require_once(_PS_MODULE_DIR_ . "commentProduct/commentProductClass.php");

class CommentProduct extends Module implements \PrestaShop\PrestaShop\Core\Module\WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'commentproduct';
        $this->author = 'Aloui Mohamed Habib';
        $this->version = '1.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Product comment', array(), 'Modules.CommentProduct.Admin');
        $this->description = $this->trans('Allow store users to leave a comment for product', array(), 'Modules.CommentProduct.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:commentProduct/views/templates/hook/CommentProduct.tpl';
    }


    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->templateFile);
    }

    public function install()
    {
        return parent::install() &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_comment` (
                `id_comment` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
                `product_id` int(10) NOT NULL,
                `comment` varchar(255) NOT NULL,
                PRIMARY KEY (`id_comment`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');

    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_comment`');
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        // handle form submission

        if (Tools::isSubmit('comment')) {
            $commentProduct =  new commentProductClass();
            $commentProduct->comment;
            $commentProduct->product_id;
            $commentProduct->user_id;
            $commentProduct->save();
            print_r(Tools::getAllValues());
        }

        return array(
            'message' => "Hello, this product is great!"
        );
    }
}
