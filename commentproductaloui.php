<?php

if (!defined('_PS_VERSION_'))
    return false;

require_once(_PS_MODULE_DIR_ . "commentproductaloui/commentProductClass.php");

class CommentProductAloui extends Module implements \PrestaShop\PrestaShop\Core\Module\WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'commentproductaloui';
        $this->author = 'Aloui Mohamed Habib';
        $this->version = '1.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Product comment', array(), 'Modules.CommentProductAloui.Admin');
        $this->description = $this->trans('Allow store users to leave a comment for product', array(), 'Modules.CommentProductAloui.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:commentproductaloui/views/templates/hook/CommentProduct.tpl';
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->templateFile);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayFooterProduct')
            && $this->registerHook('displayHeader')
            && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_comment` (
                `id_comment` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
                `product_id` int(10) NOT NULL,
                `comment` varchar(255) NOT NULL,
                `active` BOOLEAN,
                PRIMARY KEY (`id_comment`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');

    }

    public function hookdisplayHeader()
    {
        $this->context->controller->registerStylesheet('modules-commentproductaloui', 'modules/' . $this->name . '/assets/style.css');
    }

    public function getContent()
    {
        $html = "";
        if (Tools::isSubmit('getcsv')) {
            $folderName = _PS_UPLOAD_DIR_;
            $serverName = _PS_BASE_URL_ . __PS_BASE_URI__;
            $finalFileName = date('Y-m-d-i-s') . ".csv";
            $downloadFileUrl = $serverName . 'upload/' . $finalFileName;
            $file = fopen($folderName . $finalFileName, 'w');
            $data = $this->getAllRecord();
            fputcsv($file, array_keys($data[0]), ";");
            foreach ($data as $item) {
                fputcsv($file, $item, ";");
            }
            fclose($file);
            $html = "<a   type=\"button\" class=\"btn btn-primary\"     href='". $downloadFileUrl ."' >Downlod the file</a><br>" ;
        }

        if (Tools::getValue('id_comment')) {
            $resultAction = false;

            $id = Tools::getValue('id_comment');
            $comment = new commentProductClass($id);

            if (Tools::getValue('updatecommentproductaloui')) {
                $comment->active = true;
                if ($comment->save())
                    $resultAction = true;

            }
            if (Tools::isSubmit('deletecommentproductaloui')) {
                if ($comment->delete())
                    $resultAction = true;
            }

            if ($resultAction)
                $html .= "<div class='alert alert-success' >Action executed correctly </div>";
            else
                $html .= "<div class='alert alert-error' >Error happened</div>";

        }
        $data = $this->getAllRecord();
        // create the helper list
        $helper = new HelperList();
        $helper->identifier = "id_comment";
        $helper->shopLinkType = null;
        $helper->actions = array('edit', 'delete');
        $helper->title = $this->displayName;
        $helper->table = $this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $html .= $helper->generateList($data, array(
            'id_comment' => array(
                'title' => "ID",
                'width' => 80,
                'search' => false,
                'orderby' => false
            ),
            'comment' => array(
                'title' => "The comment"
            )
        ));
        return $html;


    }


    public function uninstall()
    {
        if (
            parent::uninstall()
            && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_comment`'))
            return true;
        return false;


    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        // handle form submission
        $message = "";
        $comments = null;

        if (Tools::isSubmit('comment')) {
            $commentProduct = new commentProductClass();
            $commentProduct->comment = Tools::getValue('comment');
            $commentProduct->product_id = Tools::getValue('id_product');
            $commentProduct->user_id = 1;
            $commentProduct->active = false;

            if ($commentProduct->save())
                $message = true;
            else {
                $message = false;
            }
        }
        // Get the previous comments


        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product_comment', 'pc');
        $sql->innerJoin('customer', 'c', 'pc.user_id = c.id_customer');
        $sql->where(' pc.active = 1 && pc.product_id = ' . (int)Tools::getValue('id_product'));;

        return array(
            'message' => "Hello, this product is great!",
            'messageResult' => $message,
            'comments' => DB::getInstance()->executeS($sql)
        );
    }

    protected function getAllRecord()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product_comment', 'pc');
        $sql->innerJoin('customer', 'c', 'pc.user_id = c.id_customer');
        return DB::getInstance()->executeS($sql);
    }

}
