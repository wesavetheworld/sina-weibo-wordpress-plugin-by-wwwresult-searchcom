<?php 

$o = get_option(self::option_weibo_token);
$message = '';

$formate = $o['formate'];
if(empty($formate))
{
	$formate = '%title% %link%';
}

if(!empty($_POST['sina_weibo']))
{
	$sina_weibo = $_POST['sina_weibo'];
	$o['formate'] = $sina_weibo['formate'];
	update_option(self::option_weibo_token,$o);
	$message = '<p><strong>'.__('Operate Success!',self::$class).'</strong></p>';
}

self::message($message);	
?>
<p><?php _e('You must renew author first, after this you could using other function of this plugin',self::$class)?></p>

<form action="" method="post"> 
	<table class="form-table">
	<tbody>
		<tr>
			<th><?php _e('Default Formate:',self::$class)?></th>
			<td><input type="text" name="sina_weibo[formate]" value="<?php echo $formate?>" style="width:300px" /> <?php _e('%title% %link%',self::$class)?></td>
		</tr>
	</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Submit',self::$class)?>" class="button-primary"/>
	</p>
</form>
