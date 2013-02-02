<?php
/*
  Plugin Name: Post-tracker
  Plugin URI: ichiblog.ru
  Description: Plugins for integration trackers kods.
  Version: 0.1
  Author: Ichi
  Author URI: http://ichiblog.ru
 */

if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL'))
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
if (!defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

// Подключаем раздел для посылок
require_once( plugin_dir_path( __FILE__ ) . 'aioseop.class.php');

class STbox {

    function cssStyles() {
        $stPath = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/styles/' . '/';
        echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $stPath . 'style.css" />' . "\n";
    }

}

add_action('admin_init', 'pt_admin');

// Связываем блоки дополнительных полей с записями
function pt_admin() {
    add_meta_box(
            'pt_review_meta_box', 'Tracker Info', 'display_pt_review_meta_box', 'post', 'normal', 'high'
    );
}

// Блок дополнительных полей
function display_pt_review_meta_box($pt_review) {
    $track_n = esc_html(get_post_meta($pt_review->ID, 'track_n', true));
    $track_store = esc_html(get_post_meta($pt_review->ID, 'track_store', true));
    $track_date = esc_html(get_post_meta($pt_review->ID, 'track_date', true));
    ?>
    <table>
        <tr>
            <td style="width: 100%">Трек-номер</td>
            <td><input type="text" size="80" name="_pt_track_n" value="<?php echo $track_n; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Магазин</td>
            <td><input type="text" size="80" name="_pt_track_store" value="<?php echo $track_store; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Дата заказа</td>
            <td><input type="date" size="80" name="_pt_track_date" value="<?php echo $track_date; ?>" /></td>
        </tr>
    </table>
    <?php
}

// Сохраняем поля при сохранении записи
add_action('save_post', 'add_pt_review_fields', 10, 2);

function add_pt_review_fields($pt_review_id, $pt_review) {
    if ($pt_review->post_type == 'pt_reviews') {
        // Store data in post meta table if present in post data
        if (isset($_POST['_pt_track_n']) && $_POST['_pt_track_n'] != '') {
            update_post_meta($pt_review_id, 'track_n', $_POST['_pt_track_n']);
        }
        if (isset($_POST['_pt_track_store']) && $_POST['_pt_track_store'] != '') {
            update_post_meta($pt_review_id, 'track_store', $_POST['_pt_track_store']);
        }
        if (isset($_POST['_pt_track_date']) && $_POST['_pt_track_date'] != '') {
            update_post_meta($pt_review_id, 'track_date', $_POST['_pt_track_date']);
        }
    }
}
?>