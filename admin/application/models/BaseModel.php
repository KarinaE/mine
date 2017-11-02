<?php
defined('_ACCESS') or die;

abstract class models_BaseModel
{    
    const TBL_CLIENTS_INFO      = 'clients_info';
    const TBL_CLIENTS_PHONES    = 'clients_phones';
    const TBL_CLIENTS_EMAILS    = 'clients_emails';
    const TBL_CLIENTS_ADDRESS   = 'clients_address';
    const TBL_CLIENTS_SOC_ACC   = 'clients_soc_acc';
    const TBL_PRODUCTS          = 'products';
    const TBL_PARAMETERS        = 'product_options';
    const TBL_ORDERS            = 'orders';
    const TBL_ORDER_ITEMS       = 'order_items';
    const TBL_USERS             = 'users';
    const TBL_UTYPES            = 'user_types';
    const TBL_UTYPES_REL        = 'user_types_rel';
    const TBL_COMPONENTS        = 'components';
    const TBL_MENU              = 'menu';
    const TBL_MENU_NAME         = 'menu_name';
    const TBL_MENU_REL          = 'menu_user_types_relations';
    const TBL_LANGUAGE          = 'language';
    const TBL_OPTIONS           = 'options';
    const TBL_PARAMS            = 'params';
    protected $id;
    protected $db;
    protected $uinfo; // user info (front-end)

    public function __construct($id = null)
    {
        $this->db = Database::instance();
        $this->notices = models_helpers_Notices::instance();
        $this->message = models_helpers_Language::instance()->getPack('Messages');
        $this->session = Session::instance();
        $this->id = $id;
        
        $this->uinfo = models_helpers_Access::getAuthInfoAdmin();
    }
    
    final public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    final public function getId()
    {
        return $this->id;
    }
}
