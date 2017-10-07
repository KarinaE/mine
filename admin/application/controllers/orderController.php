<?php
defined('_ACCESS') or die;

class controllers_orderController extends controllers_BaseController
{
	protected $model;
	protected $filter;
	protected $ajax;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['order_nav'], 'link'=> '/order', 'img'=>'order_icon.png' ));
        
        $this->viewer->model = $this->model = new models_Order();
	}


	public function indexAction()
    {
        parent::indexAction();

        // getting product options
        $this->viewer->options = $this->model->getOptions();
        // getting all sizes
        $this->viewer->sizes = $this->model->getSizes();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }
    
    public function changeValueAction()
    {
        // getting values
        $this->ajax['id']     = (int)$this->request->getPath();
        $this->ajax['db']     = $_POST['db'];
        $this->ajax['value']  = $_POST['value'];
        $this->ajax['type']   = $_POST['type'];
        $this->ajax['option'] = $_POST['option'];

        // update data 0 - table name, 1 - field name
        $this->ajax['db'] = explode('-',$this->ajax['db']);
        // model method updates DB with new values
        $this->model = new models_Order($this->ajax['id']);

        if($this->ajax['option'] == 'undefined')
            $this->simpleUpdate();
        else
            $this->optionUpdate();

        // check for return in case of size
        if(key($this->ajax['db'][1]) == 'size')
        {
            $result = $this->model->getSizes();
            $result = $result[$this->ajax['value']]['size'];
        }
        elseif($this->ajax['type'] == 'select')
        {
            $this->changeQuantity();
            $result = models_helpers_Options::OrderStatuses($this->ajax['value']).','.models_helpers_Options::$statusColors[$this->ajax['value']];
        }
        else
            $result = $this->ajax['value'];

        exit($result);
    }

    private function simpleUpdate()
    {
        // update array
        $this->ajax['db'][1] = array($this->ajax['db'][1] => $this->ajax['value']);

        //update
        $this->model->updateValue($this->ajax['db']);
    }

    private function optionUpdate()
    {
        // getting array of options added to product
        $oldvalue = $this->model->getProductOption();
        $oldvalue = unserialize($oldvalue['options']);

        // changing value by key in array
        $oldvalue[$this->ajax['option']] = $this->ajax['value'];
        // update array
        $this->ajax['db'][1] = array($this->ajax['db'][1] => serialize($oldvalue));

        //update
        if($this->model->updateValue($this->ajax['db']))
            $this->getOptionValue();
    }

    private function getOptionValue()
    {
        // getting option values
        $options = $this->model->getOption(explode('-',$this->ajax['option'])[1]);

        exit($options[$this->ajax['value']]['val']);
    }

    private function changeQuantity()
    {
        // getting option values and size
        $data = $this->model->getProductData($this->ajax['id']);

        $option = null;
        // form value for option condition in quantity
        foreach (unserialize($data['options']) as $k => $v)
            $option .= preg_replace('~\D+~','',$k) . '-value'. $v .'-';

        // array for selct
        $selectArray = array('product_id' => $data['product_id'], 'size_id' => $data['size'], 'params' => $option);
        // getting option values and size
        $data = $this->model->getProductQuantity($selectArray);
        // changing quantity
        $data['value'] = $this->ajax['value'] == 3 ? $data['value']-1 : ($this->ajax['value'] == 7 ? $data['value']+1 : $data['value']);
        // formatting update array
        $updateArray = array('id' => $data['id'], 'value' => $data['value']);

        // update quantity
        return $this->model->updateProductQuantity($updateArray);

    }
}