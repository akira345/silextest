<?php

namespace Testapp;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;

Class Application extends \Silex\Application
{
	public function __construct(array $values = array())
	{
		//親クラスのコンストラクタを呼び出す。
		parent::__construct($values);
		//Sessionサービスを使う
		$this->register(new \Silex\Provider\SessionServiceProvider());
		//Formサービスを使う
		$this->register(new \Silex\Provider\FormServiceProvider());
		//セキュリティサービスを使う
		$this->register(new \Silex\Provider\SecurityServiceProvider());
		//認証サービスを使う
		$this->register(new \Silex\Provider\RememberMeServiceProvider());
		//URLジェネレータサービスを使う
		$this->register(new \Silex\Provider\UrlGeneratorServiceProvider());
		//バリデータサービスを使う
		$this->register(new \Silex\Provider\ValidatorServiceProvider());
		//Monologを使う。
		$this->register(new \Silex\Provider\MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__.'/../../log/development.log',
		));
		//Twigを使う
		$this->register(new \Silex\Provider\TwigServiceProvider(), array(
		    'twig.path' => __DIR__.'/views',
		));
		//エラーハンドリング
		$this->error(function (\Exception $e, $code) {
		    switch ($code) {
		        case 404:
		            $message = 'The requested page could not be found.';
		            break;
		        default:
		            $message = 'We are sorry, but something went terribly wrong.';
		    }
		    return new Response($message);
		});
		//セキュリティファイヤーウォールの設定
		$this['security.firewalls'] = array(
			//ログインフォームは誰でもアクセスできるように設定
			'login' => array(
				'pattern' => '^/admin/login$',
				'anonymous' => true,
			),
			//管理ページ用ファイヤーウォールルール
			'admin' => array(	//adminという名前をつけただけ
				'pattern' => '^/admin',		//認証するURL
				'form' => array(
					'login_path' => '/admin/login',	//ログインフォームパス
					'check_path' => '/admin/login_check',
					'default_target_path' => '/admin',		//いきなりログインパスを叩かれた場合のデフォルトページ
					'always_use_default_target_path' => false,		//常にデフォルトページにリダイレクトする
					'with_csrf' => true,		//csrf対策を有効化
				),
				'logout' => array(
					'logout_path' => '/admin/logout',	//ログアウトするパス
					'target' => '/'		//ログアウト後飛ばすパス
				),
				'users' => array(		//認証ユーザデータ。サンプルなので直書き
					'admin' => array(
						'ROLE_ADMIN',	//必ずロールが必要。ROLE_からはじめないといけない
						'test',
					),
				),
				'anonymous' => false,		//匿名アクセスを許可しない
			),
		);
		$app = $this;
		$this['security.encoder.digest'] = $this->share(function ($app) {
			return new PlaintextPasswordEncoder();
		});
		//ロールの設定
		$this['security.access_rules'] = array(
			array('^/admin/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
			array('^/admin', 'ROLE_ADMIN'),
		);
		//コントローラプロバイダを登録
		$this->register(new \Silex\Provider\ServiceControllerServiceProvider());
		$this->mount('', new ControllerProvider\FrontControllerProvider());
	}


}