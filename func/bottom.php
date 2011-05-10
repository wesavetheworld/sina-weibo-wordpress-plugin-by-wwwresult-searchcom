<div class="postbox-container" style="width:100%;">
	<div class="metabox-holder">	
		<div class="meta-box-sortables">
			<?php
			$content = get_option($this->pre.'bottom');
			if(empty($content))
			{
				$content = file_get_contents($this->root_dir.'plg-news/bottom.php');
			}
			$this->postbox($this->pre.'like', __('Something maybe you are interesting?',$this->pre), $content);
			?>
		</div>
		<br/><br/><br/>
	</div>
</div>