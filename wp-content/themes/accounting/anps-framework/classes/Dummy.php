<?php
include_once 'Framework.php';
class Dummy extends Framework {

    public function select() {
        return get_option('anps_dummy');
    }

    public function save() {
        include_once(get_template_directory() . '/anps-framework/classes/AnpsImport.php');
        $dummy_xml = "dummy1";
        if(isset($_POST['dummy1'])) {
            $dummy_xml = "dummy1";
        } elseif(isset($_POST['dummy2'])) {
            $dummy_xml = "dummy2";
        } elseif(isset($_POST['dummy3'])) {
            $dummy_xml = "dummy3";
        } elseif(isset($_POST['dummy4'])) {
            $dummy_xml = "dummy4";
        } elseif(isset($_POST['dummy5'])) {
            $dummy_xml = "dummy5";
        } elseif(isset($_POST['dummy6'])) {
            $dummy_xml = "dummy6";
        } elseif(isset($_POST['dummy7'])) {
            $dummy_xml = "dummy7";
        } elseif(isset($_POST['dummy8'])) {
            $dummy_xml = "dummy8";
        } elseif(isset($_POST['dummy9'])) {
            $dummy_xml = "dummy9";
        } elseif(isset($_POST['dummy10'])) {
            $dummy_xml = "dummy10";
        }
        include(get_template_directory() . '/anps-framework/classes/importer/' . $dummy_xml . '/theme-options.php');

        /* Import dummy xml */
        include_once WP_PLUGIN_DIR.'/anps_theme_plugin/importer/wordpress-importer.php';
        $parse = new WP_Import();
        $parse->import(get_template_directory() . "/anps-framework/classes/importer/$dummy_xml/dummy.xml");

        /* set dummy to 1 */
        update_option('anps_dummy', 1);

        global $wp_rewrite;
        $blog_id = get_page_by_title("News")->ID;
        $error_id = get_page_by_title("404 Page")->ID;
        $first_id = get_page_by_title("Home")->ID;
        $arr = array(
            'error_page'=>$error_id
        );

        update_option($this->prefix.'page_setup', $arr);
        update_option('page_for_posts', $blog_id);
        update_option('page_on_front', $first_id);
        update_option('show_on_front', 'page');
        update_option('permalink_structure', '/%postname%/');
        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules();

        /* Set menu as primary */
	    $menu_id = wp_get_nav_menus();
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id[0]->term_id;
        set_theme_mod('nav_menu_locations', $locations);
        update_option('menu_check', true);

        /* Install all widgets */
        $anps_import_export->import_widgets_data(get_template_directory() . "/anps-framework/classes/importer/$dummy_xml/anps-widgets.txt");

        if($dummy_xml == 'dummy8' || $dummy_xml == 'dummy9') {
            /* Remove footer meta on home page */
            update_post_meta($first_id, 'anps_header_options_footer_margin', 'on');
        }

        /* Add revolution slider demo data */
        $this->__add_revslider($dummy_xml);
    }

    protected function __add_revslider($dummy_xml) {
        /* Check if slider is installed */
        if (function_exists('set_revslider_as_theme')) {
            $slider = new RevSlider();
            $slider_name = "main-slider";
            $response = $slider->importSliderFromPost('', '', get_template_directory() . "/anps-framework/classes/importer/$dummy_xml/main-slider.zip");
            //handle error
            if($response["success"] == false){
                $message = $response["error"];
                dmp("<b>Error: ".$message."</b>");
                exit;
            }
        } else {
            echo "Revolution slider is not active. Demo data for revolution slider can't be inserted.";
        }
    }
}
$dummy = new Dummy();
