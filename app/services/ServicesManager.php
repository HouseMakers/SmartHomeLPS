<?php

namespace SmartHomeLPS\Services;
    
use Phalcon\Mvc\User\Component;

use SmartHomeLPS\Services\SmartHome\EmailAlertService;
use SmartHomeLPS\Services\SmartHome\SMSAlertService;

class ServicesManager extends Component
{
    public static function getAlertServices()
    {
        $alertServices = array();

        array_push($alertServices, new EmailAlertService());
        array_push($alertServices, new SMSAlertService());
        
        return $alertServices;
    }
}