<?php 
require_once (dirname (__FILE__) . '/lib/weibooauth.php');

$sina_weibo = $_POST['sina_weibo'];
$send_or_not = $sina_weibo['send_or_not'];
if(empty($send_or_not) || $send_or_not=='no')
{
	return '';
}

global $post;
$_post = $post;
$post = get_post($id);
$url =  home_url('?p='.$id);

$title = $post->post_title;
$link = $url;
$message = $sina_weibo['content'];
$search = array(
	'%title%',
	'%link%',
);
$replace = array(
	$title,
	$link,
);
$message = str_ireplace($search,$replace,$message);

$flag = '';
$o = get_option(self::option_weibo_token);
$t = $o;
$c = new WeiboClient( self::$wb_key , self::$wb_skey , $t['oauth_token'] , $t['oauth_token_secret']  );		
$rz = $c->update($message);
$post = $_post;