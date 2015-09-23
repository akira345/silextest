<?php

namespace Testapp;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

Class Application extends \Silex\Application
{
	public function __construct(array $values = array())
	{
		parent::__construct($values);

		$this->register(new \Silex\Provider\MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__.'/../../log/development.log',
		));

		$this->register(new \Silex\Provider\ServiceControllerServiceProvider());
		$this->mount('', new ControllerProvider\FrontControllerProvider());
	}


}