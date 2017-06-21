<?php

namespace SmartHomeLPS\Services;
    
use Phalcon\Mvc\User\Component;

class ServicesManager extends Component
{
    public function getAlertServices()
    {
        $services = array();
        
        $alertServices = $this->config->smarthome->alert->services;
        foreach($alertServices as $alertService) {
            array_push($services, new $alertService());
        }
        
        return $services;
    }
    
    public function getCentralService()
    {
        $centralService = $this->config->smarthome->central->service;
        
        return new $centralService();
    }
}