<?php 
/*
Plugin Name: CSS Share Buttons
Plugin URI: http://www.digcms.com/
Description: CSS share buttons. It show lite share button only with only CSS code. It's not using any javascript like other. It's load only one CSS file. Adds a css share button which allows you to share post on Google Plus, Twitter, Facebook. 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Version: 1.0
Author: Purab Kharat
Author URI: http://www.digcms.com
*/

/*
 * CSS share admin Setting page
 */
function css_share_options() {
    // Add a new top-level menu (ill-advised):
    add_menu_page(__('CSS Share Buttons', 'menu-css-share'), __('CSS Share', 'menu-css-share'), 'manage_options', 'cssshare-dashboard', 'css_share_buttons_options_page');

    // Add a submenu to the custom top-level menu:
    add_submenu_page('menu-css-share', __('CSS menu Settings', 'menu-css-share'), __('CSS menu Settings', 'menu-css-share'), 'manage_options', 'css-share-tool', 'css_share_buttons_options_page');
}
if (is_admin()) {
    add_action('admin_menu', 'css_share_options');
    add_action('admin_init', 'css_share_buttons_init');
}

function css_share_buttons_init() {
    if (function_exists('register_setting')) {
        register_setting('css_share-options', 'css_share_where');
        register_setting('css_share-options', 'css_share_style');
    }
}
/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

/**
 * Enqueue plugin style-file
 */
function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('csssharebuttons.min.css', __FILE__) );
    wp_enqueue_style('gavern-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css' );
    wp_enqueue_style( 'gavern-font-awesome' );
    wp_enqueue_style( 'prefix-style' );
}

/*
 * hook for adding CSS button on post
 */
function css_share_buttons($content) {
    global $post;
    //$url = get_permalink();
    $title = get_the_title();
    $permalink = get_the_permalink();

    if (get_option('css_share_where') == 'manual' && get_option('css_share_style') != '') {
        $button = '<div class="container_share" style="' . get_option('css_share_style') . '">  
         <a href="http://www.facebook.com/sharer.php?u=' . $permalink . '&amp;t=' . $title . '" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span><p>Facebook</p></a>  
          <a href="http://twitter.com/share?url=' . $permalink . '&amp;text=' . $title . '" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span><p>Twitter</p></a>
          <a href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span><p>Google +</p></a>
          <a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&amp;title=' . $title . '" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span><p>Linkedin</p></a>  
        </div>';
    } else if(get_option('css_share_where') == 'floating'){
        $button = '
                    <div class="floating_container_share">  
         <a href="http://www.facebook.com/sharer.php?u=' . $permalink . '&amp;t=' . $title . '" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span></a>  
          <a href="http://twitter.com/share?url=' . $permalink . '&amp;text=' . $title . '" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span></a>
          <a href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span></a>
          <a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&amp;title=' . $title . '" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span></a>  
        </div>';
    } else {
        $button = '
                    <div class="container_share">  
         <a href="http://www.facebook.com/sharer.php?u=' . $permalink . '&amp;t=' . $title . '" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span><p>Facebook</p></a>  
          <a href="http://twitter.com/share?url=' . $permalink . '&amp;text=' . $title . '" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span><p>Twitter</p></a>
          <a href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span><p>Google +</p></a>
          <a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&amp;title=' . $title . '" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span><p>Linkedin</p></a>  
        </div>';
    }

    if (get_option('css_share_where') == 'beforeandafter') {
        return $button . $content . $button;
    } else if (get_option('css_share_where') == 'before') {
        return $button . $content;
    } else {
        return $content . $button;
    }
}

add_filter('the_content', 'css_share_buttons');
//add_filter('the_excerpt', 'css_share_buttons');

/*
 * admin config page
 */
function css_share_buttons_options_page() {
?>
        <div style="padding:50px;">
        <h2>Settings for CSS Share Button Integration in your blog</h2>
        <p>This plugin will install CSS Share Button  in page and post. This plugin will provide you more updated features.  </p>
        <form method="post" action="options.php">
            <?php
            // New way of setting the fields, for WP 2.7 and newer
            if (function_exists('settings_fields')) {
                settings_fields('css_share-options');
            } else {
                wp_nonce_field('update-options');
                ?>

                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="css_share_where" />
    <?php } ?> Display Position<br>
            <select name="css_share_where" onchange="if(this.value == 'manual'){getElementById('manualhelp').style.display = 'block';} else {getElementById('manualhelp').style.display = 'none';}">
                
                <option <?php if (get_option('css_share_where') == 'floating') echo 'selected="selected"'; ?> value="floating">Floating </option>
                <option <?php if (get_option('css_share_where') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>

                <option <?php if (get_option('css_share_where') == 'after') echo 'selected="selected"'; ?> value="after">After</option>

                <option <?php if (get_option('css_share_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>

                <option <?php if (get_option('css_share_where') == 'manual') echo 'selected="selected"'; ?> value="manual">Manual</option>

            </select><br>
            <p>
                If you use CSS Share Button  it like on digcms.com then use<b> clear:left; float: left; margin-right: 10px; margin-top:10px;</b> </p>

            <input name="css_share_style" type="text" id="css_share_style" value="<?php echo htmlspecialchars(get_option('css_share_style')); ?>" size="30" />


            <br><br>
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </form>
    </div>
<?php } ?>
