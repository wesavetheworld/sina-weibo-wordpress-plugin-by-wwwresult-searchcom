<?php 
$message = '<p><strong>'.__('Operate Success!',self::$class).'</strong></p>';
$message = '';
?>
<?php self::message($message);	?>
<form action="" method="post"> 
	<table class="form-table">
	<tbody>
		<tr>
			<th>label here:</th>
			<td>input here</td>
		</tr>
	</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Submit',self::$class)?>" class="button-primary"/>
	</p>
</form>
