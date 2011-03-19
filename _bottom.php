<div class="postbox-container" style="width:100%;">
	<div class="metabox-holder">	
		<div class="meta-box-sortables">
			<?php
			$content = get_option(self::$class.'_bottom');
			self::postbox(self::$class.'like', __('Something maybe you are interesting?',self::$class), $content);
			?>
		</div>
		<br/><br/><br/>
	</div>
</div>