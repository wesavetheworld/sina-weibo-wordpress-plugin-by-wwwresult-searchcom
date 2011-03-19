<?php 
$message = '';

if(!empty($_POST))
{
	update_option(self::option_weibo_token,array());
	$message = '<p><strong>'.__('Operate Success!',self::$class).'</strong></p>';
}
?>

<?php self::message($message);	?>
<form action="" method="post"> 
	<?php _e('click the submit button to reset all settings', self::$class); ?>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Submit',self::$class)?>" class="button-primary"/>
	</p>
</form>
