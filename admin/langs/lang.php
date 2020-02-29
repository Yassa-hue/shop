<?php


	function toArabic($phrase)
	{
		static $vocab = array(
			'page' => 'مرحبا', 
		);
		return $vocab[$phrase];
	}