<?php
defined('_ACCESS') or die;
  
abstract class models_filters_BaseFilter
{
    protected $request;
    
    protected $data;
    protected $link;
    protected $linkArray = array();
    
    protected $sortKeys;
    protected $sortOrder;
    
    protected $sortKeysDefault;
    protected $sortOrderDefault;
    
    protected $onpageDefault;
    protected $pageDefault;
    
    protected $noSortOrderInLink = false;
    
    public function __construct()
    {
        $this->request = Request::instance();
        
        $this->onpageDefault = $this->onpageDefault ? $this->onpageDefault : Settings::instance()->getParam(array('main', 'onPageItemsDefault'));
        
        $this->pageDefault = 1;
        
        $this->sortOrder = array('asc', 'desc');
        
        $this->parseFilter();
    }
        
    protected function parseFilter()
        { 
        $filter = array();
        
        // текущая страница списка
        $filter['page'] = $this->request->getPathByName('page');
        $filter['page'] = $filter['page']>0 ? $filter['page'] : $this->pageDefault;

        $filter['onpage'] = $this->request->getPathByName('onpage');
        $filter['onpage'] = $filter['onpage']>0 ? $filter['onpage'] : $this->onpageDefault;
        
        // сортировка списка. название поля и направление desc || asc
        $filter['sortKey'] = $this->sortKeysDefault;
        $filter['sortOrder'] = $this->sortOrderDefault;
        
        $sort = $this->request->getPathByName('sort');

        if( $sort && count($sort = explode('-',$sort))>0 )
        {
            $filter['sortKey'] = in_array($sort[0], $this->sortKeys) ? $sort[0] : $filter['sortKey'];
            $filter['sortOrder'] = isset ($sort[1]) && !$this->noSortOrderInLink && in_array($sort[1], $this->sortOrder) ? $sort[1] : $filter['sortOrder'];
        }
        
        $filter['linkSortOrderReverse'] = $filter['sortOrder'] == $this->sortOrder[1] ? $this->sortOrder[0] : $this->sortOrder[1];
        $filter['linkSortOrderDefault'] = $this->sortOrderDefault;
        
        $this->linkArray['sort'] =  $filter['sortKey'] != $this->sortKeysDefault || $filter['sortOrder'] != $this->sortOrderDefault  ? ( 'sort/' . $filter['sortKey'] . (!$this->noSortOrderInLink ? '-' . $filter['sortOrder'] : '') . '/') : '';
        $this->linkArray['onpage'] =  $filter['onpage'] != $this->onpageDefault ? ( 'onpage/' . $filter['onpage'] . '/') : '';
        $this->linkArray['page'] =  $filter['page'] != $this->pageDefault ? ( 'page/' . $filter['page'] . '/') : '';
        
        // формирование частей ссылок . используются в шалоне
        $this->link = $this->request->getController() . '/' . $this->request->getAction() . '/';
        
        $this->data = $filter;
        
        return $this;
    }
    
    public function checkSortOrder( $name ){
        return $this->data['sortKey'] == $name ? $this->data['sortOrder'] : false;
    }
    
    public function getData()
    {
        return $this->data;
    }
  
    public function getPageCurrent()
    {
        return $this->data['page'];
    }
    
    public function getLinkListing()
    {
        return $this->getLinkExclude('page');
    }
    
    public function getLinkExclude( $name = '' )
    {
        $link = $this->link;
        foreach($this->linkArray as $key => $val)
            if($name != $key)
                $link .= $val;
        return $link;
    }
    
    public function getLinkForm(){
        return $this->link;
    }
    
    public function getLinkSort( $name ){
        return $this->getLinkExclude('sort') . 'sort/' . $name . (!$this->noSortOrderInLink ? '-' . ($this->data['sortKey'] == $name ? $this->data['linkSortOrderReverse'] : $this->data['linkSortOrderDefault']) : '') . '/';
    }
  
}
  
?>
