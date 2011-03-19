<?php 

$current_key = self::option_weibo_token;
$message = '';
// $message = '<p><strong>'.__('Operate Success!',self::$class).'</strong></p>';
if(!empty($_POST))
{
	require_once (dirname (__FILE__) . '/lib/weibooauth.php');
	$o = get_option($current_key);
	$c = new WeiboClient( self::$wb_key , self::$wb_skey , $o['oauth_token'] , $o['oauth_token_secret'] );
	$rz = $c->update($_POST['message']);
	if(!empty($rz->error))
	{
		$message = '<p><strong>'.__('Operate aborted! You should renew author',self::$class).'</strong></p>';
	}
	else
	{
		$message = '<p><strong>'.__('Operate Success!',self::$class).'</strong></p>';
	}
}
?>
<?php self::message($message);	?>

<form action="" method="post"> 
	<table class="form-table">
	<tbody>
		<tr>
			<th><?php _e('message:',self::$class);?></th>
			<td><input type="text" name="message" style="width:500px;" value=""/></td>
		</tr>
	</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field(self::$class); ?>
		<input type="submit" name="submit" value="<?php _e('Submit',self::$class)?>" class="button-primary"/>
	</p>
</form>
