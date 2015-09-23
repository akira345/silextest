<?php
namespace Testapp\Controller;
use Testapp\Application;

Class TopController
{
	public function index(Application $app)
	{
		return "Hellow";
	}
}