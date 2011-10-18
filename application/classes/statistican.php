<?php

class Statistican {
    private $version;
    
    public function __construct() {
        $this->version = '2.0b1';
    }
    
    /**
     * Returns the version number of the current statistican version
     * @return string Version number
     */
    public function getVersion() {
        
        return $this->version;
    }
}

?>
