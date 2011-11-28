<?php
function checkHost($value) {
    if($value == 'localhost') return true;
    elseif(preg_match('%^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$%', $value)) return true;
    else return false;
}