<?php

namespace Chaos\Core\Util;

class StringUtil
{
	private function __construct () {}


	/**
	 * Converts a underscore string to camelcase
	 * @param string $value The string value to convert
	 * @param boolean $ucFirst Indicates whether to transform first letter to uppercase too or not
	 */
	public static function underscore2camelcase ($value, $ucFirst = false)
	{
		$string = preg_replace('/_+/', '_', $value);
		if ($ucFirst && preg_match('/^[a-z]/i', $value)) 
			$string = '_' . $value;

		return preg_replace_callback(
			'/_([a-z])/i', 
			function (array $matches) {return strtoupper($matches[1]);}, 
			$string
		);
	}


	/**
	 * Converts a camelcase string to underscore
	 * @param string $value The string value to convert
	 */
	public static function camelcase2underscore ($value)
	{
		$string = lcfirst($value);
		return preg_replace_callback(
			'/[A-Z]/', 
			function (array $matches) {return strtolower('_' . $matches[0]);}, 
			$string
		);
	}


	/**
	 * Converts a underscore string to camelcase
	 * @param string $value The string value to convert
	 * @param boolean $ucFirst Indicates whether to transform first letter to uppercase too or not
	 */
	public static function dash2camelcase ($value, $ucFirst = false)
	{
		$string = preg_replace('/\-+/', '-', $value);
		if ($ucFirst && preg_match('/^[a-z]/i', $value)) 
			$string = '-' . $value;

		return preg_replace_callback(
			'/-([a-z])/i', 
			function (array $matches) {return strtoupper($matches[1]);}, 
			$string
		);
	}


	/**
	 * Replaces uppercased letters with dash-prefixed lowercase letter
	 * @param string $value The string value to convert
	 */
	public static function camelcase2dash ($value) {
		$string = lcfirst($value);
		return preg_replace_callback('/[A-Z]/', function (array $matches) {
			return strtolower('-' . $matches[0]);
		}, $string);
	}
}