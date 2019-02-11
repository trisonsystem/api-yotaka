<?php
$ci = get_instance(); // CI_Loader instance
$ci->load->config('config');
$ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file','key_prefix' => 'lang_'));
$lng = 'en';
// if (!$result = $ci->cache->get($lng)){
  	// $result   = cUrl($ci->config->config['apilang_url'].'/lang',"post","lng=".$lng);
  	// $result   = cUrl($ci->config->config['base_url'].'/lang.json',"get","");
  	// $result   = cUrl($ci->config->config['base_url'].'/lang.php',"post","lng=".$lng);

  	// $ci->cache->save($lng, $result, 1 * 1440 * 365); // 1 year
// }

// $result = json_encode($arrLang);
// $decode = json_decode($result);

$lang   = array(
				'main_menu' 		=> 'Main Menu',
				'admin_setup' 		=> 'Admin Setup',
				'admin_list' 		=> 'Admin List',
				'cash' 				=> 'Cash',
				'deposit_list'		=> 'Deposit',
				'withdrawal_list' 	=> 'Withdrawal',
				'news' 				=> 'News',
				'add_news' 			=> 'Add News',
				'list_news' 		=> 'News List',
				'exchange' 			=> 'Exchange',
				'exchange_rate'		=> 'Exchange Rete',
				'interest'			=> 'Interest',
				'reward'			=> 'Reward',
				'add_reward'		=> 'Add Reward',
				'list_reward'		=> 'List Reward',
				'product'			=> 'Product',
				'add_product'		=> 'Add Product',

				'no' 				=>'No',
				'id' 				=>'ID',
				'refer' 			=>'Refer',
				'user_name' 		=>'Username',
				'status' 			=>'Status',
				'type' 				=>'Type',
				'cur' 				=>'Currency',
				'amount' 			=>'Amount',
				'bank_id' 			=>'Bank ID',
				'book_name' 		=>'Book Name',
				'book_code' 		=>'Book Code',
				'deposit_date' 		=>'Deposit Date',
				'datetime' 			=>'Date Time',
				'to_bank_id' 		=>'To Bank ID',
				'to_book_name' 		=>'To Book Name',
				'to_book_code' 		=>'To Book Code',
				'admin_update' 		=>'Admin Update',
				'update_datetime' 	=>'Update DateTime',
				'create_date' 		=>'Create Date',

				'buy' 				=>'Buy',
				'sell' 				=>'Sell',

				'username' 			=>'Username',
				'name' 				=>'Name',
				'password' 			=>'Password',
				'create' 			=>'Create',
				'edit' 				=>'Edit',
				'deposit' 			=>'Deposit',
				'withdrawal' 		=>'Withdrawal',
				'authorization' 	=>'Authorization',
				'create_admin' 		=>'Create Admin',
				'edit_admin' 		=>'Edit Admin',
				'active' 			=>'Active',
				'lock' 				=>'Lock',

				'change_pass' 		=>'Change Password',
				'old_pass' 			=>'Old Password',
				'new_pass' 			=>'New Password',
				'first_pass' 		=>'First Password',
				'second_pass' 		=>'Second Password',

				'search' 			=>'Search',
				'manage' 			=>'Manage',
				'save' 				=>'Save',
				'close' 			=>'Close',
				'confirm_chk' 		=>'Confirm Checking',
				'confirm_cancle' 	=>'Confirm Cancel',
				'wait_checking' 	=>'Wait Checking',
				'all' 				=>'All',
				'complete' 			=>'Complete',
				'reject' 			=>'Reject',
				'user_id' 			=>'UserId',
				'reset' 			=>'reset',

				'atm' 				=>'ATM',
				'int_bank' 			=>'Internet banking',
				'mobile_bank' 		=>'Mobile banking',
				'cash' 				=>'Cash',
				'bank_count' 		=>'Bank counter',

				'coupon' 			=>'Coupon',
				'xxxxx' 			=>'xxxx',

				'head' 				=>'Headder',
				'title' 			=>'Title',
				'detail' 			=>'Detail',
				'img_old' 			=>'Image Old',
				'img_upload' 		=>'Upload Image',
				'news_head' 		=>'News Head',
				'news_title' 		=>'News Title',
				'news_detail' 		=>'News Detail',
				'news_image' 		=>'News Images',
				'news_date' 		=>'News Date',

				'web_id' 			=>'Web Id',
				'condition' 		=>'Condition',
				'turnover' 			=>'Turnover',
				'condition_val' 	=>'Condition Value',
				'date_end' 			=>'DateTime End',
				'image' 			=>'Image',
				'qty' 				=>'Quantity',
				'get' 				=>'Get',
				'date_create' 		=>'DateTime Create',
				'reward_id' 		=>'Reward Id',
				'receive_address' 	=>'Receive Address',
				'reward_title' 		=>'Reward Title',
				'reward_timestop' 	=>'Reward DateTime Stop',
				'admin_sent' 		=>'Admin Sent',

				'price' 			=>'Price',
				'product_name' 		=>'Product Name',
				'product_detail' 	=>'Product Detail',
				'product_list' 		=>'Product List',
				'product_images'	=>'Product Images',
				'add_images'		=>'Add Images',
				'row' 				=>'Row',
				'prev' 				=>'Prev',
				'next' 				=>'Next',
				'page' 				=>'Page',
				'xxxxx' 			=>'xxxx',

				'save_success' 		=>'Save Success',
				'save_error' 		=>'Save Error',
				
				'xxxx' 				=>'xxxxx',

);
