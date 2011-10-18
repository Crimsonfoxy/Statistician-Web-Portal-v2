<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_TemplateWrapper extends Controller_Template {
    public $template = 'templates/default';
    
    protected final $cssPath = 'application/views/templates/css/';
    protected $titlePre = 'Statistican v2 :: ';
    protected final $viewPath = 'pages/default/';   
        
    private $sObj = null;

    public function before() {
        parent::before();
        
        if ($this->auto_render) {
            $sObj = new Statistican();
            
            $this->template->title   = '';
            $this->template->content = '';
            $this->template->version = $sObj->getVersion();
            
            $this->template->styles = array();
            $this->template->scripts = array();
      }           
    }
    
    
    public function after() {
        
        $styles = array(
            $this->cssPath.'default.css' => 'screen'
        );
        
        $scripts = array();
        
        $this->template->styles = array_merge( $this->template->styles, $styles );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        
        $this->template->title = $this->titlePre.$this->template->title;
        
        parent::after();
    }
}

?>