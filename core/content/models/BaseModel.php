<?
/**
    базовый класс для отрисовки контента
 */
 

// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class models_BaseModel{

    const TBL_CLI  = 'clients_info';
    const TBL_CLP  = 'clients_phones';
    const TBL_CLE  = 'clients_emails';
    const TBL_CLA  = 'clients_address';
    const TBL_CSA  = 'clients_soc_acc';
    const TBL_CCR  = 'clients_contacts_relations';
    const TBL_ORD  = 'orders';
    const TBL_ORI  = 'order_items';
    const TBL_PRD  = 'products';
    const TBL_OPT  = 'product_options';
    const TBL_MOD  = 'modules';

    protected $db;

    public function __construct($id = null)
    {
        $this->db = Database::instance();
    }
    

}
?>