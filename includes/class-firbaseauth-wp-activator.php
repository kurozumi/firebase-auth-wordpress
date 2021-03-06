<?php

/**
 * Fired during plugin activation
 *
 * @link       http://ash2osh.com
 * @since      1.0.0
 *
 * @package    Firbaseauth_Wp
 * @subpackage Firbaseauth_Wp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Firbaseauth_Wp
 * @subpackage Firbaseauth_Wp/includes
 * @author     ahmed sherif <ash2oshapps@gmail.om>
 */
class Firbaseauth_Wp_Activator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate() {

    // Information needed for creating the plugin's pages
    $page_definitions = array(
        'fireauth-signin' => array(
            'title' => __('Sign In', 'fawp'),
            'content' => '[fireauth_signin]'
        ),
        'fireauth-account' => array(
            'title' => __('Your Account', 'fawp'),
            'content' => '[fireauth_accountinfo]'
        ),
    );
    foreach ($page_definitions as $slug => $page) {
      // Check that the page doesn't exist already
      $query = new WP_Query('pagename=' . $slug);
      if (!$query->have_posts()) {
        // Add the page using the data from the array above
        wp_insert_post(
            array(
                'post_content' => $page['content'],
                'post_name' => $slug,
                'post_title' => $page['title'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'ping_status' => 'closed',
                'comment_status' => 'closed',
            )
        );
      }
    }


    //create the database tables needed
    global $wpdb;
    $table_name = $wpdb->prefix . 'fireauth_users';
    $wpdb_collate = $wpdb->collate;
    $createSQL = "CREATE TABLE {$table_name} (
         user_id BIGINT(20) unsigned NOT NULL ,
         uid varchar(99) NOT NULL,
         PRIMARY KEY  (uid)
         )
         COLLATE {$wpdb_collate}";
    //echo $createSQL;die();
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    dbDelta($createSQL);

    //set default options
    $default = array(
        'fawp_textarea_field_0' => '{}',
        'fawp_checkbox_field_1' => 0,
        'fawp_checkbox_field_2' => 0,
        'fawp_checkbox_field_3' => 0,
        'fawp_checkbox_field_4' => 0,
        'fawp_select_field_5' => 'fireauth-signin',
        'fawp_checkbox_field_6' => 0,
        'fawp_checkbox_field_7' => 0,
    );

    update_option('fawp_settings', $default);
  }

}
