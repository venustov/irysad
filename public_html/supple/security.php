<?php
class security
{
var $get;
var $post;
	function security()
	{
	$this->get=array();
	$this->post=array();
	}
	function get_decode()
	{
		foreach($_GET as $key=>$value)
		{
		$key=htmlspecialchars($key, ENT_QUOTES);
			if(is_string($value))
			{
			$value=htmlspecialchars($value, ENT_QUOTES);
			$this->get[$key]=$value;
			}
			else if(is_int($value))
			{
			$value=(int)$value;
			$this->get[$key]=$value;
			}
		}
	$_GET=array(); 
	} 
	function post_decode()
	{
		foreach($_POST as $key=>$value)
		{
		$key=htmlspecialchars($key, ENT_QUOTES);
			if(is_array($value))
			{
				foreach($value as $sub_key=>$sub_value)
				{
				$sub_key=htmlspecialchars($sub_key, ENT_QUOTES);
					if(is_string($sub_value))
					{
					$sub_value=htmlspecialchars($sub_value, ENT_QUOTES);
					}
					else if(is_int($sub_value))
					{
					$sub_value=(int)$sub_value;
					}
				$this->post[$key][$sub_key]=$sub_value;
				}
			}
			else if(is_string($value))
			{ 
			$sub_value=htmlspecialchars($sub_value, ENT_QUOTES);
			$this->post[$key]=$value;
			}
			else if(is_int($value))
			{
			$value=(int)$value;
			$this->post[$key]=$value;
			}
		}
	$_POST=array();
	}
}
?>