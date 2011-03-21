<?php
/*
Plugin Name: sina weibo wordpress plugin by www.result-search.com
Plugin URI: http://www.result-search.com/sty/wordpress-sina-t-plugin
Description: This plugin work for the Chinese hot twitter like website, t.sina.com.cn,if you interest in Chinese Marketing,you should use this plugin
Author: Lyman Lai(at www.result-search.com)
Version: 2.0
Author URI: http://www.result-search.com

*/

add_action("init",array("Sina_weibo","init"),1000,0);

class Sina_weibo
{
	const options_key = __CLASS__;
	const option_weibo_token = 'option_weibo_token';
	public static $wb_key = '2623609113';
	public static $wb_skey = '8152f1e08afd84047f9decd7c1e1f9e7';
	public static $is_vip = false;
	public static $class = __CLASS__;
	public static $homepage = 'http://www.yaha.me/go/?p=3';
	public static $pluginPageOnWp  = 'http://wordpress.org/extend/plugins/sina-weibo-wordpress-plugin-by-wwwresult-searchcom/';
	public static $rss_url = 'http://www.yaha.me/002/feed/';
	public static $title_arr = '';
	public static $func = '';
	public static $pageUrl = '';
	public static $url = '';
	public static $dir = '';

	
	function init()
	{
		self::$url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		self::$dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		// load localisation files
		$lang_dir = basename(dirname (__FILE__)).'/lang';
		load_plugin_textdomain(__CLASS__,false,$lang_dir);
		
		add_action( 'admin_menu', array(__CLASS__, 'RegisterAdminPage') );
		add_action( 'publish_post', array(__CLASS__, 'post_update_sina') );		
		
		add_filter('wp_mail_from',array(__CLASS__, 'admin_email'));
		self::notice_update();
		add_action('wp_dashboard_setup', array(__CLASS__,'widget_setup'));	
		add_action('admin_print_scripts', array(__CLASS__,'config_page_scripts'));
	}

	function config_page_scripts()
	{
		wp_enqueue_script('postbox');
		wp_enqueue_script('dashboard');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
	}
	
	/**
	 * Registers the plugin in the admin menu system
	 */
	function RegisterAdminPage() 
	{
		add_menu_page(
			__('Sina Weibo', __CLASS__), 		//page title
			__('Sina Weibo', __CLASS__),      		//menu title
			'administrator', 	//access level
			__CLASS__, 		//file
			array(__CLASS__,'show_html'),	//function
			'',					//icon url
			1					//position
		);
		self::$title_arr['_help'] = __('Help Information',self::$class);
		
		$sub_name = __('renew author', __CLASS__);
		$function_name = 'renew_author_link';
		self::add_sub_menu($sub_name,$function_name);

		$sub_name = __('author list', __CLASS__);
		$function_name = 'author_list';
		self::add_sub_menu($sub_name,$function_name);
		
		$sub_name = __('new message', __CLASS__);
		$function_name = 'new_message';
		self::add_sub_menu($sub_name,$function_name);
		
		$sub_name = __('reset settings', __CLASS__);
		$function_name = 'reset_all_setting';
		self::add_sub_menu($sub_name,$function_name);
		
		// $id, $title, $callback, $page, $context = 'advanced', $priority = 'default', $callback_args=null) 
		if(current_user_can('level_10'))
		{
			add_meta_box(
				__CLASS__.'_send_or_not_sina_weibo_message', //id
				__('Weibo',__CLASS__), //title
				array(__CLASS__,'sina_weibo_meta_box'), //callback
				'post', //page
				'normal',  //context
				'high'   //priority
			);
		}
	}
	
	function add_sub_menu($sub_name,$function_name)
	{
		add_submenu_page(
			__CLASS__, //parent
			$sub_name, //page title
			$sub_name, //menu title
			'administrator',  //access level
			__CLASS__.'_'.$function_name, //file,actually, just look at it like a token
			array(__CLASS__,'show_html')
		);
		self::$title_arr['_'.$function_name] = $sub_name;
	}
	function show_html()
	{
		$func = str_ireplace(__CLASS__,'',$_GET['page']);
		if(empty($func))
		{
			$func = '_help';
		}
		self::$func = $func;
		$title = self::$title_arr[$func];
		self::$pageUrl = get_admin_url().'admin.php?page='.self::$class.self::$func;
?>
		<div class="wrap">
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e('Sina Weibo',self::$class)?></h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<div id="<?php echo $func; ?>" class="postbox">
								<div class="handlediv" title="Click to toggle"><br /></div>
								<h3 class="hndle"><span><?php echo $title; ?></span></h3>
								<div class="inside" style="padding:10px;">
								<?php 
									$file = self::$dir.$func.'.php';
									include $file;
								?>
								</div>
							</div>
						<?php include self::$dir.'_bottom.php';?>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:20%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<?php
								self::plugin_like();
								self::plugin_support();
								self::news();
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
<?php			
	}
	
	function postbox($id, $title, $content) {
	?>
		<div id="<?php echo $id; ?>" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php echo $title; ?></span></h3>
			<div class="inside" style="padding:10px;">
				<?php echo $content; ?>
			</div>
		</div>
	<?php
	}
	
	function plugin_like() {
		$content = '<p>'.__('Why not do any or all of the following:',self::$class).'</p>';
		$content .= '<ul>';
		$content .= '<li><a href="'.self::$homepage.'">'.__('Link to it so other folks can find out about it.',self::$class).'</a></li>';
		$content .= '<li><a href="'.self::$pluginPageOnWp.'">'.__('Give it a good rating on WordPress.org.',self::$class).'</a></li>';
		$content .= '</ul>';
		self::postbox(self::$class.'like', __('Like this plugin?',self::$class), $content);
	}	
	
	function plugin_support() {
		$content = '<p>'.__('If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the',self::$class).' <a href="'.self::$homepage.'">'.__("Support forums",self::$class).'</a>.</p>';
		$content = '<p>'.__('If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the',self::$class).' <a href="'.self::$homepage.'">'.__("Support forums",self::$class).'</a>.</p>';
		self::postbox(self::$class.'support', __('Need support?',self::$class), $content);
	}

	function news() 
	{
			$content = get_option(self::$class.'_news');
			self::postbox('yaha-me', __('Latest news from yaha.me',self::$class), $content);
	}
	
	function notice_update()
	{
		//just tell plugin author who using the plugin, thanks that if you would not remove this code.
		if(get_option(__CLASS__.'_time')!= date('Y-m-d',time()) )
		{
			update_option(self::$class.'_bottom', file_get_contents('http://www.yaha.me/plg-news/') );
			@wp_mail('yaaahaaa.com@gmail.com', 'YH-user:'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].',Time:'.date('Y-m-d H:i:s',time()), '');
			
			//get news(blog rss)
			require_once(ABSPATH.WPINC.'/rss.php'); 
			ob_start();
			wp_widget_rss_output(self::$rss_url, array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0 ));
			$content = ob_get_contents();
			ob_end_clean();
			update_option(self::$class.'_news', $content );
			
			//update time flag
			update_option(__CLASS__.'_time', date('Y-m-d',time()) ) ;
		}
	}
	
	function message($message)
	{
		if(!empty($message))
		{
			echo '<div id="message" class="updated fade">'.$message.'</div>';
		}
	}
	//common function end
	
	//start custom function 
	function post_update_sina($id)
	{
		include self::$dir.'_post_update_sina.php';
    }
	
	function admin_email($email)
	{
		return get_bloginfo('admin_email');
	}
	
	function sina_weibo_meta_box($post)
	{
		$o = get_option(self::option_weibo_token);
		$formate = empty($o['formate']) ? '%title% %link%' : $o['formate'];
		
		$send = 'no';
		global $current_screen;
		if($current_screen->post_type=='post' && $current_screen->action=='add')
		{
			$send = 'yes';
		}
?>

		<p>
		<?php _e('Do you want to update to sina weibo:',__CLASS__) ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label>
			<input type="radio" name="sina_weibo[send_or_not]" value="yes" <?php if($send=='yes')echo 'checked="checked"';?>/>
			<?php _e('yes',__CLASS__) ?>
		</label>
		<label>
			<input type="radio" name="sina_weibo[send_or_not]" value="no" <?php if($send=='no')echo 'checked="checked"';?>/>
			<?php _e('no',__CLASS__) ?>
		</label>
		</p>
		<p>
			<label><?php _e('Formate:',__CLASS__) ?>
				<textarea cols="40" rows="2" name="sina_weibo[content]"><?php echo $formate?></textarea>
				<?php _e('Default Formate: %title% %link%',__CLASS__) ?>
			</label>
		</p>
		<p>
		<?php _e('Having trouble while using this plugin?, Just <a href="http://www.yaha.me/go/?p=3" target="_blank">click here</a> come to yaha.me to get support!',__CLASS__);?>
		</p>
<?php
	}
	
	function widget_setup() 
	{
		wp_add_dashboard_widget( __CLASS__.'_db_widget' , __('Something maybe you are interesting?',self::$class) , array(__CLASS__, 'db_widget'));
	}
	function db_widget()
	{
		$content = get_option(self::$class.'_bottom');
		echo $content;
	}
}