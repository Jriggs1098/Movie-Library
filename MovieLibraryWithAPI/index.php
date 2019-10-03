<?php
/*
 * Author: Jack Riggs
 * Date: April 7, 2019
 * Name: index.php
 * Description: Homepage.
 */

//Load application settings
require_once ("application/config.php");

//Load autoloader
require_once ("vendor/autoload.php");

//load the dispatcher that dissects a request URL
new Dispatcher();