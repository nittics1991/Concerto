<?php

namespace Concerto\@\Validator\test;

use PHPUnit\Framework\TestCase;
use Concerto\@\Validator\RuleLexicer;

class RuleLexicerTest extends TestCase
{
	/**
	* @test
	**/
	public function analyzeSuccessProvider()
	{
		return [
			['gt:3&int+lt:0&float^!text'],
			
		];
	}
	
	/**
	* @test
	* @dataProvider analyzeSuccessProvider
	**/
	public function analyzeSuccess($ruleset)
	{
		//$this->markTestIncomplete();
		
		$obj = new RuleLexicer();
		$expect = $obj->analyze($ruleset);
		
		var_dump($expect);
		
		
		
		
	}
	
	
	
}
