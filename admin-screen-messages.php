<?php
/**
 * Plugin Name: Admin Screen Messages
 * Plugin URI: http://www.smallbusinesswebdesigns.net.au/wordpress-admin-screen-messages-release.html
 * Description: This feature allows you to set custom messages at the top of administrator screen, for users based on their role.
 * Version: 1.0.0
 * Author: Small Business Web Design Sydney
 * Author URI: http://www.smallbusinesswebdesigns.net.au
 */


function asm_my_admin_notice() {
global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	$curr_user = $wp_roles->role_names[$role];
$msg = filter_var(get_option('wpb_'.$curr_user.'_Msg'), FILTER_SANITIZE_STRING);
if($msg):
?>
<div class="updated">
<?php 

echo '<p>'.$msg.'</p></div>';
endif;
?>
<?php
}
add_action( 'admin_notices', 'asm_my_admin_notice' );


function asm_send_msgs() {
$roles = get_editable_roles();

if(isset($_POST['sub'])){
foreach($roles as $role){
	$msg = sanitize_text_field(get_option('wpb_'.$role['name'].'_Msg'));
	if($msg != "" || $msg == NULL){
	update_option( 'wpb_'.$role['name'].'_Msg', sanitize_text_field($_POST[$role['name'].'-Msg']));
	}
	else{
	add_option( 'wpb_'.$role['name'].'_Msg',  sanitize_text_field($_POST[$role['name'].'-Msg']), '', 'yes' );
	}
 }
}
?>
<form method="post" action="">
<fieldset style="border:1px solid #000; width:50%; margin-top:20px; padding:10px;">
<legend style="font-size:16px; font-weight:700;">Add custom messages to display in the administrator screen based on role:</legend>
<table cellpadding="10" cellspacing="10">
<?php
foreach($roles as $role){
echo '<tr>
<td valign="top" style="width:50%;"><strong>Add custom message for Role : '.$role['name'].'</strong>
</td>
<td>
<input type="hidden" name="role" value="'.$role['name'].'"/>
<textarea name="'.$role['name'].'-Msg" style="width:400px; height:80px">'.get_option('wpb_'.$role['name'].'_Msg').'</textarea>
</td>
</tr>';
}
?>
<tr><td colspan="2"><input type="submit" id="sub" name="sub" class="button" value="Save"/></td></tr>
</table>
</fieldset>
</form>
<?
}

function asm_admin_msgs() {
 if ( is_admin() ) {
 add_options_page('Admin Screen Messages', 'Admin Screen Messages', 'manage_options', 'admin-screen-messages', 'asm_send_msgs');
 }	
}
add_action('admin_menu', 'asm_admin_msgs');

