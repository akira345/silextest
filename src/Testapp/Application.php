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
		//親クラスのコンストラクタを呼び出す。
		parent::__construct($values);
		//Monologを使う。
		$this->register(new \Silex\Provider\MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__.'/../../log/development.log',
		));
		//Twigを使う
		$this->register(new \Silex\Provider\TwigServiceProvider(), array(
		    'twig.path' => __DIR__.'/views',
		));
		//コントローラプロバイダを登録
		$this->register(new \Silex\Provider\ServiceControllerServiceProvider());
		$this->mount('', new ControllerProvider\FrontControllerProvider());
	}


}