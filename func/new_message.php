<?php 

$o = get_option($this->pre);

$message = '';
// $message = '<p><strong>'.__('Operate Success!',$this->pre).'</strong></p>';


if(!empty($_POST))
{
	$img = '';
	if(!empty($_FILES["image"]["tmp_name"]))
	{
		$upload = wp_upload_bits($_FILES["image"]["name"], null, file_get_contents($_FILES["image"]["tmp_name"]));
		if(!$upload['error'])
		{
			$img = $upload['url'];
		}
	}elseif(!empty($_POST['image']))
	{
		$img = $_POST['image'];
	}
	
	$user = wp_get_current_user();
	$user_id = get_user_meta($user->ID, 'open_id', 1);
	
	$t = get_user_meta($user->ID, 'sina_open_token_array'.$this->key, 1);

	
	$c = new WeiboClient( $this->key , $this->skey , $t['oauth_token'] , $t['oauth_token_secret'] );
	if(!empty($img))
	{
		$rz[$user_id] = $c->upload($_POST['message'],$img);
	}
	else
	{
		$rz[$user_id] = $c->update($_POST['message']);
	}
	
	if(is_array($rz))
	foreach( $rz as $user_id=>$val )
	{
		$screen_name = $user->display_name;
		if(!empty($val['error']))
		{
			$message .= '<p><strong>'.$screen_name.' '.$val['error'].'</strong></p>';
		}
		else
		{
			$sid = $val['id'];
			$url = "http://api.t.sina.com.cn/".$user_id."/statuses/".$sid;
			$message .= '<p><strong>'.$screen_name.' <a target="_blank" href="'.$url.'">'.$url.'</a></strong></p>';
		}
	}
}
?>
<?php $this->message($message);	?>

<form action="" method="post" enctype="multipart/form-data"> 
	<table class="form-table">
	<tbody>
		<tr>
			<th><?php _e('message:',$this->pre);?></th>
			<td><input type="text" name="message" style="width:500px;" value=""/></td>
		</tr>
		<tr>
			<th><?php _e('upload image:',$this->pre);?></th>
			<td>
				<input type="file" name="image" style="width:500px;" value=""/> <br />
				<?php _e('if empty, system would using remote image instead',$this->pre);?>
			</td>
		</tr>
		<tr>
			<th><?php _e('remote image:',$this->pre);?></th>
			<td>
				<input type="text" name="image" style="width:500px;" value=""/> <br />
				<?php _e('if upload image and remote image both empty, system would not upload image to weibo',$this->pre);?>
			</td>
		</tr>
		
	</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field($this->pre); ?>
		<input type="submit" name="submit" value="<?php _e('Submit',$this->pre)?>" class="button-primary"/>
	</p>
</form>

<?php 
$using_days = $this->get_using_days();
if($using_days>3)
{
?>
也许你想要有多个绑定的帐号同时发布微博的功能？ 点击查看<br />
<a href="http://www.yaha.me/002/wordpress-sina-t-plugin/unlimited" target="_blank">
<img src="http://ww2.sinaimg.cn/large/7cd57fe4jw1dh1v3r71xgj.jpg" width="400px" />
</a>

<br />
您可以在选项设置中设置关闭这个功能提示
<?php 
}
?>

