<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_Install extends Controller_Template {
    public $template = 'index';
    public $session;
    public $lang;
    public $step;
    
    public function before() {
        parent::before();
        
        $this->session = Session::instance();
            
        $this->template->title   = 'Installation';
        $this->template->content = '';

        $this->template->styles = array();
        $this->template->scripts = array();
                 
    }
    
    
    public function action_start() {
        
    }
}

?>
