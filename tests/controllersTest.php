<?php

use Silex\WebTestCase;

/**
 * ユニットテスト用クラス
 */
class controllersTest extends WebTestCase
{
    public function test_TOPページ表示確認()
    {
        //クライアント作成
        $client = $this->createClient();
        //リダイレクト有効化
        $client->followRedirects(true);
        //TOPページアクセス
        $crawler = $client->request('GET', '/');
        //レスポンス確認
        $this->assertTrue($client->getResponse()->isOk());
        //サイト内容チェック
        $this->assertContains('Default', $crawler->filter('body')->text());
    }
    public function test_Helloページ表示確認()
    {
        //チェック用パラメタ設定
        $str_name = "hoge";
        //クライアント作成
        $client = $this->createClient();
        //リダイレクト有効化
        $client->followRedirects(true);
        //helloページへアクセス
        $crawler = $client->request('GET', '/hello/'.$str_name);
        //レスポンス確認
        $this->assertTrue($client->getResponse()->isOk());
        //サイト内容チェック
        $this->assertContains('Hello '.$str_name, $crawler->filter('body')->text());
    }
    public function test_管理ページログイン成功テスト(){
        //クライアント作成
        $client = $this->createClient();
        //リダイレクト有効化
        $client->followRedirects(true);
        //CSRF用トークン取得
        $csrf_token = $this->app['csrf.token_manager']->getToken('authenticate');
        //管理ページログインテスト
        $crawler = $client->request('POST','/admin/login_check', array(
                'username'=>'admin','password'=>'test',
                '_csrf_token'=>$csrf_token
            )
        );
        //ステータスチェック
        $this->assertTrue($client->getResponse()->isOk());
        //管理者ページが表示されているかチェック
        $this->assertContains('Admin Page', $crawler->filter('body')->text());
    }
    public function test_管理ページログイン失敗テスト(){
        //クライアント作成
        $client = $this->createClient();
        //リダイレクト有効化
        $client->followRedirects(true);
        //CSRF用トークン取得
        $csrf_token = $this->app['csrf.token_manager']->getToken('authenticate');
        //管理ページログインテスト
        $crawler = $client->request('POST','/admin/login_check', array(
                'form' => array('username'=>'dummy','password'=>'dummy'),
                '_csrf_token'=>$csrf_token
            )
        );
        //ステータスチェック
        $this->assertTrue($client->getResponse()->isOk());
        //管理者ページが表示されているかチェック
        $this->assertContains('ログインできません', $crawler->filter('body')->text());
    }

    public function createApplication()
    {
        //アプリケーション起動
        require_once __DIR__ . './../vendor/autoload.php';
        $app = new Testapp\Application();
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
