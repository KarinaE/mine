<?php
defined('_ACCESS') or die;

class models_check_Modules extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('id', '');
        $this->addData('author', '');
        $this->addData('name', '');
        $this->addData('category', '');
        $this->addData('scripts', '');
        $this->addData('code', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            
            if (!$name)  $this->notices->addError($this->message['modules_check_name']);

            $script = $this->get('category') == 'search' ? 'search.js' :  $this->get('scripts');
                        
            $this->addData('author',   $this->get('author'));
            $this->addData('name',     $name);
            $this->addData('category', $this->get('category'));
            $this->addData('scripts',  $script);
            $this->addData('code',     $this->getParams());

            return true;
        }
    
        return false;
    }
    
    private function getParams()
    {
        if($this->get('action') == 'add')
        {
            $id = Database::instance()->select(models_BaseModel::TBL_MODULES, 'MAX(id)', '',  null, null, Database::ENCODE_HTML);
            $id = $id[0]['MAX(id)']+1;    
        }
        
        if($this->get('category') == 'code')
            return $this->codeWriter($id);
            
        if($this->get('category') == 'search')
            return $this->get('search_code');
            
        $array = explode(',',$this->get($this->get('category').'_'.'queries'));

        foreach ($array as $val)
        {
            $param = $this->get($this->get('category').'_'.$val);
            if(empty($param))
                $params[$val] = $param;
            elseif($param === false || $param == 'on')
                $params[$val] = $param === false ? 2 : 1;
                    
            if($param && !$params[$val])
                    $params[$val] = $param;      
        }
        if(is_array($params['parent_cat']))
            for($i=0;count($params['parent_cat'])>$i;$i++)
                $parent_cat .= $params['parent_cat'][$i].',';
        $params['parent_cat'] = substr($parent_cat,0,-1);
    
        return serialize($params);    
    }
    
    private function codeWriter($id = '')
    {
        $id = $id ? $id : Request::instance()->getPath();
        // module unique name
        $codeModule = '/core/module/view/code/module'.$id.'.php';
        // creation or reading file
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].models_helpers_Url::getDomain().$codeModule , "w+"); 
        $result = fwrite($fp, stripslashes($this->get('code')));
        fclose ($fp);
        if ($result == 0)
            $this->notices->addError($this->message['modules_check_code_warning']);
        else
            return $codeModule;                
    }
}
?>