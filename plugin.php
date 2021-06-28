<?php
/*
Plugin Name: WP User Role Blocker
Plugin URI: https://github.com/luthfor-Rahman-Jubel
Description: A simple and nice plugin to block existing users from logging into the admin panel by assigning them to the 'Blocked' user role, as simple as that.
Version: 1.0.0
Author: Jubel Ahmed
Author URI: https://github.com/luthfor-Rahman-Jubel
License: GPLv2 or later
Text Domain: U_R_B
Domain Path: /languages
 */

 class role_blocker{

    public function __construct(){

        add_action('plugin_action_links_'.plugin_basename(__FILE__), array($this,'U_R_B_settings_page') );
        add_action('plugins_loaded', array($this, 'U_R_B_bootstrap') );
        add_action('wp_enqueue_scripts', array($this, 'U_R_B_scripts_loaded') );
        add_action('init', array($this, 'U_R_B_user_role') );
        add_action('init', array($this, 'U_R_B_redirect_url') );
        add_filter('query_vars', array($this, 'U_R_B_query_var') );
        add_action('template_redirect', array($this, 'U_R_B_blocked_page') );
    }

    public function U_R_B_settings_page( $links ){
        $newlink = sprintf("<a href='%s'>%s</a>",'options-general.php',__('Settings','U_R_B') );
        $links[] = $newlink;
        return $links;
    }
    public function U_R_B_bootstrap(){
        load_plugin_textdomain('U_R_B_lang', plugin_dir_url(__FILE__). "/languages" );
       // wp_enqueue_style('role-blocker-css', plugin_dir_url(__FILE__). "assets/css/style.css", null, time() );
    }
    public function U_R_B_scripts_loaded( $hook ){     
            wp_enqueue_style('role-blocker-css', plugin_dir_url(__FILE__). "assets/css/style.css", null, '1.0' );

    }
    public function U_R_B_user_role(){
        add_role('user_blocked', __( 'Blocked','U_R_B'), array( 'blocked' => true ) );
        add_rewrite_rule('blocked/?$', 'index.php?blocked=1', 'top');
    }
    public function U_R_B_redirect_url(){
        if( is_admin() && current_user_can('blocked') ){
           wp_redirect( get_home_url() . '/blocked' );
           die();
        }
    }  
    public function U_R_B_query_var( $query_vars ){
        $query_vars[] = 'blocked';
        return $query_vars;
    }
    public function U_R_B_blocked_page(){
        $is_blocked = intval(get_query_var( 'blocked' ) );
        if( $is_blocked || current_user_can('blocked') ){
            ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php _e('Role-blocker','U_R_B'); ?></title>
    </head>
    <body>
            <?php wp_head();
           // echo get_header();
            
            ?>
        <header class="top-header">
        </header>
        
        <!--dust particel-->
        <div>
            <div class="starsec"></div>
            <div class="starthird"></div>
            <div class="starfourth"></div>
            <div class="starfifth"></div>
        </div>
        <!--Dust particle end--->
        
        <div class="lamp__wrap">
            <div class="lamp">
            <div class="cable"></div>
            <div class="cover"></div>
            <div class="in-cover">
                <div class="bulb"></div>
            </div>
            <div class="light"></div>
            </div>
        </div>
        <!-- END Lamp -->
        <section class="error">
            <!-- Content -->
            <div class="error__content">
            <div class="error__message message">
                <h1 class="message__title"><?php _e('You are blocked','U_R_B'); ?></h1>
                <p class="message__text">
                <?php _e("We're sorry, the page you were looking for isn't available for you. You may blocked by your site admin", "U_R_B"); ?>
                </p>
            </div>
            <div class="error__nav e-nav">
                <a href="<?php echo site_url(); ?>" target="_blanck" class="e-nav__link"></a>
            </div>
            </div>
            <!-- END Content -->
        </section>
        
    </div>

    <?php
  //  echo get_footer();
    wp_footer(); 
    
    ?>
    </body>
    </html>
            <?php
        die();
        }
    }
 }

 new role_blocker();