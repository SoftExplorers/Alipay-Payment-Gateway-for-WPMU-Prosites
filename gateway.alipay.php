<?php
/*
	Addon Name: AliPay
	Author: Softexplorers
	Author URI: http://softexplorers.com
	Gateway ID: alipay
 */

class alipay extends Membership_Gateway {
	var $gateway = 'alipay';
	var $title = 'AliPay';
	
	public function __construct() {
		parent::__construct();
		add_action( 'M_gateways_settings_' . $this->gateway, array( &$this, 'mysettings' ) );
		
		if ( $this->is_active() ) {
			// Subscription form gateway
			add_action( 'membership_purchase_button', array( &$this, 'display_subscribe_button' ), 1, 3 );
			// Payment return
			add_shortcode( 'alipay_membership_handle_payment_return', array(&$this, 'handle_alipay_return' ));
		}
	}#construct ends here
	function mysettings() {
		global $M_options;
		if ( empty($M_options['paymentcurrency']) ) {
			$M_options['paymentcurrency'] = 'AUD';
		}
		$title 			= get_option($this->gateway . "_title");
		$description 	= get_option($this->gateway . "_description");
		$payment_method = get_option($this->gateway . "_payment_method");
		$partner_id 	= get_option($this->gateway . "_partnerID");
		$secure_key 	= get_option($this->gateway . "_secure_key");
		$account 		= get_option($this->gateway . "_ap_account");
		$return_url_back = get_option($this->gateway . "_return_url");
		$notify_url_back = get_option($this->gateway . "_notify_url");
		$form_sub_met 	= get_option($this->gateway . "_form_sub_method");
		$title_format 	= get_option($this->gateway . "_order_title_format");
		$debug			= get_option($this->gateway . "_debug");
		$se_alipay_currency 	= get_option($this->gateway . "_se_alipay_currency");

		$title 			= !empty($title) ? $title : '';
		$description 	= !empty($description) ? $description : '';
		$payment_method = !empty($payment_method) ? $payment_method : '';
		$partner_id 	= !empty($partner_id) ? $partner_id : '';
		$secure_key 	= !empty($secure_key) ? $secure_key : '';
		$account 		= !empty($account) ? $account : '';
		$return_url_back = !empty($return_url_back) ? $return_url_back : '';
		$notify_url_back = !empty($notify_url_back) ? $notify_url_back : '';
		$title_format 	= !empty($title_format) ? $title_format : '';
		$debug 			= !empty($debug) ? $debug : '';
		$se_alipay_currency 	= !empty($se_alipay_currency) ? $se_alipay_currency : $M_options['paymentcurrency'];
		$description 	= stripcslashes($description);
		$description 	= stripcslashes($description); 
		?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th class="titledesc" scope="row">
					<td class="forminp">
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_alipay_title">Title</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Title</span>
							</legend>
							<input id="se_alipay_title" class="input-text regular-input " type="text" placeholder="" value="<?php echo $title;?>" style="" name="se_alipay_title">
							<p class="description">This controls the title which the user sees during checkout.</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_alipay_description">Description</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Description</span>
							</legend>
							<textarea id="se_alipay_description" class="input-text wide-input " placeholder="" style="width: 100%" name="se_alipay_description" type="textarea" cols="20" rows="3"><?php echo $description;?></textarea>
						</fieldset>
					</td>
				</tr>				
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_alipay_partnerID">Partner ID</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Partner ID</span>
							</legend>
							<input id="se_alipay_partnerID" class="input-text regular-input " type="text" placeholder="" value="<?php echo $partner_id;?>" style="width:400px" name="se_alipay_partnerID">
							<p class="description">
								Please enter the partner ID
								<br>
								If you don't have one,
								<a target="_blank" href="https://b.alipay.com/newIndex.htm">Click here</a> to get.
							</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_alipay_secure_key">Security Key</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Security Key</span>
							</legend>
							<input id="se_alipay_secure_key" class="input-text regular-input " type="text" placeholder="" style="width:400px" value="<?php echo $secure_key;?>" name="se_alipay_secure_key">
								<p class="description">
								Please enter the security key
								<br>
								If you don't have one,
								<a target="_blank" href="https://b.alipay.com/newIndex.htm">Click here</a> to get.
							</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_return_url">Return URL</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Return URL</span>
							</legend>
							<input id="se_return_url" class="input-text regular-input " type="text" placeholder="" style="width:400px" value="<?php echo $return_url_back;?>" name="se_return_url">
							<p class="description">Please enter return URL here.</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_notify_url">Notify URL</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Notify URL</span>
							</legend>
							<input id="se_notify_url" class="input-text regular-input " type="text" placeholder="" style="width:400px" value="<?php echo $notify_url_back;?>" name="se_notify_url">
							<p class="description">Please enter notify URL here.</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="se_alipay_currency">Currency</label>
					</th>
					<td class="forminp">
						<fieldset>
							<legend class="screen-reader-text">
								<span>Currency</span>
							</legend>
							<?php echo $se_alipay_currency;?>
							<input id="se_alipay_currency" class="input-text regular-input " type="hidden" placeholder="" style="width:100px;" value="<?php echo $se_alipay_currency;?>" name="se_alipay_currency">
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	} # function mysettings ends here
	function update() {
		if (isset($_POST['se_alipay_partnerID'])) {
			update_option($this->gateway . "_title", $_POST['se_alipay_title']);
			update_option($this->gateway . "_description", $_POST['se_alipay_description']);
			update_option($this->gateway . "_partnerID", $_POST['se_alipay_partnerID']);
			update_option($this->gateway . "_secure_key", $_POST['se_alipay_secure_key']);
			#update_option($this->gateway . "_ap_account", $_POST['se_alipay_alipay_account']);
			update_option($this->gateway . "_return_url", $_POST['se_return_url']);
			update_option($this->gateway . "_notify_url", $_POST['se_notify_url']);
			update_option($this->gateway . "_se_alipay_currency", $_POST['se_alipay_currency']);
		}
		// default action is to return true
		return true;
	}#function update ends here
	function display_subscribe_button($subscription, $pricing, $user_id) {
		global $M_options;
		if ( empty($M_options['paymentcurrency']) ) {
			$M_options['paymentcurrency'] = 'AUD';
		}
		$transaction_id		='';
		$partner 			= get_option($this->gateway . "_partnerID");
		$secure_key1 		= get_option($this->gateway . "_secure_key");
		$se_alipay_currency = get_option($this->gateway . "_se_alipay_currency");
		$return_url 		= get_option($this->gateway . "_return_url");
		$notify_url 		= get_option($this->gateway . "_notify_url");
		$title          	= get_option($this->gateway . "_title");
		$description    	= get_option($this->gateway . "_description");
		
		$description    	= !empty($description) ? $description : '';
		$title          	= !empty($title) ? $title : 'Pay via Alipay';

		$subscription_name 	= $subscription->sub_name();
		$subscription_id 	= $subscription->sub_id();
		$price_in_dollars 	= $pricing[0]['amount'];
		$payment_currency 	= $M_options['paymentcurrency'];
		
		$encrypted_user_id	=  md5($user_id);
		$encrypted_user_id  = substr($encrypted_user_id,0,8);
		$transaction_id 	= wp_generate_password(5).':'.$encrypted_user_id.':'.$subscription_id; 
		
		$alipay_params_to_send = array() ;
		$alipay_params_to_send['service'] = 'create_forex_trade' ;
		$alipay_params_to_send['partner'] = $partner;
		$alipay_params_to_send['notify_url'] =  $notify_url;
		$alipay_params_to_send['return_url'] = $return_url ;
		$alipay_params_to_send['subject'] =  $user_id.'|#'.$transaction_id;
		$alipay_params_to_send['body'] = $user_id.'|#'.$transaction_id ;
		$alipay_params_to_send['out_trade_no'] = $transaction_id ;
		$alipay_params_to_send['currency'] = $payment_currency ; #Default is australian dollars
		$alipay_params_to_send['total_fee'] = $price_in_dollars ;
		$alipay_params_to_send['_input_charset'] = "utf-8" ;		
		$ok = ksort( $alipay_params_to_send ) ;
    	if ( $ok !== TRUE ) {
        	return <<<EOT
&nbsp;&nbsp; "ksort()" failure #1 - preparing query string parameters for Alipay
EOT;
    	}
	    $signature_string = '';
	    $comma = '';
	    foreach ( $alipay_params_to_send as $name => $value ) {
	        if ( trim( $value ) !== '' ) {
	            $signature_string .= $comma . $name . '=' . $value;
	            $comma = '&';
	        }
	    }
	    $signature_string .= $secure_key1 ;
	    $md5 = md5( $signature_string ) ;
	    $alipay_params_to_send['sign']      = $md5  ;
    	$alipay_params_to_send['sign_type'] = 'MD5' ;
		$ok = ksort( $alipay_params_to_send ) ;

    	if ( $ok !== TRUE ) {
        	return <<<EOT
&nbsp;&nbsp; "ksort()" failure #2 - preparing query string parameters for Alipay
EOT;
    	}
    	$url = 'https://mapi.alipay.com/gateway.do' ;
		$form 	 = '';
		$form 	.= '<form id="alipaysubmit" target="_top" method="post" action="'.$url.'" name="alipaysubmit">';
	    foreach ( $alipay_params_to_send as $name => $value ) {
	        $form   .= '<input type="hidden" value="'.$value.'" name="'.$name.'">';
	    }
		$form   .= '<input id="submit_alipay_payment_form" class="button-alt" type="submit" value="'.$title.'">';
		$form   .= '</form><br><p>'.$description.'</p><br>';
		echo $form;
	} #function display subscribe button ends here
	function handle_alipay_return() {
		//handle return things here
		$timestamp = time();
		$outTradeNumber = $_REQUEST['out_trade_no'];
		if (isset($outTradeNumber)){
			global $current_user;
			$user_id = $current_user->ID;
			list($random_string,$encrypted_user_id,$sub_id) = explode(':',$outTradeNumber);
			
			# Verify the User Validity
			$verify_user_id	=  md5($user_id);
			$verify_user_id  = substr($verify_user_id,0,8);
			if($verify_user_id == $encrypted_user_id) {
				## Do the membership stuff
				if ( $_REQUEST['trade_status'] == 'TRADE_FINISHED' || $_REQUEST['trade_status'] == 'TRADE_SUCCESS' ) {
				$this->_record_transaction($user_id, $sub_id, $_REQUEST['total_fee'], $_REQUEST['currency'], $timestamp, $_REQUEST['out_trade_no'],$_REQUEST['trade_status'], '');
				membership_debug_log(__('Processed transaction received - ', 'membership') . print_r($_REQUEST, true));
				// Added for affiliate system link
				do_action('membership_payment_processed', $user_id, $sub_id,$_REQUEST['total_fee'],$_REQUEST['currency'], $_REQUEST['out_trade_no']);
				$member = Membership_Plugin::factory()->get_member($user_id);
				if($member) {
					$member->create_subscription($sub_id, $this->gateway);
					membership_debug_log( sprintf(__('Order complete for user %d on subscription %d.', 'membership'), $user_id, $sub_id ) );
				}
				do_action('membership_payment_subscr_signup', $user_id, $sub_id);
				}
			}
		}
	} #handle alipay return ends here
} #class alipay ends here
Membership_Gateway::register_gateway( 'alipay', 'alipay' );