<?php 

$id = $args;

$sina_weibo = $_POST['sina_weibo'];
$send_or_not = $sina_weibo['send_or_not'];
if(empty($send_or_not) || $send_or_not=='no' || $_POST['wp-preview']=='dopreview')
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
$o = get_option($this->pre);


$user = wp_get_current_user();
$user_id = $user->ID;
$t = get_user_meta($user_id, 'sina_open_token_array'.$this->key,1);
$flag = 'has post to server';
$c = new WeiboClient( $this->key , $this->skey , $t['oauth_token'] , $t['oauth_token_secret']  );		

$img = '';
if(!empty($_POST['sina_weibo_url_image']))
{
	$img = $_POST['sina_weibo_url_image'];
}		

if(!empty($img))
{
	$rz = $c->upload($message,$img);
}
else
{
	$rz = $c->update($message);
}

if(empty($rz['id']))
{
	echo '('.__LINE__.')'.__FILE__."\n<br /><pre>"; var_dump($rz);echo "</pre>";exit();
}
$post = $_post;