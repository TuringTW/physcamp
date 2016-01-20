<?php 
	if ( ! function_exists('css_url'))
	{
		function css_url($uri = '')
		{
		$CI =& get_instance();
		$css_string = "<link rel='stylesheet' type='text/css' href='".$CI->config->base_url("/style/css".$uri)."' media='all'>";
		return $css_string;
		}
	}
	if ( ! function_exists('js_url'))
	{
		function js_url($uri = '')
		{
		$CI =& get_instance();
		$js_string = "<script  type='text/javascript' src='".$CI->config->base_url("/style/js".$uri)."'></script>";
		return $js_string;
		}
	}
	if ( ! function_exists('img_url'))
	{
		function img_url($uri = '')
		{
		$CI =& get_instance();
		$string = $CI->config->base_url("/style/img".$uri);
		return $string;
		}
	}
	if ( ! function_exists('web_url'))
	{
		function web_url($uri = '')
		{
		$CI =& get_instance();
		$string = $CI->config->base_url("index.php".$uri);
		return $string;
		}
	}
	if ( ! function_exists('file_url'))
	{
		function file_url($uri = '')
		{
		$CI =& get_instance();
		$string = $CI->config->base_url("/style/file".$uri);
		return $string;
		}
	}
 ?>
