<?php 
if(empty($_POST['post_2_weibo']))
{
	return '';
}

$comment_id = $args;
$comment_post_id = $_POST['comment_post_ID'];
if(!$comment_post_id)
{
	return;
}

$current_comment = get_comment($comment_id);
$current_post = get_post($comment_post_id);

$t = get_user_meta($current_comment->user_id, 'sina_open_token_array'.$this->key, 1);

if($t)
{
	$c = new WeiboClient( $this->key , $this->skey , $t['oauth_token'] , $t['oauth_token_secret']  );	
	
	$message = urlencode($current_comment->comment_content. ' '.$url =  home_url('?p='.$comment_post_id)."#comment-".$comment_id);			
	
	if( $_POST['comment_parent']!='0' && $weibo_id = get_comment_meta($_POST['comment_parent'], 'weibo_id', $single = 1)  )
	{
		$param = array(); 
        $param['id'] = $weibo_id; 
        $param['status'] = $message; 
        $param['is_comment'] = 3; 

        $rz = $c->oauth->post( 'http://api.t.sina.com.cn/statuses/repost.json' , $param  ); 
		if(empty($rz->ID))
		{
			$rz = $c->update($message);
		}
	}
	else
	{
		$rz = $c->update($message);
	}
	
	if(!empty($rz['id']))
	{
		update_comment_meta($comment_id, 'weibo_id', $rz['id'] );
	}
	else
	{
		// echo '('.__LINE__.')'.__FILE__."\n<br /><pre>"; var_dump($rz);echo "</pre>";exit();
	}
}
