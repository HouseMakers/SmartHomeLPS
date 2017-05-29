<?php

namespace SmartHomeLPS\Services\SmartHome;
    
use Phalcon\Mvc\User\Component;

abstract class AlertService extends Component
{
    public abstract function alert($alert);
}