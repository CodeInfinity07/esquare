<?php

function callcourier_register_settings() {
    add_option( 'callcourier_option_name', 'This is my option value.');
    register_setting( 'callcourier_nt_group', 'callcourier_option_name', 'callcourier_callback' );
 }
 add_action( 'admin_init', 'callcourier_register_settings' );

 function callcourier_register_options_page() {
    add_options_page('Page Title', 'Plugin Menu', 'manage_options', 'callcourier', 'callcourier_options_page');
  }
  add_action('admin_menu', 'callcourier_register_options_page');

  function callcourier_option_page()
{
  //content on page goes here
}

function callcourier_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>My Plugin Page Title</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'myplugin_options_group' ); ?>
  <h3>This is my option</h3>
  <p>Some text here.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="myplugin_option_name">Label</label></th>
  <td><input type="text" id="myplugin_option_name" name="myplugin_option_name" value="<?php echo get_option('myplugin_option_name'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
} ?>