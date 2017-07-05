<?php

namespace Testapp;

use Silex\Provider\CsrfServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Sorien\Provider\PimpleDumpProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Translation\Loader\YamlFileLoader;

Class Application extends \Silex\Application
{
    public function __construct(array $values = array())
    {
        //親クラスのコンストラクタを呼び出す。
        parent::__construct($values);
        //PHPStorm用
        $this->register(new PimpleDumpProvider());
        //Sessionサービスを使う
        $this->register(new \Silex\Provider\SessionServiceProvider());
        //Formサービスを使う
        $this->register(new \Silex\Provider\FormServiceProvider());
        //セキュリティサービスを使う
        $this->register(new \Silex\Provider\SecurityServiceProvider());
        //認証サービスを使う
        $this->register(new \Silex\Provider\RememberMeServiceProvider());
        //バリデータサービスを使う
        $this->register(new \Silex\Provider\ValidatorServiceProvider());
        //Monologを使う。
        $this->register(new \Silex\Provider\MonologServiceProvider(), array(
            'monolog.logfile' => __DIR__.'/../../log/development.log',
        ));
        //Translationサービスを使う
        $this->register(new TranslationServiceProvider(), [
            'locale_fallback' => array('ja'),
        ]);
        $this['translator'] = $this->extend('translator', function($translator, $app) {
            $translator->addLoader('yaml', new YamlFileLoader());
            $translator->addResource('yaml', __DIR__.'/locale/msg.ja.yml', 'ja');
            return $translator;
        });
        //Twigを使う
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__.'/views',
        ));
        //Localeサービスを追加
        $this->register(new LocaleServiceProvider(), array(
            'locale' => 'ja',
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
            'admin' => array(   //adminという名前をつけただけ
                'pattern' => '^/admin',     //認証するURL
                'form' => array(
                    'login_path' => '/admin/login', //ログインフォームパス
                    'check_path' => '/admin/login_check',
                    'default_target_path' => '/admin',      //いきなりログインパスを叩かれた場合のデフォルトページ
                    'always_use_default_target_path' => false,      //常にデフォルトページにリダイレクトする
                    'with_csrf' => true,        //csrf対策を有効化
                    'username_parameter' => 'form[username]',   //form builderを使用すると、form[]な形でセットされる
                    'password_parameter' => 'form[password]',
                ),
                'logout' => array(
                    'logout_path' => '/admin/logout',   //ログアウトするパス
                    'target' => '/'     //ログアウト後飛ばすパス
                ),
                'users' => array(       //認証ユーザデータ。サンプルなので直書き
                    'admin' => array(
                        'ROLE_ADMIN',   //必ずロールが必要。ROLE_からはじめないといけない
                        'test',
                    ),
                ),
                'anonymous' => false,       //匿名アクセスを許可しない
            ),
        );
        //パスワードのカスタムエンコードを有効化し、プレインテキストを設定
        $this['security.default_encoder'] = function ($app) {
          // Plain text (e.g. for debugging)
          return new PlaintextPasswordEncoder();
        };
        //ロールの設定
        $this['security.access_rules'] = array(
            array('^/admin/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/admin', 'ROLE_ADMIN'),
        );
        //CSRF設定
        $this->register(new CsrfServiceProvider());
        //コントローラプロバイダを登録
        $this->register(new \Silex\Provider\ServiceControllerServiceProvider());
        $this->mount('', new ControllerProvider\FrontControllerProvider());

        $app = $this;
    }
}
