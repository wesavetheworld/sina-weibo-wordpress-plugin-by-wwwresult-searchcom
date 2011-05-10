<?php 
$message = '';

if(!empty($_POST))
{
	yh_plugin_register_deactivation_hook();
	$message = '<p><strong>'.__('Operate Success!',$this->pre).'</strong></p>';
}
?>

<?php $this->message($message);	?>
<form action="" method="post"> 
	<?php _e('click the submit button to reset all settings', $this->pre); ?>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Submit',$this->pre)?>" class="button-primary"/>
	</p>
</form>
