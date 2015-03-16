<?php
/* Factory Pattern */

class Validation
{	
	private function _check_is_numeric($number)
	{
		return is_numeric($number);
	}
	private function _check_is_integer($number)
	{
		$pattern = '/^[0-9]$/';
		return preg_match($pattern,$number);
	}
	private function _check_is_width($number,$minOfDigit,$maxOfDigit)
	{
		$pattern = '/^[0-9]{".$minOfDigit.",".$maxOfDigit."}$/';
		return preg_match($pattern,$number);
	}
	private function _check_is_mobileNo($number)
	{
		$pattern = '/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/';
		return preg_match($pattern,$number);
	}
	private function _check_is_dateTime($number)
	{
		$pattern = '/^([2][0]\d{2}\/([0]\d|[1][0-2])\/([0-2]\d|[3][0-1]))$|^([2][0]\d{2}\/([0]\d|[1][0-2])\/([0-2]\d|[3][0-1])\s([0-1]\d|[2][0-3])\:[0-5]\d\:[0-5]\d)$/';
		return preg_match($pattern,$number);
	}
	public static function check_number($number,$dataType='int',$minOfDigit=0,$maxOfDigit=0)
	{
		if($this->_check_is_numeric($number))
		{
			if($dataType=='int' & $this->_check_is_integer($number))
			{
				if($minOfDigit>=0 && $maxOfDigit>$minOfDigit)
					 return true;
				else
					return $this->_check_is_width($number,$minOfDigit,$maxOfDigit);
			}
			else if($dataType=='mobile')
			{
					return $this->_check_is_mobileNo($number);
			}
			else if($dataType=='dateTime')
			{
					return $this->_check_is_dateTime($number);
			}
		}
	}
}