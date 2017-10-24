<?php
defined('_ACCESS') or die;

class models_helpers_Paging{

  static private $instance;

  private $paging_tpl = 'application/views/tpls/paging.phtml';

  private function __construct()
  {
    
  }

  static public function instance()
  {
    if( is_null ( self::$instance ) )
      self::$instance = new self();

    return self::$instance;
  }

  private function getPages( $totalPages, $page, $range )
  {
    $list = array();
    if( $totalPages>0 ){
      
      for( $i=1; $i<=$range; $i++ )
        if( $totalPages>=$i )
          $list[$i] = $i;

      for($i=($page-$range);$i<=($page+$range);$i++){
        if( $i>0 && $i<=$totalPages )
          $list[$i] = $i;
      }

      for( $i=0; $i<$range; $i++ )
        if( ($totalPages-$i)>0 )
          $list[$totalPages-$i] = $totalPages-$i;
              
      $res['items'] = $list;  
      sort($res['items']);
      $prev = 0;
      foreach( $res['items'] as $key => $val){
        if( $prev+1 != $val )
          $res['separator'][$key-1] = $key-1;
        $prev = $val;
      }
      
            
      $res['prev'] = $page != 1 ? $page-1: false;
      $res['first'] = $page != 1 ? 1 : false;
      
      $res['next'] = $page != $totalPages ? $page+1 : false ;
      $res['last'] = $page != $totalPages ? $totalPages : false ;
      
      $res['current'] = $page;
      
      
      return $res;      
    }
    return false;
  }

  public function setTemplate($tpl)
  {
    $this->paging_tpl = $tpl;
  }

  public function getPaging( $link, $pageTotal, $pageCurrent = 1, $range = 3, $linkName = 'page' )
  {
    $list = $this->getPages($pageTotal,$pageCurrent, $range);
    if (sizeof($list))
    {
      Viewer::instance()->list = $list;
      Viewer::instance()->link = $link . $linkName . '/';

      return Viewer::instance()->getBuffered($this->paging_tpl);
    }
  }

}
?>