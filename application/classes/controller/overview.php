<?php

class Controller_Overview extends Controller_TemplateWrapper {
    
    public function action_index() {
        $this->template->title = 'Overview';
        $view = View::factory($this->viewPath.'overview');        
       
        
                
        $this->template->content = $view;
       
    }
    
}

?>
