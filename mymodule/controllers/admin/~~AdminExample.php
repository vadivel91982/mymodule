<?php


class AdminControllerParentClassName extends ModuleAdminController
{
public function __construct()
{

// Enable bootstrap
$this->bootstrap = true;
// Call of the parent constructor method
parent::__construct();

}
}
