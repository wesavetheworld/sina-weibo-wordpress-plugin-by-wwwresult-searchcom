<?php 
$current_key = self::option_weibo_token;
$o = get_option( $current_key );
?>
	<table class="form-table">
		<tbody>		
<?php 
$i = 0;
if(is_array($o)):
$i++;
$oauth_token = $o['oauth_token'];
$oauth_token_secret = $o['oauth_token_secret'];
$user_id = $o['user_id'];
?>
<tr >
<th><label for="oauth_token"><?php _e('authorization token',self::$class)?></label></th>
<td>
<?php 
echo $oauth_token;
?>
</td>
</tr>
<tr >
<th><label for="oauth_token_secret"><?php _e('authorization token secret',self::$class)?></label></th>
<td>
<?php 
echo $oauth_token_secret;
?>
</td>
</tr>
<tr >
<th><label for="oauth_token_secret"><?php _e('user',self::$class)?></label></th>
<td>
<?php 
$link = 'http://t.sina.com.cn/'.$user_id;
?>
<a href="<?php echo $link?>" target="_blank"><?php echo $link?></a>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td></td>
</tr>
<?php 
endif;

if($i==0)
{
	_e('nothing yet',self::$class);
}
?>
		</tbody>
	</table>
