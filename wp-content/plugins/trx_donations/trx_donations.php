<?php
/*
Plugin Name:	ThemeREX Donations
Plugin URI:		http://themerex.net
Description:	Manage donation causes 
Version:		1.0
Author:			ThemeREX
Author URI:		http://themerex.net
*/

if (!function_exists('themerex_plugins_donations_init')) {
	function themerex_plugins_donations_init() {
		THEMEREX_Donations::get_instance();
	}
	add_action( 'init', 'themerex_plugins_donations_init');
}


require_once 'includes/plugin.debug.php';
require_once 'includes/plugin.files.php';
require_once 'includes/plugin.wp.php';
require_once 'includes/plugin.html.php';


if (!class_exists('THEMEREX_Donations')) {
	class THEMEREX_Donations {
	
		const POST_TYPE = 'donation';					// Post type for plugin
		const TAXONOMY = 'donation_category';			// Taxonomy for plugin
	    const OPTIONS_NAME = 'trx_donations_options';	// Name of the wp_options key to store plugin options
	
		static $instance = null;						// Self instance reference
		
		var $options = array(							// Plugin options
			// PayPal settings
			'pp_account' => 'you@youremail.com',		// E-mail from PayPal account
			'pp_sandbox' => true,						// Use sandbox mode to testing PayPal transactions
			'pp_currency' => 'USD',						// Currency code for PayPal
			'pp_amount' => 5,							// Default amount for PayPal forms
			'pp_in_new_tab' => false,					// Open PayPal page in the new tab
			// Common settings
			'blog_style' => 'excerpt',					// excerpt - Post's style in the donations archive
			'blog_columns' => 1,						// Columns number for the donations archive
			'max_supporters_to_show' => 10,				// Max number of supporters to show in the single donation post
			'share' => array(							// Social share
				'twitter' => '1',
				'facebook' => '1'
			),
			// Theme specific settings
			'columns_wrap_class' => '',					// Class name for columns wrapper, used in the  current theme. If empty - use internal class
			'column_class' => ''						// Class name (mask) for single column, used in the current theme.
														// For example: column-$1_$2, where $1 - column width, $2 - total columns: column-1_4, column-2_3, etc.
														// If empty - use internal class
		);

		private $blog_styles = array(
			'excerpt' => 'Excerpt'
		);

		public $currency_codes = array(
			'AUD' => 'Australian Dollar',
			'BRL' => 'Brazilian Real',
			'CAD' => 'Canadian Dollar',
			'CZK' => 'Czech Koruna',
			'DKK' => 'Danish Krone',
			'EUR' => 'Euro (&euro;)',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungarian Forint',
			'ILS' => 'Israeli New Shekel',
			'JPY' => 'Yen (&yen;)',
			'MYR' => 'Malaysian Ringgit',
			'MXN' => 'Mexican Peso',
			'NOK' => 'Norwegian Krone',
			'NZD' => 'New Zealand Dollar',
			'PHP' => 'Philippine Peso',
			'PLN' => 'Polish Zloty',
			'GBP' => 'Pounds Sterling (&pound;)',
			'RUB' => 'Russian Ruble',
			'SGD' => 'Singapore Dollar',
			'SEK' => 'Swedish Krona',
			'CHF' => 'Swiss Franc',
			'TWD' => 'Taiwan New Dollar',
			'THB' => 'Thai Baht',
			'TRY' => 'Turkish Lira',
			'USD' => 'U.S. Dollar ($)'
		);
	
		private $socials_share = array(
			'blogger' =>		'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}',
			'bobrdobr' =>		'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}',
			'delicious' =>		'http://delicious.com/save?url={link}&title={title}&note={descr}',
			'designbump' =>		'http://designbump.com/node/add/drigg/?url={link}&title={title}',
			'designfloat' =>	'http://www.designfloat.com/submit.php?url={link}',
			'digg' =>			'http://digg.com/submit?url={link}',
			'evernote' =>		'https://www.evernote.com/clip.action?url={link}&title={title}',
			'facebook' =>		'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}',
			'friendfeed' =>		'http://www.friendfeed.com/share?title={title} - {link}',
			'google' =>			'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}',
			'gplus' => 			'https://plus.google.com/share?url={link}', 
			'identi' => 		'http://identi.ca/notice/new?status_textarea={title} - {link}', 
			'juick' => 			'http://www.juick.com/post?body={title} - {link}',
			'linkedin' => 		'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}', 
			'liveinternet' =>	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}',
			'livejournal' =>	'http://www.livejournal.com/update.bml?event={link}&subject={title}',
			'mail' =>			'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}',
			'memori' =>			'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}', 
			'mister-wong' =>	'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}', 
			'mixx' =>			'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}', 
			'moykrug' =>		'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}',
			'myspace' =>		'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}', 
			'newsvine' =>		'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}',
			'odnoklassniki' =>	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}', 
			'pikabu' =>			'http://pikabu.ru/add_story.php?story_url={link}',
			'pinterest' =>		'http://pinterest.com/pin/create/button/?url={link}&media={image}&description={title}',
			'posterous' =>		'http://posterous.com/share?linkto={link}&title={title}',
			'postila' =>		'http://postila.ru/publish/?url={link}&agregator=themerex',
			'reddit' =>			'http://reddit.com/submit?url={link}&title={title}', 
			'rutvit' =>			'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}', 
			'stumbleupon' =>	'http://www.stumbleupon.com/submit?url={link}&title={title}', 
			'surfingbird' =>	'http://surfingbird.ru/share?url={link}', 
			'technorati' =>		'http://technorati.com/faves?add={link}&title={title}', 
			'tumblr' =>			'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}', 
			'twitter' =>		'https://twitter.com/intent/tweet?text={title}&url={link}',
			'vk' =>				'http://vk.com/share.php?url={link}&title={title}&description={descr}',
			'vk2' =>			'http://vk.com/share.php?url={link}&title={title}&description={descr}',
			'webdiscover' =>	'http://webdiscover.ru/share.php?url={link}',
			'yahoo' =>			'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}',
			'yandex' =>			'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}',
			'ya' =>				'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}',
			'yosmi' =>			'http://yosmi.ru/index.php?do=share&url={link}'
		);

		// Create itself and return reference on just created object
		public static function get_instance() {
			if (self::$instance === null) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	
		function __construct() {
	
			// Set image sizes
			add_image_size( 'thumb-med', 480, 270, true );

			// Setup actions handlers
			if (is_admin()) {
	
				// Admin
				add_action('admin_menu',			array($this, 'admin_menu_item'));
				add_action('admin_enqueue_scripts',	array($this, 'admin_load_scripts'));
				add_action('add_meta_boxes',		array($this, 'admin_add_meta_box'));
				add_action('save_post', 			array($this, 'admin_save_meta_box'));

				// Extra columns for donations list
				add_filter('manage_edit-donation_columns',			array($this, 'admin_add_list_columns'), 9);
				add_filter('manage_donation_posts_custom_column',	array($this, 'admin_fill_list_columns'), 9, 2);

				// AJAX: Send donation form data
				add_action('wp_ajax_send_donations_form',			array($this, 'sc_donations_form_send'));
				add_action('wp_ajax_nopriv_send_donations_form',	array($this, 'sc_donations_form_send'));
	
			} else {
	
				// Frontend
				add_action('wp_enqueue_scripts',	array($this, 'load_scripts'));

				// Replace standard templates
				add_filter('single_template',		array($this, 'get_single_template'));
				add_filter('archive_template',		array($this, 'get_archive_template'));
				add_filter('taxonomy_template',		array($this, 'get_taxonomy_template'));

				// Add plugin shortcodes
				add_shortcode('trx_donations_form',	array($this, 'sc_donations_form'));
				add_shortcode('trx_donations_list',	array($this, 'sc_donations_list'));

			}
	
			// Create post type 'Donations'
			register_post_type( self::POST_TYPE, array(
				'label'               => __( 'Donations', 'themerex' ),
				'description'         => __( 'Donation causes', 'themerex' ),
				'labels'              => array(
					'name'                => _x( 'Donations', 'Post Type General Name', 'themerex' ),
					'singular_name'       => _x( 'Donation', 'Post Type Singular Name', 'themerex' ),
					'menu_name'           => __( 'Donations', 'themerex' ),
					'parent_item_colon'   => __( 'Parent Item:', 'themerex' ),
					'all_items'           => __( 'All Donations', 'themerex' ),
					'view_item'           => __( 'View Donation', 'themerex' ),
					'add_new_item'        => __( 'Add New Donation', 'themerex' ),
					'add_new'             => __( 'Add New', 'themerex' ),
					'edit_item'           => __( 'Edit Donation', 'themerex' ),
					'update_item'         => __( 'Update Donation', 'themerex' ),
					'search_items'        => __( 'Search Donations', 'themerex' ),
					'not_found'           => __( 'Donation not found', 'themerex' ),
					'not_found_in_trash'  => __( 'Donation not found in Trash', 'themerex' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments'),	//'custom-fields'
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-money',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '51.1',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'post',
				'rewrite'             => true
				)
			);
				
			// Prepare taxonomy for donations
			register_taxonomy( self::TAXONOMY, self::POST_TYPE, array(
					'labels'            => array(
						'name'              => _x( 'Donations Category', 'taxonomy general name', 'themerex' ),
						'singular_name'     => _x( 'Category', 'taxonomy singular name', 'themerex' ),
						'search_items'      => __( 'Search Categories', 'themerex' ),
						'all_items'         => __( 'All Categories', 'themerex' ),
						'parent_item'       => __( 'Parent Category', 'themerex' ),
						'parent_item_colon' => __( 'Parent Category:', 'themerex' ),
						'edit_item'         => __( 'Edit Category', 'themerex' ),
						'update_item'       => __( 'Update Category', 'themerex' ),
						'add_new_item'      => __( 'Add New Category', 'themerex' ),
						'new_item_name'     => __( 'New Category Name', 'themerex' ),
						'menu_name'         => __( 'Categories', 'themerex' ),
					),
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => self::TAXONOMY ),
					)
				);

			// Init properties
			if ( ($options = get_option('trx_donations_options', false))!==false)
				$this->options = $options;

			// If this is IPN check call
			if (isset($_REQUEST['trx_donations_pp_answer'])) {

				if (substr($_REQUEST['trx_donations_pp_answer'], 0, 6) == "notify") {

					if (!$this->ipn_validate2()) {
						// IPN validation failed. Email the admin to notify this event.
						$admin_email = get_bloginfo('admin_email');
						$subject = __('IPN validation failed for a payment', 'themerex');
						$body = __("This is a notification email from the TRX Donations plugin letting you know that a payment verification failed.", 'themerex')
							. "\n\n"
							. __("Please check your paypal account to make sure you received the correct amount in your account before proceeding", 'themerex');
						//wp_mail($admin_email, $subject, $body);
					}
					exit;

				} else if (substr($_REQUEST['trx_donations_pp_answer'], 0, 6)=='error') {

					$error = __('Operation failed! Please, try again later!', 'themerex');

				} else if (substr($_REQUEST['trx_donations_pp_answer'], 0, 7)=='success')	{

					if (!empty($_POST['payment_status']) && $_POST['payment_status']=='Completed' && !empty($_POST['item_number']) && (int) $_POST['item_number'] > 0) {
						// Get data from POST
						$post_id = !empty($_POST['item_number']) ? (int) $_POST['item_number'] : get_the_ID();
						$amount = !empty($_POST['payment_gross']) ? (float) $_POST['payment_gross'] : 0;
						$fee = !empty($_POST['payment_fee']) ? (float) $_POST['payment_fee'] : 0;
						$pp_name = (!empty($_POST['last_name']) ? stripslashes($_POST['last_name']) : '')
								. (!empty($_POST['first_name']) ? " ".stripslashes($_POST['first_name']) : '');
						$name = $site = '';
						$email = !empty($_POST['payer_email']) ? $_POST['payer_email'] : '';
						$show_in_rating = '0';
						if (!empty($_POST['option_selection1'])) {
							$info = explode('|', $_POST['option_selection1']);
							foreach ($info as $v) {
								$v = explode('=', $v);
								if (in_array($v[0], array('name', 'show_in_rating', 'site')))
									$$v[0] = $v[1];
							}
						}
						$msg = (!empty($_POST['option_selection2']) ? stripslashes($_POST['option_selection2']) : '')
								. (!empty($_POST['memo']) ? "\n".stripslashes($_POST['memo']) : '');
						// Add to supporters list
						$this->add_to_supporters($post_id, array(
							'email' => $email,
							'name' => $name,
							'message' => $msg,
							'amount' => $amount,
							'site' => $site,
							'show_in_rating' => $show_in_rating
							)
						);
					}
					
				}
			}
		}






	
	
	
		//-----------------------------------------------------------------------------------
		// Admin Interface
		//-----------------------------------------------------------------------------------
		function admin_menu_item() {
	
			// In this case menu item is add in admin menu
			add_options_page(__('ThemeREX Donations', 'themerex'), __('ThemeREX Donations', 'themerex'), 'manage_options', 'trx_donations_settings', array($this, 'admin_settings'));
		}
		
		
		// Load required styles and scripts
		function admin_load_scripts() {
			global $post_type;
			if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_donations_settings' || $post_type==self::POST_TYPE && (strpos($_SERVER['REQUEST_URI'], 'post-new.php')!==false || strpos($_SERVER['REQUEST_URI'], 'post.php')!==false)) {
				themerex_plugins_enqueue_style ('themerex-donations-style',  themerex_plugins_get_file_url( 'trx_donations_admin.css' ), array(), null);
				themerex_plugins_enqueue_script('themerex-donations-script', themerex_plugins_get_file_url( 'trx_donations_admin.js' ), array('jquery'), null, true);
			}
		}
		
		
		// Build the Settings page
		function admin_settings() {
			$error = $success = '';
			if (isset($_POST['trx_donations_settings_nonce'])) {
				do {
					if ( !wp_verify_nonce( $_POST['trx_donations_settings_nonce'], get_admin_url() ) ) {
						$error = __('Invalid security code! Options not saved. Please, refresh page and try again.', 'themerex');
						break;
					}
					
					$this->options['pp_account'] = trim(themerex_plugins_get_value_gp('pp_account'));
					$this->options['pp_sandbox'] = (int) themerex_plugins_get_value_gp('pp_sandbox', 0) > 0;
					$this->options['pp_in_new_tab'] = (int) themerex_plugins_get_value_gp('pp_in_new_tab', 0) > 0;
					$this->options['pp_amount'] = (float) themerex_plugins_get_value_gp('pp_amount');
					$this->options['pp_currency'] = themerex_plugins_get_value_gp('pp_currency');
					$this->options['blog_style'] = themerex_plugins_get_value_gp('blog_style');
					$this->options['blog_columns'] = max(1, (int) themerex_plugins_get_value_gp('blog_columns'));
					$this->options['max_supporters_to_show'] = max(0, (int) themerex_plugins_get_value_gp('max_supporters_to_show'));
					$this->options['share'] = array();
					foreach ($this->socials_share as $code=>$link) {
						if ( !empty($_POST['share'][$code]) )
							$this->options['share'][$code] = 1;
					}
					$this->options['columns_wrap_class'] = trim(themerex_plugins_get_value_gp('columns_wrap_class'));
					$this->options['column_class'] = trim(themerex_plugins_get_value_gp('column_class'));
				} while (false);
				$success = __('Options saved!', 'themerex');
				update_option('trx_donations_options', $this->options);
			}
			?>


			<div class="trx_donations_options">

				<h2 class="trx_donations_options_title"><?php _e('Donations settings', 'themerex'); ?></h2>
				
				<?php if (!empty($error)) { ?>
					<div class="error"><p><?php echo trim($error); ?></p></div>
				<?php } else if (!empty($success)) { ?>
					<div class="updated"><p><?php echo trim($success); ?></p></div>
				<?php } ?>

				<form action="<?php echo esc_url(menu_page_url('trx_donations_settings', false)); ?>" method="post">

					<input type="hidden" name="trx_donations_settings_nonce" value="<?php echo esc_attr(wp_create_nonce(get_admin_url())); ?>">

					<div class="trx_donations_options_section trx_donations_options_section_shortcodes">

						<h3 class="trx_donations_options_section_title"><?php _e('Shortcodes', 'themerex'); ?></h3>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Insert Donation form into post/page', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" readonly name="sc_form" value="[trx_donations_form]">
								<div class="trx_donations_options_description"><?php _e('Use this shortcode to insert donation form into post/page content.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Insert list of donations into post/page', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" readonly name="sc_form" value="[trx_donations_list cat='category_slug_or_id' count='3' columns='3']">
								<div class="trx_donations_options_description"><?php _e('Use this shortcode to insert donations list into post/page content.', 'themerex'); ?></div>
							</div>
						</div>

					</div>
									
					<div class="trx_donations_options_section trx_donations_options_section_pp_account">

						<h3 class="trx_donations_options_section_title"><?php _e('PayPal options', 'themerex'); ?></h3>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('PayPal account', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="pp_account" value="<?php echo esc_attr($this->get_option('pp_account')); ?>">
								<div class="trx_donations_options_description"><?php _e('E-mail, used for registration PayPal account.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Default amount', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="pp_amount" value="<?php echo esc_attr($this->get_option('pp_amount')); ?>">
								<div class="trx_donations_options_description"><?php _e('Default value for donation amount.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Currency', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<select size="1" name="pp_currency">
									<?php
									foreach ($this->currency_codes as $code=>$title) {
										?>
										<option value="<?php echo esc_attr($code); ?>"<?php echo $this->get_option('pp_currency')==$code ? ' selected="selected"' : ''; ?>><?php echo trim($title); ?></option>
										<?php
									}
									?>
								</select>
								<div class="trx_donations_options_description"><?php _e('Default currency for donation.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Sandbox', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<label><input type="checkbox" name="pp_sandbox" value="1"<?php echo (int) $this->get_option('pp_sandbox') > 0 ? ' checked="checked"' : ''; ?>> <?php _e('Enable sandbox mode', 'themerex'); ?></label>
								<div class="trx_donations_options_description"><?php _e('Enable sandbox mode to testing your payments without real money transfer.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Open PayPal in a new tab', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<label><input type="checkbox" name="pp_in_new_tab" value="1"<?php echo (int) $this->get_option('pp_in_new_tab') > 0 ? ' checked="checked"' : ''; ?>> <?php _e('Open PayPal in new tab', 'themerex'); ?></label>
								<div class="trx_donations_options_description"><?php _e('Open PayPal page in a new tab or replace current page (with donation form)', 'themerex'); ?></div>
							</div>
						</div>

					</div>

					
					<div class="trx_donations_options_section trx_donations_options_section_common">

						<h3 class="trx_donations_options_section_title"><?php _e('Common options', 'themerex'); ?></h3>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Blog style', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<select size="1" name="blog_style">
									<?php
									foreach ($this->blog_styles as $code=>$title) {
										?>
										<option value="<?php echo esc_attr($code); ?>"<?php echo $this->get_option('blog_style')==$code ? ' selected="selected"' : ''; ?>><?php echo trim($title); ?></option>
										<?php
									}
									?>
								</select>
								<div class="trx_donations_options_description"><?php _e('Default articles style for donations archive page.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Blog columns', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="blog_columns" value="<?php echo esc_attr($this->get_option('blog_columns')); ?>">
								<div class="trx_donations_options_description"><?php _e('Columns number in the donations archive.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Max supporters showed', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="max_supporters_to_show" value="<?php echo esc_attr($this->get_option('max_supporters_to_show')); ?>">
								<div class="trx_donations_options_description"><?php _e('Max number of supporters to show in the single donation post.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Socials share', 'themerex'); ?></h4>
							<div class="trx_donations_options_field trx_donations_options_field_share">
								<?php
								$share = $this->get_option('share');
								foreach ($this->socials_share as $code=>$link) {
									?><label><input type="checkbox" name="share[<?php echo esc_attr($code); ?>]" value="1"<?php echo !empty($share[$code]) && (int) $share[$code] > 0 ? ' checked="checked"' : ''; ?>> <?php echo trim($code); ?></label><?php
								}
								?>
								<div class="trx_donations_options_description"><?php _e('Select social networks to enable share articles.', 'themerex'); ?></div>
							</div>
						</div>

					</div>


					
					<div class="trx_donations_options_section trx_donations_options_section_theme">

						<h3 class="trx_donations_options_section_title"><?php _e('Theme specific options', 'themerex'); ?></h3>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Columns wrapper class', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="columns_wrap_class" value="<?php echo esc_attr($this->get_option('columns_wrap_class')); ?>">
								<div class="trx_donations_options_description"><?php _e('Class name for columns wrapper, used in the  current theme. If empty - use internal class.', 'themerex'); ?></div>
							</div>
						</div>
						
						<div class="trx_donations_options_row">
							<h4 class="trx_donations_options_caption"><?php _e('Single column class', 'themerex'); ?></h4>
							<div class="trx_donations_options_field">
								<input type="text" name="column_class" value="<?php echo esc_attr($this->get_option('column_class')); ?>">
								<div class="trx_donations_options_description"><?php _e('Class name (mask) for column item, used in the  current theme. If empty - use internal class.', 'themerex'); _e('For example: column-$1_$2, where $1 - column width, $2 - total columns: column-1_4, column-2_3, etc.', 'themerex'); ?></div>
							</div>
						</div>

					</div>

					<input type="submit" value="<?php echo esc_attr(__('Save options', 'themerex')); ?>">

				</form>
				
			</div>
			<?php
		}
		
		
		// Add meta box to the "New/Edit Donation" page
		function admin_add_meta_box($post_type) {
			if ($post_type == self::POST_TYPE) {
				add_meta_box(
					'trx_donations_meta_box',							// id for this section
					__( 'Donation Options', 'myplugin_textdomain' ),	// Header for this section
					array($this, 'admin_show_meta_box'),				// Method to display meta box fields
					self::POST_TYPE									// Screen name where meta box will be displayed
				);
			}
		}
	
		
		// Create additional columns in the donations list
		function admin_add_list_columns( $columns ){
			if (is_array($columns) && count($columns)>0) {
				$new_columns = array();
				foreach($columns as $k=>$v) {
					if ($k=='comments') {
						$new_columns['trx_donations_goal'] = __('Goal', 'themerex');
						$new_columns['trx_donations_raised'] = __('Raised', 'themerex');
					}
					$new_columns[$k] = $v;
				}
				$columns = $new_columns;
			}
			return $columns;
		}
		
		// Fill columns with data
		function admin_fill_list_columns($column_name='', $post_id=0) {
			if ($post_id == 0) return;
			if ($column_name == 'trx_donations_goal') {
				$goal = get_post_meta( $post_id, 'trx_donations_goal', true );
				echo floatval($goal);
			} else if ($column_name == 'trx_donations_raised') {
				$manual = get_post_meta( $post_id, 'trx_donations_manual', true );
				$raised = get_post_meta( $post_id, 'trx_donations_raised', true );
				if (empty($raised)) $raised = 0;
				echo floatval($manual + $raised);
			}
		}

	
		// Show meta box on the "New/Edit Donation" page
		function admin_show_meta_box( $post ) {
		
			// Add a nonce field so we can check for it later.
			wp_nonce_field( get_admin_url(), 'trx_donations_meta_box_nonce' );
	
			// Show social profiles
			/*
			$meta = get_post_meta( $post->ID, 'trx_donations_socials', true );
			$socials = $this->options['socials'];
			if (!empty($meta)) $socials = array_merge($socials, $meta);
			if (is_array($socials) && count($socials) > 0) {
				?>
				<div class="trx_donations_meta_socials">
					<h4 class="trx_donations_meta_title"><?php _e('Socials', 'themerex'); ?></h4>
					<p class="trx_donations_meta_description"><?php _e('Specify URLs to this project\'s profiles in the social networks', 'themerex'); ?></p>
					<?php foreach ($socials as $soc_name => $soc_link) { ?>
						<div class="trx_donations_meta_socials_item trx_donations_meta_socials_item_<?php echo esc_attr($soc_name); ?>">
							<label for="trx_donations_meta_socials_field_<?php echo esc_attr($soc_name); ?>"><?php echo esc_html(strtoupper($soc_name)); ?></label>
							<input type="text" id="trx_donations_meta_socials_field_<?php echo esc_attr($soc_name); ?>" name="trx_donations_meta_socials_field_<?php echo esc_attr($soc_name); ?>" value="<?php echo esc_attr($soc_link); ?>">
						</div>
					<?php } ?>
				</div>
				<?php
			}
			*/
	
			// Show goal and manually added money
			?>
			<div class="trx_donations_meta_money">
	
				<h4 class="trx_donations_meta_title"><?php _e('Donations details', 'themerex'); ?></h4>
				<p class="trx_donations_meta_description"><?php _e('Specify dotations goal and start manually added money to this project', 'themerex'); ?></p>
	
				<?php
				// Group Goal
				$meta = get_post_meta( $post->ID, 'trx_donations_goal', true );
				?>
				<div class="trx_donations_meta_money_item trx_donations_meta_goal">
					<label for="trx_donations_meta_goal_field"><?php _e('Group Goal', 'themerex'); ?></label>
					<input type="text" id="trx_donations_meta_goal_field" name="trx_donations_meta_goal_field" value="<?php echo esc_attr($meta); ?>">
				</div>
	
				<?php
				// Manually added
				$meta = get_post_meta( $post->ID, 'trx_donations_manual', true );
				?>
				<div class="trx_donations_meta_money_item trx_donations_meta_manual">
					<label for="trx_donations_meta_manual_field"><?php _e('Manually added', 'themerex'); ?></label>
					<input type="text" id="trx_donations_meta_manual_field" name="trx_donations_meta_manual_field" value="<?php echo esc_attr($meta); ?>">
				</div>
	
			</div>
	
			<?php
			// Supporters
			$meta = get_post_meta( $post->ID, 'trx_donations_supporters', true );
			?>
			<div class="trx_donations_meta_supporters">
				<h4 class="trx_donations_meta_title"><?php _e('Supporters', 'themerex'); ?></h4>
				<p class="trx_donations_meta_description"><?php _e('Supporters list for this project', 'themerex'); ?></p>
				<?php if (is_array($meta) && count($meta) > 0) { ?>
					<table>
						<thead>
							<tr>
								<th width="50%"><?php echo _e('Name', 'themerex'); ?></th>
								<th><?php echo _e('Amount', 'themerex'); ?></th>
								<th width="20%"><?php echo _e('E-mail', 'themerex'); ?></th>
								<th width="20%"><?php echo _e('Site', 'themerex'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$total = 0;
						foreach ($meta as $v) {
							$total += $v['amount'];
							?>
							<tr>
								<td class="trx_donations_meta_supporters_name">
									<span class="trx_donations_meta_supporters_title"><?php echo esc_html($v['name']); ?></span>
									<?php echo (!empty($v['message']) ? '<span class="trx_donations_meta_supporters_message">' . esc_html($v['message']) . '</span>' : ''); ?>
								</td>
								<td class="trx_donations_meta_supporters_amount" align="right"><?php echo esc_html($this->get_money($v['amount'])); ?></td>
								<td class="trx_donations_meta_supporters_email"><?php echo esc_html($v['email']); ?></td>
								<td class="trx_donations_meta_supporters_site"><?php echo esc_html($v['site']); ?></td>
							</tr>
							<?php 
						}
						?>
						</tbody>
						<tfoot>
							<tr>
								<td align="right"><?php _e('Total donated', 'themerex'); ?></td>
								<td align="right"><?php echo esc_html($this->get_money($total)); ?></td>
							</tr>
						</tfoot>
					</table>
				<?php } else { ?>
					<p class="trx_donations_meta_error"><?php _e('No supporters yet', 'themerex'); ?></p>
				<?php } ?>			
			</div>
			<?php
		}
	
		// Save meta box on the "New/Edit Donation" page
		function admin_save_meta_box( $post_id ) {
		
			// Check if our nonce is set.
			if ( ! isset( $_POST['trx_donations_meta_box_nonce'] ) ) {
				return;
			}
		
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['trx_donations_meta_box_nonce'], get_admin_url() ) ) {
				return;
			}
		
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
		
			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && $_POST['post_type']==self::POST_TYPE ) {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}
		
			// OK, it's safe for us to save the data now.
			
			/*
			// Socials
			if (!empty($this->options['socials'])) {
				$socials = $this->options['socials'];
				foreach ($socials as $k=>$v) {
					if (isset($_POST['trx_donations_meta_socials_field_'.$k]))
						$socials[$k] = sanitize_text_field($_POST['trx_donations_meta_socials_field_'.$k]);
				}
				update_post_meta( $post_id, 'trx_donations_socials', $socials );
			}
			*/

			// Group Goal
			if (isset($_POST['trx_donations_meta_goal_field'])) {
				$goal = (float) sanitize_text_field($_POST['trx_donations_meta_goal_field']);
				update_post_meta( $post_id, 'trx_donations_goal', $goal );
			}
	
			// Manually added
			if (isset($_POST['trx_donations_meta_manual_field'])) {
				$manual = (float) sanitize_text_field($_POST['trx_donations_meta_manual_field']);
				update_post_meta( $post_id, 'trx_donations_manual', $manual );
			}
	
		}






	
	
		//-----------------------------------------------------------------------------------
		// Frontend Interface
		//-----------------------------------------------------------------------------------
		
		
		// Load required styles and scripts
		function load_scripts() {
			global $post;
			if ( empty($post->post_content) || empty($post->post_type) ) return;
			if ( (is_single() && $post->post_type == self::POST_TYPE) || is_post_type_archive(self::POST_TYPE) || is_tax(self::TAXONOMY) || has_shortcode($post->post_content, 'trx_donations_list') || has_shortcode($post->post_content, 'trx_donations_form')) {
				themerex_plugins_enqueue_style ('themerex-donations-style',  themerex_plugins_get_file_url( 'trx_donations.css' ), array(), null);
				themerex_plugins_enqueue_style( 'themerex-socials-share-style', themerex_plugins_get_file_url('css/socials-share/css/socials-share-embedded.css'),  array(), null);
				themerex_plugins_enqueue_script('themerex-donations-script', themerex_plugins_get_file_url( 'trx_donations.js' ), array('jquery'), null, true);
			}
		}

		// Change standard single template for donation posts
		function get_single_template($template) {
			global $post;
			if (is_single() && $post->post_type == self::POST_TYPE)
				$template = dirname( __FILE__ ) . '/templates/single.php';
			return $template;
		}

		// Change standard archive template for donation posts
		function get_archive_template( $template ) {
			 if ( is_post_type_archive(self::POST_TYPE) )
				  $template = dirname( __FILE__ ) . '/templates/archive.php';
			 return $template;
		}	

		// Change standard category template for donation categories
		function get_taxonomy_template( $template ) {
			 if ( is_tax(self::TAXONOMY) )
				  $template = dirname( __FILE__ ) . '/templates/archive.php';
			 return $template;
		}	








	
		//-----------------------------------------------------------------------------------
		// Public API Interface
		//-----------------------------------------------------------------------------------

		// Return money with currency
		function get_money($money) {
			return sprintf(__('%.2f %s', 'themerex'), $money, $this->get_option('pp_currency'));
		}

		// Return option value
		function get_option($name) {
			return isset($this->options) ? $this->options[$name] : null;
		}

		// Show share links
		function show_share_links($args=array()) {
	
			$args = array_merge(array(
				'post_id' => 0,						// post ID
				'post_link' => '',					// post link
				'post_title' => '',					// post title
				'post_descr' => '',					// post descr
				'post_thumb' => '',					// post featured image
				'popup' => true,					// open share url in new window or in popup window
				'caption' => __('Share:', 'themerex'),		// share block caption
				'echo' => true						// if true - show on page, else - only return as string
				), $args);
	
			$share = $this->get_option('share');
			
			$output = '';

			if (is_array($share) && count($share) > 0) {
				if (empty($args['post_id'])) $args['post_id'] = get_the_ID();
				if (empty($args['post_id'])) $args['post_link'] = get_permalink($args['post_id']);
				if (empty($args['post_id'])) $args['post_title'] = get_the_title($args['post_id']);
				if (empty($args['post_id'])) $args['post_descr'] = get_the_excerpt($args['post_id']);
				if (empty($args['post_id'])) $args['post_thumb'] = wp_get_attachment_url(get_post_thumbnail_id($args['post_id']));

				foreach ($share as $soc=>$enabled) {
					if ( (int) $enabled == 0 || empty($this->socials_share[$soc]) ) continue;
					$icon = 'icon_share-'.$soc;
					$link = str_replace(
						array('{id}', '{link}', '{title}', '{descr}', '{image}'),
						array(
							urlencode($args['post_id']),
							urlencode($args['post_link']),
							urlencode(strip_tags($args['post_title'])),
							urlencode(strip_tags($args['post_descr'])),
							urlencode($args['post_thumb'])
							),
						$this->socials_share[$soc]);
					$output .= '<a href="'.esc_url($soc['url']).'"'
								. ' class="sc_socials_share_item"'
								. ($args['popup'] ? ' onclick="window.open(\'' . esc_url($link) .'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0\'); return false;"' : ' target="_blank"')
							. '>'
								. '<span class="' . esc_attr($icon) . '"></span>' 
							. '</a>';
				}
			}
			
			if ($output) {
				$output = '<div class="sc_socials_share">'
						. ($args['caption']!='' ? '<span class="sc_socials_share_caption">'.($args['caption']).'</span>' : '')
						. $output
					. '</div>';
				if ($args['echo']) echo ($output);
			}
			return $output;
		}

	




	
	
		//-----------------------------------------------------------------------------------
		// Shortcodes: Donation form
		//-----------------------------------------------------------------------------------
		function sc_donations_form($atts, $content = null) {
			extract(themerex_plugins_html_decode(shortcode_atts(array(
				// Individual params
				"title" => "",
				"subtitle" => "",
				"description" => "",
				"align" => "",
				// PayPal settings
				"account" => "",
				"sandbox" => "",
				"amount" => "",
				"currency" => "",
				// Common params
				"id" => "",
				"class" => "",
				"css" => "",
				"width" => "",
				"top" => "",
				"bottom" => "",
				"left" => "",
				"right" => ""
			), $atts)));
		
			if (empty($id)) $id = "sc_donations_form";
			if (empty($account)) $account = $this->get_option('pp_account');
			if (empty($currency)) $currency = $this->get_option('pp_currency');
			if (!in_array($sandbox, array('on', 'off'))) $sandbox = $this->get_option('pp_sandbox') ? "on" : "off";
			if ((float) $amount <= 0) $amount = $this->get_option('pp_amount');
			
			$css .= themerex_plugins_get_css_position_from_values($top, $right, $bottom, $left, $width);

			$form_url = $sandbox=='on' 
				? 'https://www.sandbox.paypal.com/cgi-bin/webscr' 
				: 'https://www.paypal.com/cgi-bin/webscr';

			$success = $error = '';
			if (isset($_REQUEST['trx_donations_pp_answer'])) {
				if (substr($_REQUEST['trx_donations_pp_answer'], 0, 6)=='error')
					$error = __('Operation failed! Please, try again later!', 'themerex');
				else if (substr($_REQUEST['trx_donations_pp_answer'], 0, 7)=='success' 
						&& !empty($_POST['payment_status']) && $_POST['payment_status']=='Completed' 
						&& !empty($_POST['item_number']) && (int) $_POST['item_number'] == get_the_ID())
					$success = __('Operation success! Thank you for your donation!', 'themerex');
			}

			$output = '<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_donations_form'
						. (!empty($align) && !themerex_plugins_param_is_off($align) ? ' align'.esc_attr($align) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. (!empty($subtitle) 
							? '<h6 class="sc_donations_form_subtitle sc_item_subtitle">' . trim($subtitle) . '</h6>' 
							: '')
						. (!empty($title) 
							? '<h2 class="sc_donations_form_title sc_item_title">' . trim($title) . '</h2>' 
							: '')
						. (!empty($description) 
							? '<div class="sc_donations_form_descr sc_item_descr">' . trim($description) . '</div>' 
							: '')
					. (!empty($success)
						? '<div id="sc_donations_result" class="sc_donations_result sc_donations_success">' 
								. '<p>' . trim($success) . '</p>'
							. '</div>'
						: ''
						)
					. (!empty($error)
						? '<div id="sc_donations_result" class="sc_donations_result sc_donations_error">' 
								. '<p>' . trim($error) . '</p>'
							. '</div>'
						: ''
						)
					. '<form' . ($id ? ' id="'.esc_attr($id).'_form"' : '') . ' method="post" action="' . esc_url($form_url) . '"' . ($this->get_option('pp_in_new_tab') ? ' target="_blank"' : '') . '>'
						// Hidden fields
						. '<input type="hidden" name="cmd" value="_ext-enter">'
						. '<input type="hidden" name="redirect_cmd" value="_xclick">'
						. '<input type="hidden" name="business" value="' . esc_attr($account) . '">'
						. '<input type="hidden" name="item_name" value="' . esc_attr(get_the_title()) . '">'
						. '<input type="hidden" name="item_number" value="' . esc_attr(get_the_ID()) . '">'
						. '<input type="hidden" name="currency_code" value="' . esc_attr($currency) . '">'
						. '<input type="hidden" name="quantity" value="1">'
						. '<input type="hidden" name="no_shipping" value="1">'
						. '<input type="hidden" name="first_name" value="">'
						. '<input type="hidden" name="last_name" value="">'
						. '<input type="hidden" name="on0" value="buyer_info">'
						. '<input type="hidden" name="os0" value="">'
						. '<input type="hidden" name="on1" value="message">'
						. '<input type="hidden" name="notify_url" value="' . esc_url(str_replace('http://', 'https://', themerex_plugins_add_to_url(site_url().'/', array('trx_donations_pp_answer' => 'notify')))) . '">'
						. '<input type="hidden" name="return" value="' . esc_url(themerex_plugins_add_to_url(get_permalink(), array('trx_donations_pp_answer' => 'success'))) . '">'
						. '<input type="hidden" name="cancel_return" value="' . esc_url(themerex_plugins_add_to_url(get_permalink(), array('trx_donations_pp_answer' => 'cancel'))) . '">'
						
						// PayPal statistics
						. '<img src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="">'

						// User interface fields
						. '<div class="sc_donations_form_field sc_donations_form_field_amount"><label class="required sc_donations_form_label" for="sc_donations_form_amount_5">' . __('Amount', 'themerex') . '</label>'
							. '<input id="sc_donations_form_amount_5" type="radio" name="amount_value" value="5"' . ($amount==5 ? ' checked="checked"' : '') . '>'
							. '<label class="sc_donations_form_amount_label" for="sc_donations_form_amount_5">' . $this->get_money(5) . '</label>'
							. '<input id="sc_donations_form_amount_10" type="radio" name="amount_value" value="10"' . ($amount==10 ? ' checked="checked"' : '') . '>'
							. '<label class="sc_donations_form_amount_label" for="sc_donations_form_amount_10">' . $this->get_money(10) . '</label>'
							. '<input id="sc_donations_form_amount_20" type="radio" name="amount_value" value="20"' . ($amount==20 ? ' checked="checked"' : '') . '>'
							. '<label class="sc_donations_form_amount_label" for="sc_donations_form_amount_20">' . $this->get_money(20) . '</label>'
							. '<input id="sc_donations_form_amount_50" type="radio" name="amount_value" value="50"' . ($amount==50 ? ' checked="checked"' : '') . '>'
							. '<label class="sc_donations_form_amount_label" for="sc_donations_form_amount_50">' . $this->get_money(50) . '</label>'
							. '<br>'
							. '<input id="sc_donations_form_amount_0" type="radio" name="amount_value" value="0"' . (!in_array($amount, array(5,10,20,50)) ? ' checked="checked"' : '') . '>'
							. '<label class="sc_donations_form_amount_label" for="sc_donations_form_amount_0">' . __('Other:', 'themerex') . '</label>'
							. '<input id="sc_donations_form_amount" class="sc_donations_form_amount" type="text" name="amount" value="' . esc_attr($amount) . '">'
							. ' '. esc_html($this->get_option('pp_currency'))
						. '</div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_name"><label class="required sc_donations_form_label" for="sc_donations_form_name">' . __('Name', 'themerex') . '</label><input id="sc_donations_form_name" type="text" name="name" placeholder="' . __('Name *', 'themerex') . '"></div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_email"><label class="required sc_donations_form_label" for="sc_donations_form_email">' . __('E-mail', 'themerex') . '</label><input id="sc_donations_form_email" type="text" name="email" placeholder="' . __('E-mail *', 'themerex') . '"></div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_site"><label class="sc_donations_form_label" for="sc_donations_form_site">' . __('Website', 'themerex') . '</label><input id="sc_donations_form_site" type="text" name="site" placeholder="' . __('Website', 'themerex') . '"></div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_message"><label class="sc_donations_form_label" for="sc_donations_form_message">' . __('Message (up to 200 characters)', 'themerex') . '</label><textarea id="sc_donations_form_message" name="os1" maxlength="200" placeholder="' . __('Message', 'themerex') . '"></textarea></div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_rating"><input id="sc_donations_form_rating" type="checkbox" name="show_in_rating" value="1" checked="checked"><label class="sc_donations_form_rating_label" for="sc_donations_form_rating">' . __('Show me in the supporters rating', 'themerex') . '</label></div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_note icon_share-info-circled">' . sprintf(__('<b>Please note!</b> Due to the peculiarities of payment confirmation mechanism in PayPal, in order for your donation to be registered on our website, you must click on the <b><u>"Return to %s"</u></b> link after the payment on the PayPal payment page. Thank you for your understanding.', 'themerex'), $account) . '</div>'
						. '<div class="sc_donations_form_field sc_donations_form_field_button"><input type="button" class="sc_donations_form_submit" value="'.__('Donation', 'themerex').'"></div>'
					. '</form>'
				. '</div>';
		
			return $output;
		}
		
		// Verifies a PayPal Live/Sandbox IPN.
		function ipn_validate() {
			$validated = true;
			
			// Reading from $_POST causes serialization issues with array data
			// Instead, read raw POST data from the input stream
			$raw = file_get_contents('php://input');
			$data = explode('&', $raw);
			$new_data = array();
			foreach ($data as $kv) {
				$kv = explode('=', $kv);
				if (count($kv) == 2)
					$new_data[$kv[0]] = urldecode($kv[1]);
			}

			// Prepend 'cmd=_notify-validate'
			$output = 'cmd=_notify-validate';
			$magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
			foreach ($new_data as $k => $v)
				$output .= '&' . $k . '=' . ($magic ? urlencode(stripslashes($v)) : urlencode($v));
		
			// POST IPN data back to PayPal to validate
			$url = $this->get_option('pp_sandbox')=='on' 
				? 'https://www.sandbox.paypal.com/cgi-bin/webscr' 
				: 'https://www.paypal.com/cgi-bin/webscr';

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $output);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
			if (!($res = curl_exec($ch))) {
				curl_close($ch);
				exit;
			}
			curl_close($ch);
		
			// Inspect IPN validation result and act accordingly
			if ($res == "VERIFIED") 		// The IPN is verified, process it
				$validated = true;
			else if ($res == "INVALID") 	// IPN invalid, log for manual investigation
				$validated = false;
		
			return $validated;
		}

		// Verifies a PayPal Live/Sandbox IPN.
		//
		// It returns NULL if freak error occurs (no connection, bad reply, bad IPN).
		// It returns BOOL to signal verification success or failure.
		//
		// @param array/null $IPN
		// @return bool/null
		function ipn_validate2(array $ipn = null){
			if (empty($ipn)) $ipn = $_POST;
			if (empty($ipn['verify_sign'])) return null;
			$ipn['cmd'] = '_notify-validate';
			$url = 'https://www' . ($this->get_option('pp_sandbox')=='on' ? '.sandbox' : '') . '.paypal.com/cgi-bin/webscr';
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $ipn);
			curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
			curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
			curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
			curl_setopt($cURL, CURLOPT_HEADER, false);
			curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
			curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
				'Connection: close',
				'Expect: ',
			));
			$response = curl_exec($cURL);
			$status = (int) curl_getinfo($cURL, CURLINFO_HTTP_CODE);
			curl_close($cURL);
			if (empty($response) or !preg_match('~^(VERIFIED|INVALID)$~i', $response = trim($response)) or !$status)
				return null;
			if (intval($status / 100) != 2)
				return false;
			return !strcasecmp($response, 'VERIFIED');
		}

		// Add to supporters list
		function add_to_supporters($post_id, $sup) {
			$supporters = get_post_meta( $post_id, 'trx_donations_supporters', true );
			$raised = max(0, (float) get_post_meta( $post_id, 'trx_donations_raised', true ));
			if (empty($supporters)) $supporters = array();
			$supporters[] = $sup;
			uasort($supporters, array(&$this, 'sort_supporters'));
			update_post_meta( $post_id, 'trx_donations_supporters', $supporters );
			update_post_meta( $post_id, 'trx_donations_raised', $raised + (float) $sup['amount'] );
		}

		// Sort supporters by amount
		function sort_supporters($s1, $s2) {
			return $s1['amount'] > $s2['amount'] 
				? -1
				: ($s1['amount'] < $s2['amount'] 
					? 1
					: 0);
		}

	




	
	
		//-----------------------------------------------------------------------------------
		// Shortcodes: Donations list
		//-----------------------------------------------------------------------------------
		function sc_donations_list($atts, $content = null) {
			extract(themerex_plugins_html_decode(shortcode_atts(array(
				// Individual params
				"style" => "excerpt",
				"columns" => 3,
				"ids" => "",
				"cat" => "",
				"count" => 3,
				"offset" => "",
				"orderby" => "date",
				"order" => "desc",
				"readmore" => __('Learn more', 'themerex'),
				"title" => "",
				"subtitle" => "",
				"description" => "",
				"link_caption" => __('More donations', 'themerex'),
				"link" => '',
				// Common params
				"id" => "",
				"class" => "",
				"css" => "",
				"top" => "",
				"bottom" => ""
			), $atts)));
		
			$output = '';
			$in_shortcode = true;
			
			if (file_exists($tpl = themerex_plugins_get_file_dir( 'templates/content-'.$style.'.php' ))) {

				if (empty($id)) $id = "sc_donations_".str_replace('.', '', mt_rand());
				
				$css .= themerex_plugins_get_css_position_from_values($top, '', $bottom, '');
		
				$count = max(1, (int) $count);
				$columns = max(1, min(12, (int) $columns));
				if ($count < $columns) $columns = $count;
		
				$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
							. ' class="sc_donations'
								. ' sc_donations_style_'.esc_attr($style)
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. '"'
								. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. (!empty($subtitle) ? '<h6 class="sc_donations_subtitle sc_item_subtitle">' . trim($subtitle) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_donations_title sc_item_title">' . trim($title) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_donations_descr sc_item_descr">' . trim($description) . '</div>' : '')
							. ($columns > 1 
								? '<div class="'.($this->get_option('columns_wrap_class')!='' ? esc_attr($this->get_option('columns_wrap_class')) : 'sc_donations_columns_wrap').'">' 
								: '');
	
				if (!empty($ids)) {
					$posts = explode(',', $ids);
					$count = count($posts);
					if ($count < $columns) $columns = $count;
				}
					
				$args = array(
					'post_type' => self::POST_TYPE,
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
					'readmore' => $readmore
				);
				
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
				
				$args = themerex_plugins_query_add_sort_order($args, $orderby, $order);
				$args = themerex_plugins_query_add_posts_and_cats($args, $ids, self::POST_TYPE, $cat, self::TAXONOMY);
	
				$query = new WP_Query( $args );
			
				while ( $query->have_posts() ) { 
					$query->the_post();
					ob_start();
					require $tpl;
					$output .= ob_get_contents();
					ob_end_clean();
				}
				wp_reset_postdata();
			
				if ($columns > 1) {
					$output .= '</div>';
				}
		
				$output .=  (!empty($link) ? '<div class="sc_donations_button sc_item_button"><a href="'.esc_url($link).'">'.esc_html($link_caption).'</a></div>' : '')
							. '</div><!-- /.sc_donations -->';
			}
			return $output;
		}





	}
}
?>