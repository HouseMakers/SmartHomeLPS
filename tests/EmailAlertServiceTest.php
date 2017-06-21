<?php

namespace SmartHomeLPS\Tests;

/**
 * Class UnitTest
 */
class EmailAlertServiceTest extends UnitTestCase {

    public function testShouldSendMail() {
        $this->setUp();
        $modelsManager = $this->di->get("modelsManager");
    }
}