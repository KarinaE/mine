<?php
defined('_ACCESS') or die;

class models_helpers_NavBar
{
    private $data;
    private $navbar_tpl;
    static private $instance;

    private function __construct()
    {
        $cfg = Settings::instance()->getParam('navbar');
        $this->navbar_tpl   = isset ($cfg['admin-tpl']) ? $cfg['admin-tpl'] : null;
    }

    static public function instance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    public function setTpl($tpl)
    {
        $this->navbar_tpl = $tpl;
    }

    public function getNavBar()
    {
        if (sizeof($this->data) ){
          Viewer::instance()->assign('navbarData', $this->data);
          Viewer::instance()->assign('navbarLast', count($this->data)-1 );
          return Viewer::instance()->getBuffered($this->navbar_tpl);
        }
    }

    public function addNavBar( $navItem )
    {
        $this->data[] = $navItem;
    }
}
