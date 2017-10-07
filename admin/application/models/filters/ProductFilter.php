<?php
defined('_ACCESS') or die;
    
class models_filters_ProductFilter extends models_filters_BaseFilter
{
    public function __construct()
    {
        // ��������� �������� ������� ����������
        $this->sortKeys = array('t1.date_add','id','name','author','t3.name','price','vendor');
        
        // �������� �� ��������� ������� ����������
        $this->sortKeysDefault = $this->sortKeys[1];
        $this->sortOrderDefault = 'desc';
        
        parent::__construct();
    }  
    
    protected function parseFilter()
    {
        parent::parseFilter();
        
        $path = $this->request->getPathByName('status');
        if ($path)
        {
            $this->data['status'] = $path;
            $this->linkArray['status'] = 'status/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('size');
        if ($path)
        {
            $this->data['size'] = $path;
            $this->linkArray['size'] = 'size/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('category');
        if ($path)
        {
            $this->data['category'] = urldecode ($path);
            $this->linkArray['category'] = 'category/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('fulltext');
        if ($path)
        {
            $this->data['fulltext'] = urldecode ($path);
            $this->linkArray['fulltext'] = 'fulltext/' . $path . '/';
        }
        
    }
} 
?>