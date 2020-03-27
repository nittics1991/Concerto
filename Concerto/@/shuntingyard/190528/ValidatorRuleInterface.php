<?php

namespace Concerto\Validator;

interface ValidatorRuleInterface
{
	/**
	* 	validate
	*
	* 	@param mixed $value
	* 	@return bool
	**/
	public function validate($value) :bool;
}
