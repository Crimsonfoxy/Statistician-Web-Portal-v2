<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_Install extends Controller_Template {
    public $template = 'index';
    public $session;
    public $lang;
    public $active_step;
    
    public function before() {
        parent::before();
        
        $this->session = Session::instance();        
        $this->active_step = $this->session->get('active_step', 0);
        I18n::load($this->request->param('language', $this->session->get('language', 'en')));
        
        
        $this->template->title   = __('Installation of');
        $this->template->content = '';
        $this->template->errors = array();
        
        $this->lang = array('en' => 'English');
    }
    
    /*
     * Langauge
     */
    public function action_start() {
        if($this->request->post()) {
            $this->session->set('language', $this->request->post('language'));
            $this->session->set('active_step', 1);
            
            $this->request->redirect('install/step1');
        }
        
        $this->template->content = View::factory('install/start', array('langs' => $this->lang));
    }
    
    /*
     * Database
     */
    public function action_step1() {
        if($this->session->get('active_step') < 1) $this->request->redirect ('install/start');
        
        if($this->request->post()) {
            $config = array(
                'type' => $this->request->post('driver'),
                'connection' => array(
                    'hostname' => $this->request->post('hostname') . $this->request->post('port'),
                    'database' => $this->request->post('database_name'),                 
                    'username' => $this->request->post('user'),
                    'password' => $this->request->post('password'),
                    'persistent' => FALSE
                ),
                'table_prefix' => $this->request->post('prefix'),
                'charset' => 'utf8',
                'caching' => FALSE,
                'profiling' => FALSE
            );
            
            
            $this->session->set('database_config', $config);
        }
        
        $this->template->content = View::factory('install/step1');
    }
}

?>
