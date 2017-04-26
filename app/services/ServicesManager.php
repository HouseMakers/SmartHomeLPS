<?php

namespace SmartHomeLPS\Services;
    
use Phalcon\Mvc\User\Component;

use SmartHomeLPS\Services\SmartHome\EmailAlertService;

class ServicesManager extends Component
{
    public static function getAlertServices()
    {
        $alertServices = array();
        
        array_push($alertServices, new EmailAlertService());
        
        return $alertServices;
    }
}