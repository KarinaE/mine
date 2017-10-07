<?php
defined('_ACCESS') or die;

class models_check_Product extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('author', '');
        $this->addData('name', '');
        $this->addData('show_title', '');
        $this->addData('show_date', '');
        $this->addData('showCmnt', '');
        $this->addData('url', '');
        $this->addData('category', '');
        $this->addData('main_lang', '');
        $this->addData('title', '');
        $this->addData('description', '');
        $this->addData('keywords', '');
        $this->addData('canonical', '');
        $this->addData('cat_img', '');
        $this->addData('template', '');
        $this->addData('contype', '');
        $this->addData('ordr', '');
        $this->addData('params', '');
        $this->addData('language', '');
        $this->addData('content', '');
        $this->addData('price', '');
        $this->addData('oldprice', '');
        $this->addData('brand', '');
        $this->addData('type_size', '');
        $this->addData('bonus', '');
        $this->addData('vendor', '');

    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            if (!$name)  $this->notices->addError($this->message['content_check_name']);

            $type_size = $this->get('type_size');
            if (!$type_size)  $this->notices->addError($this->message['content_check_type_sizename']);

            $show_title = $this->get('show_title');
            $show_title = empty($show_title) ? 2 : 1;
            
            $show_date = $this->get('show_date');
            $show_date = empty($show_date) ? 2 : 1;
            
            $showCmnt = $this->get('showCmnt');
            $showCmnt = empty($showCmnt) ? 2 : 1;

            $url = $this->get('url');
            if (empty($url)) $url = $this->transliteration($name);
            
            $cat_img = $this->get('cat_img');
            $cat_img = empty($cat_img) ? 2 : 1;
            
            $contype = $this->get('contype');
            $contype = empty($contype) ? 'product' : $contype;
            
            $this->addData('author',        $this->get('author'));
            $this->addData('name',          $name);
            $this->addData('show_title',    $show_title);
            $this->addData('show_date',     $show_date);
            $this->addData('showCmnt',      $showCmnt);
            $this->addData('url',           $url);
            $this->addData('category',      $this->get('category'));
            $this->addData('main_lang',     $this->get('main_lang'));
            $this->addData('title',         $this->get('title'));
            $this->addData('description',   $this->get('description'));
            $this->addData('keywords',      $this->get('keywords'));
            $this->addData('canonical',     $this->get('canonical'));
            $this->addData('cat_img',       $cat_img);
            $this->addData('template',      $this->get('template'));
            $this->addData('contype',       $contype);
            $this->addData('ordr',          $this->get('ordr'));
            $this->addData('params',        $this->getParams());
            $this->addData('language',      $this->getLangs());
            $this->addData('content',       $this->get('content'));
            $this->addData('price',         $this->get('price'));
            $this->addData('oldprice',      $this->get('oldprice'));
            $this->addData('type_size',     $this->get('type_size'));
            $this->addData('brand',         $this->get('brand'));
            $this->addData('bonus',         $this->get('bonus'));
            $this->addData('vendor',        $this->get('vendor'));

            
            return true;
        }
    
        return false;
    }
    
    private function getParams()
    {   
        $array = explode('|',substr($this->get('params'),0,-1));
        
        foreach ($array as $val)
            if($this->get('param_'.$val))
                $params[$val] = $this->get('param_'.$val);
   
        return serialize($params);    
    }
    
    private function getLangs()
    {   
        $array = explode('|',substr($this->get('langs'),0,-1));

        foreach ($array as $val)
            if($this->get($val))
                $langs[$val] = $this->get($val);

        if($langs)
            return serialize($langs);
    }
}
?>