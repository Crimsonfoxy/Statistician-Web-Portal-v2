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
                'type' => 'MySQL',
                'connection' => array(
                    'hostname' => $this->request->post('hostname') .':'. $this->request->post('port'),
                    'database' => $this->request->post('database'),                 
                    'username' => $this->request->post('user'),
                    'password' => $this->request->post('password'),
                    'persistent' => FALSE
                ),
                'table_prefix' => $this->request->post('prefix'),
                'charset' => 'utf8',
                'caching' => FALSE,
                'profiling' => FALSE
            );
            
            try {
                // Check if db is correct
                Database::instance(NULL, $config)->connect();
                
                $this->session->set('database_config', $config);
                $this->session->set('datbase_post', $_POST);
                
                $this->session->set('active_step', 2);
                $this->request->redirect('install/step2');
            }
            catch(Exception $e) {
                $this->template->errors = array(__('Cannot connect'), $e);
            }
        }
        
        $data['data'] = ($this->request->post()) ? $this->request->post() : $this->session->get('datbase_post');
        $this->template->content = View::factory('install/step1', $data);
    }
    
    /*
     * Admin configuration
     */
    public function action_step2() {
        if($this->session->get('active_step') < 2) $this->request->redirect ('install/step'.$this->session->get('active_step'));
        
        
        $this->template->content = View::factory('install/step2');
    }
}

?>
