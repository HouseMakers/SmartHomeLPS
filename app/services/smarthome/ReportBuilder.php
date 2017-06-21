<?php

namespace SmartHomeLPS\Services\SmartHome;

use Phalcon\Mvc\User\Component;

abstract class ReportBuilder extends Component
{
    public abstract function makeReport();
    protected abstract function makeHeader();
    protected abstract function makeBody();
    protected abstract function makeFooter();
}