<?php
// Add new custom font to Font Family selection in icon box module
function anps_icons() {
    $param = WPBMap::getParam( 'vc_icon', 'type' );
    $param['value'][__( 'Anpsthemes icons', 'accounting' )] = 'anps_icons';
    vc_update_shortcode_param( 'vc_icon', $param );
}
add_filter( 'init', 'anps_icons', 40 );

function anps_icons_add( $icons ) {
    $icons_anps = array(
        'Bank',
        'Bitcoin',
        'Calculator',
        'Certificate 1',
        'Certificate 2',
        'Chess',
        'Clipboard',
        'Coins',
        'Coins growth',
        'Credit cards',
        'Document signed',
        'Documents in progress',
        'Download doc',
        'Euro',
        'Folders',
        'Forecast',
        'Graph decline',
        'Graph growth',
        'Graph statistics',
        'Law',
        'Online payment',
        'Online transfer',
        'Percent',
        'Pie chart',
        'Research',
        'Retirement plan',
        'Safe',
        'Signature 1',
        'Signature 2',
        'Signature 3',
        'Signature 4',
        'Stamp 1',
        'Stamp 2',
        'Upload document',
        'USD',
        'Wallet',
    );

    $icons_vc = array();

    foreach($icons_anps as $icon) {
        $icons_vc[] = array('anps-icon-' . sanitize_title($icon) => $icon);
    }

    return $icons_vc;
}
add_filter( 'vc_iconpicker-type-anps_icons', 'anps_icons_add' );

/* Custom field for Table shortcode */
function table_field($settings, $value) {
    if($value == "") {
    	$value = "[table_head][table_row][table_heading_cell][/table_heading_cell][/table_row][/table_head][table_body][table_row][table_cell][/table_cell][/table_row][/table_body]";
    }

    $matches = array();
    $match_vals = array(
        'row-start' => array('[table_row]', '<tr>'),
        'row-end' => array('[/table_row]', '</tr>'),
        'heading-start' => array('[table_heading_cell]', '<th><input type="text" placeholder="' . __("Table heading", 'accounting') . '" value="'),
        'heading-end' => array('[/table_heading_cell]', '" /></th>'),
        'cell-start' => array('[table_cell]', '<td><input type="text" placeholder="' . __("Table cell", 'accounting') . '" value="'),
        'cell-end' => array('[/table_cell]', '" /></td>')
    );
    /* Get table head */
    $head = preg_match('/\[table_head\](.*?)\[\/table_head\]/s', $value, $matches);
    $head = $matches[1];
    $head = str_replace($match_vals['row-start'][0], $match_vals['row-start'][1], $head);
    $head = str_replace($match_vals['row-end'][0], $match_vals['row-end'][1], $head);
    $head = str_replace($match_vals['heading-start'][0], $match_vals['heading-start'][1], $head);
    $head = str_replace($match_vals['heading-end'][0], $match_vals['heading-end'][1], $head);
    /* Get table body */
    $body = preg_match('/\[table_body\](.*?)\[\/table_body\]/s', $value, $matches);
    $body = $matches[1];
    $body = str_replace($match_vals['row-start'][0], $match_vals['row-start'][1], $body);
    $body = str_replace($match_vals['row-end'][0], $match_vals['row-end'][1], $body);
    $body = str_replace($match_vals['cell-start'][0], $match_vals['cell-start'][1], $body);
    $body = str_replace($match_vals['cell-end'][0], $match_vals['cell-end'][1], $body);
    /* Get table foot */
    $foot = preg_match('/\[table_foot\](.*?)\[\/table_foot\]/s', $value, $matches);
    if( isset($matches[1]) ) {
    	$foot = $matches[1];
	}
    $foot = str_replace($match_vals['row-start'][0], $match_vals['row-start'][1], $foot);
    $foot = str_replace($match_vals['row-end'][0], $match_vals['row-end'][1], $foot);
    $foot = str_replace($match_vals['cell-start'][0], $match_vals['cell-start'][1], $foot);
    $foot = str_replace($match_vals['cell-end'][0], $match_vals['cell-end'][1], $foot);

    $number_of_rows = substr_count($value, '[table_row]');
    $number_of_cells = substr_count($head, '<th>');

    $data = '<input type="text" value="'.$value.'" name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select anps_custom_val '.$settings['param_name'].' '.$settings['type'].'" id="anps_custom_prod">';
    $data .= '<div class="anps-table-field">';
        $data .= '<div class="anps-table-field-remove-rows">';
        for($i=0;$i<$number_of_rows;$i++) {
        	if( $i == 0 ) {
        		$data .= '<button style="visibility: hidden;" title="' . esc_html__("Remove row", 'accounting') . '">&#215;</button>';
        	} else {
        		$data .= '<button title="' . esc_html__("Remove row", 'accounting') . '">&#215;</button>';
        	}
        }
        $data .= '</div>';
        $data .= '<table class="anps-table-field-remove-cells"><tbody><tr>';
        for($i=0;$i<$number_of_cells;$i++) {
            $data .= '<td><button title="' . esc_html__("Remove cell", 'accounting') . '">&#215;</button></td>';
        }
        $data .= '</tr></tbody></table>';
        $data .= '<table data-heading-placeholder="' . esc_html__("Table heading", 'accounting') . '" data-cell-placeholder="' . esc_html__("Table cell", 'accounting') . '" class="anps-table-field-vals">';
        $data .= '<thead>' . $head . '</thead>';
        $data .= '<tbody>' . $body . '</tbody>';
        //$data .= '<tfoot>' . $foot . '</tfoot>';
        $data .= '</table>';
        $data .= '<div class="anps-table-field-add-cells">';
            $data .= '<button title="' . esc_html__("Add cells", 'accounting') . '">+</button>';
        $data .= '</div>';
        $data .= '<div class="anps-table-field-add-rows">';
            $data .= '<button title="' . esc_html__("Add row", 'accounting') . '">+</button>';
        $data .= '</div>';
    $data .= '</div>';
    return $data;
}
vc_add_shortcode_param('table' , 'table_field', get_template_directory_uri() . "/js/vc-table.js", __FILE__);
/* Remove Default VC values */
$vc_values = array(
    'vc_cta_button2',
    'vc_message',
    'vc_facebook',
    'vc_tweetmeme',
    'vc_googleplus',
    'vc_pinterest',
    'vc_toggle',
    //'vc_gallery',
    //'vc_images_carousel',
    'vc_tour',
    'vc_accordion',
    'vc_posts_grid',
    'vc_carousel',
    'vc_posts_slider',
    'vc_widget_sidebar',
    'vc_button',
    'vc_cta_button',
    'vc_video',
    'vc_gmaps',
    'vc_raw_js',
    'vc_flickr',
    'vc_progress_bar',
    'vc_pie',
);
foreach ($vc_values as $vc_value) {
    vc_remove_element($vc_value);
}
/* Blog categories new parameter */
function blog_categories_settings_field($settings, $value) {
    $blog_data = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
    $blog_data .= '<option class="0" value="">'.esc_html__("All", 'accounting').'</option>';
    foreach(get_categories() as $val) {
        $selected = '';
        if ($value!='' && $val->slug == $value) {
             $selected = ' selected="selected"';
        }
        $blog_data .= '<option class="'.$val->slug.'" value="'.$val->slug.'"'.$selected.'>'.$val->name.'</option>';
    }
    $blog_data .= '</select>';
    return $blog_data;
}
vc_add_shortcode_param('blog_categories' , 'blog_categories_settings_field');
/* Portfolio categories new parameter */
function portfolio_categories_settings_field($settings, $value) {
    $categories = get_terms('portfolio_category');
    $data = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
    $data .= '<option class="0" value="0">'.esc_html__("All", 'accounting').'</option>';
    foreach($categories as $val) {
        $selected = '';
        if ($value!='' && $val->term_id == $value) {
             $selected = ' selected="selected"';
        }
        $data .= '<option class="'.$val->term_id.'" value="'.$val->term_id.'"'.$selected.'>'.$val->name.'</option>';
    }
    $data .= '</select>';
    return $data;
}
vc_add_shortcode_param('portfolio_categories' , 'portfolio_categories_settings_field');
/* Team categories new parameter */
function team_categories_settings_field($settings, $value) {
    $categories = get_terms('team_category');
    $data = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
    $data .= '<option value="0">'.esc_html__("All", 'accounting').'</option>';
    foreach($categories as $val) {
        $selected = '';
        if ($value!='' && $val->term_id == $value) {
             $selected = ' selected="selected"';
        }
        $data .= '<option class="'.$val->term_id.'" value="'.$val->term_id.'"'.$selected.'>'.$val->name.'</option>';
    }
    $data .= '</select>';
    return $data;
}
vc_add_shortcode_param('team_categories' , 'team_categories_settings_field');
/* All pages new parameter */
function all_pages_settings_field($settings, $value) {
    $data = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
    foreach(get_pages() as $val) {
        $selected = '';
        if ($value!='' && $val->ID == $value) {
             $selected = ' selected="selected"';
        }
        $data .= '<option class="'.$val->ID.'" value="'.$val->ID.'"'.$selected.'>'.$val->post_title.'</option>';
    }
    $data .= '</select>';
    return $data;
}
vc_add_shortcode_param('all_pages' , 'all_pages_settings_field');
/* VC Appointment */
$cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
$contact_form_array = array();
if($cf7) {
    foreach($cf7 as $cform) {
        $contact_form_array[$cform->post_title] = $cform->ID;
    }
} else {
    $contact_form_array[ esc_html__( 'No contact forms found', 'js_composer' ) ] = 0;
}
vc_map( array(
   'name' => esc_html__('Appointment', 'accounting'),
   'base' => 'appointment',
   'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_appointment.png",
   'category' => 'Accounting',
   'params' => array(
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image', 'accounting'),
            'param_name' => 'image_u',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Location', 'accounting'),
            'param_name' => 'location',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'text',
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Contact form', 'accounting'),
            'param_name' => 'contact_form',
            'value' => $contact_form_array,
            'save_always' => true,
            'admin_label' => true
        )
   )
) );
/* END VC Appointment */
/* VC Blog */
vc_map( array(
    'name' => esc_html__('Blog', 'accounting'),
    'base' => 'blog',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_blog.png",
    'params' => array(
        array(
            'type' => 'blog_categories',
            'heading' => esc_html__('Blog categories', 'accounting'),
            'param_name' => 'category',
            'description' => esc_html__('Select blog categories.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Posts per page', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter post per page.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order By', 'accounting'),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__('Default', 'accounting') => '',
                esc_html__('Date', 'accounting') => 'date',
                esc_html__('Id', 'accounting') => 'ID',
                esc_html__('Title', 'accounting') => 'title',
                esc_html__('Name', 'accounting') => 'name',
                esc_html__('Author', 'accounting') => 'author'
            ),
            'description' => esc_html__('Select order by.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order', 'accounting'),
            'param_name' => 'order',
            'value' => array(
                esc_html__('Default', 'accounting') => '',
                esc_html__('ASC', 'accounting') => 'ASC',
                esc_html__('DESC', 'accounting')=>'DESC'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Blog type', 'accounting'),
            'param_name' => 'type',
            'value' => array(
                esc_html__('', 'accounting') => '',
                esc_html__('Grid', 'accounting') => 'grid',
                esc_html__('Masonry', 'accounting') => 'masonry'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Columns', 'accounting'),
            'param_name' => 'columns',
            'value' => array(
                esc_html__('3 columns', 'accounting') => '3',
                esc_html__('4 columns', 'accounting') => '4'
            ),
            'save_always' => true,
            'admin_label' => true
        )
    )
) );
/* END VC Blog */
/* VC Portfolio */
vc_map( array(
    'name' => esc_html__('Portfolio', 'accounting'),
    'base' => 'portfolio',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_portfolio.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of portfolio posts', 'accounting'),
            'param_name' => 'per_page',
            'value' => "",
            'description' => esc_html__('Enter number of portfolio posts.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show in row', 'accounting'),
            'param_name' => 'columns',
            'value' => array(
                esc_html__('6', 'accounting') => '6',
                esc_html__('4', 'accounting') => '4',
                esc_html__('3', 'accounting') => '3',
                esc_html__('2', 'accounting') => '2'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'portfolio_categories',
            'heading' => esc_html__('Portfolio categories', 'accounting'),
            'param_name' => 'category',
            'description' => esc_html__('Select portfolio categories.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Filter', 'accounting'),
            'param_name' => 'filter',
            'value' => array(
                esc_html__('On', 'accounting') => 'on',
                esc_html__('Off', 'accounting') => 'off'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Filter style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => 'style-1',
                esc_html__('Style 2', 'accounting') => 'style-2'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Portfolio type', 'accounting'),
            'param_name' => 'type',
            'value' => array(
                esc_html__('Default', 'accounting') => 'default',
                esc_html__('Classic', 'accounting') => 'classic',
                esc_html__('Random', 'accounting' )=> 'random'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order By', 'accounting'),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__('Default', 'accounting') => 'default',
                esc_html__('Date', 'accounting') => 'date',
                esc_html__('Id', 'accounting') => 'ID',
                esc_html__('Title', 'accounting') => 'title',
                esc_html__('Name', 'accounting') => 'name'
            ),
            'description' => esc_html__('Enter order by.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order', 'accounting'),
            'param_name' => 'order',
            'value' => array(
                esc_html__('Default', 'accounting') => '',
                esc_html__('ASC', 'accounting') => 'ASC',
                esc_html__('DESC', 'accounting') => 'DESC'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Mobile view', 'accounting'),
            'param_name' => 'mobile_class',
            'value' => array(
                esc_html__('2 columns', 'accounting') => '2',
                esc_html__('1 column', 'accounting') => '1'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Filter color', 'accounting'),
            'param_name' => 'filter_color',
            'value' => '',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        )
    )
));
/* END VC Portfolio */
/* VC team */
vc_map( array(
    'name' => esc_html__('Team', 'accounting'),
    'base' => 'team',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_team.png",
    'params' => array(
        array(
            'type' => 'team_categories',
            'heading' => esc_html__('Team categories', 'accounting'),
            'param_name' => 'category',
            'description' => esc_html__('Select team category.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Number of items in column', 'accounting'),
            'param_name' => 'columns',
            'value' => array(
                esc_html__('4', 'accounting') => '4',
                esc_html__('2', 'accounting') => '2',
                esc_html__('3', 'accounting') => '3',
                esc_html__('6', 'accounting') => '6'
            ),
            'description' => esc_html__("Enter number of team item in column.", 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Use large images', 'accounting'),
            'param_name' => 'large_images',
            'description' => esc_html__('Use full sized images instead of column sized ones', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Member pages', 'accounting'),
            'param_name' => 'member_pages',
            'description' => esc_html__('Add links to member pages.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of team members', 'accounting'),
            'param_name' => 'number_items',
            'value' => '',
            'description' => esc_html__('Enter number of team members (if you want all than enter -1).', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Team member id/s', 'accounting'),
            'param_name' => 'ids',
            'value' => '',
            'description' => esc_html__('Enter team member id/s. Example: 1,2,3', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC team */
/* VC recent blog */
vc_map( array(
    'name' => esc_html__('Recent blog', 'accounting'),
    'base' => 'recent_blog',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_recentblog.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of blog posts', 'accounting'),
            'param_name' => 'number',
            'value' => '3',
            'description' => esc_html__('Enter number of recent blog posts.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Number of columns in a row', 'accounting'),
            'param_name' => 'col_number',
            'value' => array(__("2", 'accounting')=>'2', __("3", 'accounting')=>'3', __("4", 'accounting')=>'4',  __("6", 'accounting')=>'6'),
            'std' => '3',
            'description' => esc_html__('Select number of items in a row.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting')=>'1',
                esc_html__('Style 2', 'accounting')=>'2',
                esc_html__('Style 3', 'accounting')=>'3',
                esc_html__('Style 4', 'accounting')=>'4',
                esc_html__('Style 5', 'accounting')=>'5'
            ),
            'std' => '1',
            'description' => esc_html__('Select recent blog posts style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Title color', 'accounting'),
            'param_name' => 'title_color',
            'value' => '',
            'group' => esc_html__('Design Options', "accounting"),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Text color', 'accounting'),
            'param_name' => 'text_color',
            'value' => '',
            'group' => esc_html__('Design Options', "accounting"),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Links & divider color', 'accounting'),
            'param_name' => 'links_color',
            'value' => '',
            'group' => esc_html__('Design Options', "accounting"),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Backgoround color', 'accounting'),
            'param_name' => 'bg_color',
            'value' => '',
            'group' => esc_html__('Design Options', "accounting"),
            'admin_label' => false
        ),
    )
) );
/* END VC recent blog */
/* VC cta */
vc_map( array(
    'name' => esc_html__('Call to action', 'accounting'),
    'base' => 'anps_cta',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_statement.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Subtitle', 'accounting'),
            'param_name' => 'subtitle',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button text', 'accounting'),
            'param_name' => 'button_text',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button link', 'accounting'),
            'param_name' => 'button_link',
            'value' => '#',
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Button target', 'accounting'),
            'param_name' => 'button_target',
            'value' => array(
                '_self' => '_self',
                '_blank' => '_blank',
                '_parent' => '_parent',
                '_top' => '_top',
            ),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
            'save_always' => true
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Background color', 'accounting'),
            'param_name' => 'bg_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Title color', 'accounting'),
            'param_name' => 'title_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Subtitle color', 'accounting'),
            'param_name' => 'subtitle_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Button color', 'accounting'),
            'param_name' => 'button_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => "colorpicker",
            'heading' => esc_html__('Button background color', 'accounting'),
            'param_name' => 'button_bg_color',
            'group' => esc_html__('Design Options', 'accounting'),
        ),
    )
) );
/* END VC cta */
/* VC recent portfolio slider */
vc_map( array(
    'name' => esc_html__('Recent portfolio slider', 'accounting'),
    'icon' => get_template_directory_uri()."/images/visual-composer/shortcode_icons-recent.png",
    'base' => 'recent_portfolio_slider',
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'recent_title',
            'value' => '',
            'description' => esc_html__('Recent portfolio title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Title color', 'accounting'),
            'param_name' => 'title_color',
            'value' => '#c1c1c1',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Next/Prev color', 'accounting'),
            'param_name' => 'nex_prev_color',
            'value' => '#c1c1c1',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Next/Prev background color', 'accounting'),
            'param_name' => 'nex_prev_bg_color',
            'value' => '#3d3d3d',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of portfolio posts', 'accounting'),
            'param_name' => 'number',
            'value' => '',
            'description' => esc_html__('Enter number of recent portfolio posts. If you want to display all posts, leave this field empty.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Number in row', 'accounting'),
            'param_name' => 'number_in_row',
            'value' => array(
                esc_html__('3', 'accounting') => '3',
                esc_html__('4', 'accounting') => '4',
                esc_html__('5', 'accounting') => '5',
                esc_html__('6', 'accounting') => '6'
            ),
            'std' => '4',
            'description' => esc_html__('Select number of items in row.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'portfolio_categories',
            'heading' => esc_html__('Portfolio categories', 'accounting'),
            'param_name' => 'category',
            'description' => esc_html__('Select portfolio categories.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC recent portfolio slider */
/* VC recent portfolio */
vc_map( array(
    'name' => esc_html__('Recent portfolio', 'accounting'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_recentportfolio.png",
    'base' => 'recent_portfolio',
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of portfolio posts', 'accounting'),
            'param_name' => 'number',
            'value' => '5',
            'description' => esc_html__('Enter number of recent portfolio posts.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'portfolio_categories',
            'heading' => esc_html__('Portfolio categories', 'accounting'),
            'param_name' => 'category',
            'description' => esc_html__('Select portfolio categories.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Mobile view', 'accounting'),
            'param_name' => 'mobile_class',
            'value' => array(
                esc_html__('2 columns', 'accounting') => '2',
                esc_html__('1 column', 'accounting') => '1'
            ),
            'save_always' => true,
            'admin_label' => true
        )
    )
) );
/* END VC recent portfolio */
/* VC twitter */
vc_map( array(
    'name' => esc_html__('Twitter', 'accounting'),
    'base' => 'twitter',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_twitter.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Slug', 'accounting'),
            'param_name' => 'slug',
            'description' => esc_html__('This is used for both for none page navigation and the parallax effect (if you do not have the navigation need you enter a unique slug if you want parallax effect to function)', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'value' => 'Stay tuned, follow us on Twitter',
            'description' => esc_html__('Enter twitter title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Parallax', 'accounting'),
            'param_name' => 'parallax',
            'value' => array(
                esc_html__('False', 'accounting') => 'false',
                esc_html__('True', 'accounting') => 'true'
            ),
            'description' => esc_html__('Enter parallax.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Parallax overlay', 'accounting'),
            'param_name' => 'parallax_overlay',
            'value' => array(
                esc_html__('False', 'accounting') => '',
                esc_html__('True', 'accounting') => 'true'
            ),
            'description' => esc_html__('Parallax overlay.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Background image url', 'accounting'),
            'param_name' => 'image',
            'description' => esc_html__('Enter background image url.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Background image', 'accounting'),
            'param_name' => 'image_u',
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Background color', 'accounting'),
            'param_name' => 'color',
            'value' => '',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Twitter username', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter twitter username.', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC twitter */
/* VC alert */
vc_map( array(
    'name' => esc_html__('Alert', 'accounting'),
    'base' => 'alert',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_alert.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter alert text.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Icon', 'accounting'),
            'param_name' => 'type',
            'value' => array(
                esc_html__('', 'accounting') => '',
                esc_html__('Warning', 'accounting') => 'warning',
                esc_html__('Info', 'accounting') => 'info',
                esc_html__('Success', 'accounting') => 'success',
                esc_html__('Useful', 'accounting') => 'useful',
                esc_html__('Normal', 'accounting' )=> 'normal'
            ),
            'save_always' => true,
            'admin_label' => true
        )
    )
));
/* END VC alert */
/* VC counter */
vc_map( array(
    'name' => esc_html__('Counter', 'accounting'),
    'base' => 'counter',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_counter.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter counter text.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'save_always' => true,
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Max number', 'accounting'),
            'param_name' => 'max',
            'value' => '',
            'description' => esc_html__('Enter max number.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Min number', 'accounting'),
            'param_name' => 'min',
            'value' => '0',
            'description' => esc_html__('Enter min number.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Icon color', 'accounting'),
            'param_name' => 'icon_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Number color', 'accounting'),
            'param_name' => 'number_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Subtitle color', 'accounting'),
            'param_name' => 'subtitle_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Border color', 'accounting'),
            'param_name' => 'border_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC counter */
/* VC progress */
vc_map( array(
    'name' => esc_html__('Progress', 'accounting'),
    'base' => 'progress',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_progress.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Progress procent', 'accounting'),
            'param_name' => 'procent',
            'value' => '',
            'description' => esc_html__('Enter progress procent.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Striped', 'accounting'),
            'param_name' => 'striped',
            'value' => array(
                esc_html__('No', 'accounting') => '',
                esc_html__('Yes', 'accounting') => 'true'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Active', 'accounting'),
            'param_name' => 'active',
            'value' => array(
                esc_html__('No', 'accounting') => '',
                esc_html__('Yes', 'accounting') => 'true'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Color class', 'accounting'),
            'param_name' => 'color_class',
            'value' => array(
                esc_html__('Success', 'accounting') => 'progress-bar-success',
                esc_html__('Info', 'accounting') => 'progress-bar-info',
                esc_html__('Warning', 'accounting') => 'progress-bar-warning',
                esc_html__('Danger', 'accounting') => 'progress-bar-danger'
            ),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Progress title', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter progress title.', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC progress */
/* VC icon */
vc_map( array(
    'name' => esc_html__('Icon', 'accounting'),
    'base' => 'icon',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_icon.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Subtitle', 'accounting'),
            'param_name' => 'subtitle',
            'admin_label' => true
        ),
        array(
            'type' => 'textarea_html',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Link', 'accounting'),
            'param_name' => 'url',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Target', 'accounting'),
            'param_name' => 'target',
            'value' => '_self',
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
                esc_html__( 'Accounting icons', 'js_composer' ) => 'anps_icons',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
            'save_always' => true
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'accounting' ),
            'param_name' => 'icon_anps_icons',
            'settings' => array(
                'emptyIcon' => true,
                'type' => 'anps_icons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'anps_icons',
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Class', 'accounting'),
            'param_name' => 'class',
            'value' => array(
                esc_html__('Style 1', 'accounting') => '',
                esc_html__('Style 2', 'accounting') => 'style-2',
                esc_html__('Style 3', 'accounting') => 'style-3'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Position', 'accounting'),
            'param_name' => 'position',
            'value' => array(
                esc_html__('Left', 'accounting') => 'left',
                esc_html__('Right', 'accounting') => 'right'
            ),
            'save_always' => true,
            'admin_label' => false,
            'dependency'  => array(
                'element' => 'class',
                'value'   => array(
                    'style-2',
                )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Position', 'accounting'),
            'param_name' => 'position_2',
            'value' => array(
                esc_html__('Left', 'accounting') => 'left',
                esc_html__('Right', 'accounting') => 'right',
                esc_html__('Center', 'accounting') => 'center',
            ),
            'save_always' => true,
            'admin_label' => false,
            'dependency'  => array(
                'element' => 'class',
                'value'   => array(
                    'style-3',
                )
            ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Icon size', 'accounting'),
            'param_name' => 'icon_size',
            'value' => '22',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Title color', 'accounting'),
            'param_name' => 'title_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Subtitle color', 'accounting'),
            'param_name' => 'subtitle_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
      array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Text color', 'accounting'),
            'param_name' => 'text_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
      ),
      array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Icon color', 'accounting'),
            'param_name' => 'icon_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
       ),
       array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Icon background color', 'accounting'),
            'param_name' => 'icon_bg_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
      )
   )
) );
/* END VC icon */
/* VC quote */
vc_map( array(
    'name' => esc_html__('Quote', 'accounting'),
    'base' => 'quote',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_quote.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Quote text', 'accounting'),
            'param_name' => 'content',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => 'style-1',
                esc_html__('Style 2', 'accounting') => 'style-2'
            ),
            'save_always' => true,
            'admin_label' => false
        )
    )
) );
/* END VC quote */
/* VC color */
vc_map( array(
    'name' => esc_html__('Color', 'accounting'),
    'base' => 'color',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_color.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Color text', 'accounting'),
            'param_name' => 'content',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => '',
                esc_html__('Style 2', 'accounting') => 'style-2'
            ),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Custom color', 'accounting'),
            'param_name' => 'custom',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC color */
/* VC dropcaps */
vc_map( array(
    'name' => esc_html__('Dropcaps', 'accounting'),
    'base' => 'dropcaps',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_dropcaps.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Dropcaps text', 'accounting'),
            'param_name' => 'content',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => '',
                esc_html__('Style 2', 'accounting') => 'style-2'
            ),
            'save_always' => true,
            'admin_label' => false
        )
    )
) );
/* END VC dropcaps */
/* VC statement */
vc_map( array(
    'name' => esc_html__('Statement', 'accounting'),
    'base' => 'statement',
    'content_element' => true,
    'is_container' => true,
    'js_view' => 'VcColumnView',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_statement.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Slug', 'accounting'),
            'param_name' => 'slug',
            'description' => esc_html__('This is used for both for none page navigation and the parallax effect (if you do not have the navigation need you enter a unique slug if you want parallax effect to function)', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Parallax', 'accounting'),
            'param_name' => 'parallax',
            'value' => array(
                esc_html__('False', 'accounting') => 'false',
                esc_html__('True', 'accounting') => 'true'
            ),
            'description' => esc_html__('Enter parallax.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Parallax overlay', 'accounting'),
            'param_name' => 'parallax_overlay',
            'value' => array(
                esc_html__('False', 'accounting') => '',
                esc_html__('True', 'accounting') => 'true'
            ),
            'description' => esc_html__('Parallax overlay.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Background image url', 'accounting'),
            'param_name' => 'image',
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Background image', 'accounting'),
            'param_name' => 'image_u',
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Background color', 'accounting'),
            'param_name' => 'color',
            'value' => '',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC statement */
/* VC heading */
vc_map( array(
    'name' => esc_html__('Heading', 'accounting'),
    'base' => 'heading',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_heading.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Size', 'accounting'),
            'param_name' => 'size',
            'value' => array(
                esc_html__('H1', 'accounting') => '1',
                esc_html__('H2', 'accounting') => '2',
                esc_html__('H3', 'accounting') => '3',
                esc_html__('H4', 'accounting') => '4',
                esc_html__('H5', 'accounting') => '5'
            ),
            'description' => esc_html__('Enter title size.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Heading class', 'accounting'),
            'param_name' => 'heading_class',
            'value' => array(
                esc_html__('Middle heading', 'accounting') => 'heading',
                esc_html__('Content heading', 'accounting') => 'content_heading',
                esc_html__('Left heading', 'accounting') => 'style-3'
            ),
            'description' => esc_html__('Choose heading.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Heading style', 'accounting'),
            'param_name' => 'heading_style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => 'style-1',
                esc_html__('Style 2', 'accounting') => 'divider-sm',
                esc_html__('Style 3', 'accounting') => 'divider-lg'
            ),
            'description' => esc_html__('Choose heading style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Id', 'accounting'),
            'param_name' => 'h_id',
            'value' => '',
            'description' => esc_html__('Enter id.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Class', 'accounting'),
            'param_name' => 'h_class',
            'value' => '',
            'description' => esc_html__('Enter class.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Color', 'accounting'),
            'param_name' => 'color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC heading */
/* VC Google maps (as parent) */
vc_map( array(
    'name' => esc_html__('Google maps', 'accounting'),
    'base' => 'google_maps',
    'category' => 'Accounting',
    'content_element' => true,
    'is_container' => true,
    'as_parent' => array('only' => 'google_maps_item'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_googlemaps.png",
    'js_view' => 'VcColumnView',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Zoom', 'accounting'),
            'param_name' => 'zoom',
            'value' => '15',
            'description' => esc_html__('Enter zoom.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__('Map Type', 'accounting'),
            'param_name' => 'map_type',
            'value'      => array(
                esc_html__('Road map', 'accounting')  => 'ROADMAP',
                esc_html__('Satellite', 'accounting') => 'SATELLITE',
                esc_html__('Hybrid', 'accounting')    => 'HYBRID',
                esc_html__('Terrain', 'accounting')   => 'TERRAIN'
            ),
            'description' => esc_html__('Choose between four types of maps.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Height', 'accounting'),
            'param_name' => 'height',
            'value' => '550',
            'description' => esc_html__('Enter height in px.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Disable scrolling', 'accounting'),
            'param_name' => 'scroll',
            'description' => esc_html__('Disable scrolling and dragging (mobile).', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'description' => esc_html__('Custom styles', 'accounting'),
            'admin_label' => false
        ),
     )
) );
/* END VC Google maps */
/* VC Google maps item (as child) */
vc_map( array(
    'name' => esc_html__('Google maps item', 'accounting'),
    'base' => 'google_maps_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_googlemaps.png",
    'as_child' => array('only' => 'google_maps'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Location', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter address.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Show marker at center', 'accounting'),
            'param_name' => 'marker_center',
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Pin', 'accounting'),
            'param_name' => 'pin',
            'description' => esc_html__('Select or upload pin icon.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Info', 'accounting'),
            'param_name' => 'info',
            'value' => '',
            'description' => esc_html__('Enter info about location.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC Google maps item */
/* VC vimeo */
vc_map( array(
    'name' => esc_html__('Vimeo', 'accounting'),
    'base' => 'vimeo',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_vimeo.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Video id', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter vimeo video id.', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC vimeo */
/* VC youtube */
vc_map( array(
    "name" => __("Youtube", 'accounting'),
    "base" => "youtube",
    "class" => "",
    "icon" => "icon-wpb-film-youtube",
    "category" => 'Accounting',
    "params" => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Video id', 'accounting'),
            'param_name' => 'content',
            'value' => '',
            'description' => esc_html__('Enter youtube video id.', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC youtube */
/* VC social icons */
vc_map( array(
    'name' => esc_html__('Social icons', 'accounting'),
    'base' => 'social_icons',
    'content_element' => true,
    'as_parent' => array('only' => 'social_icon_item'),
    'show_settings_on_create' => false,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_social.png",
    'js_view' => 'VcColumnView'
) );
/* END VC social icons */
/* VC social icon */
vc_map( array(
    'name' => esc_html__('Social icon item', 'accounting'),
    'base' => 'social_icon_item',
    'content_element' => true,
    'is_container' => true,
    'category' => 'Accounting',
    'as_child' => array('only' => 'social_icons'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_social.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Url', 'accounting'),
            'param_name' => 'url',
            'description' => esc_html__('Enter url.', 'accounting'),
            'value' => '#',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'description' => esc_html__('Text that will display on mouse hover and the alternative icon text.', 'accounting'),
            'value' => '',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
            "save_always" => true
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Target', 'accounting'),
            'param_name' => 'target',
            'value' => '_blank',
            'description' => esc_html__('Enter target.', 'accounting'),
            'admin_label' => false
        )
    )
));
/* END VC social icon */
/* VC contact info */
vc_map( array(
    'name' => esc_html__('Contact info', 'accounting'),
    'base' => 'contact_info',
    'as_parent' => array('only' => 'contact_info_item'),
    'content_element' => true,
    'show_settings_on_create' => false,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_contactinfo.png",
    'js_view' => 'VcColumnView'
) );
/* END VC contact info */
/* VC contact info item */
vc_map( array(
    'name' => esc_html__('Contact info item', 'accounting'),
    'base' => 'contact_info_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_contactinfo.png",
    'as_child' => array('only' => 'contact_info'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter text.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
            "save_always" => true
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        )
    )
) );
/* END VC contact info item */
/* VC Faq */
vc_map( array(
    'name' => esc_html__('Faq', 'accounting'),
    'base' => 'faq',
    'as_parent' => array('only' => 'faq_item'),
    'content_element' => true,
    'show_settings_on_create' => false,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_faq.png",
    'js_view' => 'VcColumnView'
) );
/* END VC Faq */
/* VC Faq item */
vc_map( array(
    'name' => esc_html__('Faq item', 'accounting'),
    'base' => 'faq_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_faq.png",
    'as_child' => array('only' => 'faq'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'description' => esc_html__('Enter faq title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Answer title', 'accounting'),
            'param_name' => 'answer_title',
            'description' => esc_html__('Enter faq answer title.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Faq content', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter faq text content.', 'accounting'),
            'admin_label' => false
        )
    )
));
/* END VC Faq item */
/* VC logos (as parent) */
vc_map( array(
    'name' => esc_html__('Logos', 'accounting'),
    'base' => 'logos',
    'as_parent' => array('only' => 'logo'),
    'content_element' => true,
    'is_container' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_logo.png",
    'js_view' => 'VcColumnView',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Logos in row', 'accounting'),
            'param_name' => 'in_row',
            'description' => esc_html__('Logos in one row.', 'accounting'),
            'value' => '3',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' => array(
                esc_html__('Style 1', 'accounting') => 'style-1',
                esc_html__('Carousel Logos', 'accounting') => 'style-2'
            ),
            'description' => esc_html__('Select logos style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        )
    )
) );
/* END VC logos*/
/* VC logo (as child) */
vc_map( array(
    'name' => esc_html__('Logo', 'accounting'),
    'base' => 'logo',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_logo.png",
    'as_child' => array('only' => 'logos'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Image url', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter image url.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image', 'accounting'),
            'param_name' => 'image_u',
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image on hover', 'accounting'),
            'param_name' => 'image_u_hover',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Url', 'accounting'),
            'param_name' => 'url',
            'description' => esc_html__('Logo url.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Alt', 'accounting'),
            'param_name' => 'alt',
            'description' => esc_html__('Logo alt.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* VC About us (as parent) */
vc_map( array(
    'name' => esc_html__('About us', 'accounting'),
    'base' => 'about_us',
    'category' => 'Accounting',
    'content_element' => true,
    'is_container' => true,
    'as_parent' => array('only' => 'about_us_item'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_team.png",
    'js_view' => 'VcColumnView',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Subtitle', 'accounting'),
            'param_name' => 'subtitle',
            'admin_label' => true
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Description', 'accounting'),
            'param_name' => 'desc',
            'admin_label' => false
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'text',
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image', 'accounting'),
            'param_name' => 'image',
            'description' => esc_html__('Choose image.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Image position', 'accounting'),
            'param_name' => 'image_position',
            'value' => array(
                esc_html__('Right', 'accounting')=>'right',
                esc_html__('Left', 'accounting')=>'left',
            ),
            'description' => esc_html__('Select testimonials style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
    )
) );
/* END VC About us */
/* VC About us item (as child) */
vc_map( array(
    'name' => esc_html__('About us item', 'accounting'),
    'base' => 'about_us_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_timeline.png",
    'as_child' => array('only' => 'about_us'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Year', 'accounting'),
            'param_name' => 'year',
            'value' => '2017',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
    )
) );
/* END VC About us item */
/* VC Timeline (as parent) */
vc_map( array(
    'name' => esc_html__('Timeline', 'accounting'),
    'base' => 'timeline',
    'category' => 'Accounting',
    'content_element' => true,
    'is_container' => true,
    'show_settings_on_create' => false,
    'as_parent' => array('only' => 'timeline_item'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_timeline.png",
    'js_view' => 'VcColumnView',
    'params' => array()
) );
/* END VC Timeline */
/* VC Timeline item (as child) */
vc_map( array(
    'name' => esc_html__('Timeline item', 'accounting'),
    'base' => 'timeline_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_timeline.png",
    'as_child' => array('only' => 'timeline'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Year', 'accounting'),
            'param_name' => 'year',
            'value' => '2017',
            'description' => esc_html__('Enter year number.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'description' => esc_html__('Enter title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Content', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter content.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC Timeline item */
/* VC testimonials (as parent) */
vc_map( array(
    'name' => esc_html__('Testimonials', 'accounting'),
    'base' => 'testimonials',
    'as_parent' => array('only' => 'testimonial'),
    'content_element' => true,
    'show_settings_on_create' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_testimonials.png",
    'js_view' => 'VcColumnView',
    'params' => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            "value" => array(
                esc_html__('Style 1', 'accounting') => 'style-1',
                esc_html__('Style 2', 'accounting') => 'style-2',
                esc_html__('Style 3', 'accounting') => 'style-3',
                esc_html__('Style 4', 'accounting') => 'style-4',
                esc_html__('Style 5', 'accounting') => 'style-5'
            ),
            'description' => esc_html__('Select testimonials style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Text color', 'accounting'),
            'param_name' => 'text_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('style-4', 'style-5'),
            ),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Secondary color', 'accounting'),
            'param_name' => 'secondary_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('style-4', 'style-5'),
            ),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Desktop minimum height', 'accounting'),
            'param_name' => 'min_height_lg',
            'description' => esc_html__('Set the minimum element height for large screens.', 'accounting'),
            'group' => esc_html__('Design Options', 'accounting'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('style-4', 'style-5'),
            ),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Background color', 'accounting'),
            'param_name' => 'bg_color',
            'group' => esc_html__('Design Options', 'accounting'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('style-5'),
            ),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Background opacity', 'accounting'),
            'description' => esc_html__('Enter a number between 0 and 100', 'accounting'),
            'param_name' => 'bg_opacity',
            'group' => esc_html__('Design Options', 'accounting'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('style-5'),
            ),
            'admin_label' => false
        ),
    )
) );
/* END VC testimonials */
/* VC testimonial (as child) */
vc_map( array(
    'name' => esc_html__('Testimonial item', 'accounting'),
    'base' => 'testimonial',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_testimonials.png",
    'as_child' => array('only' => 'testimonials'),
    'params' => array(
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Testimonial text', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter user testimonial text', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('User name', 'accounting'),
            'param_name' => 'user_name',
            'description' => esc_html__('Enter user name', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Position', 'accounting'),
            'param_name' => 'position',
            'description' => esc_html__('Applicable only for style-3 testimonials.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('User url', 'accounting'),
            'param_name' => 'user_url',
            'description' => esc_html__("Enter user url", 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('User image', 'accounting'),
            'param_name' => 'image_u',
            'description' => esc_html__('Choose image.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('User image url', 'accounting'),
            'param_name' => 'image',
            'description' => esc_html__('Enter image url.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC testimonial */
/* VC button */
vc_map( array(
    'name' => esc_html__('Button', 'accounting'),
    'base' => 'button',
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_button.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter button text.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Link', 'accounting'),
            'param_name' => 'link',
            'value' => '#',
            'description' => esc_html__('Enter button link.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Target', 'accounting'),
            'param_name' => 'target',
            'value' => '_self',
            'description' => esc_html__('Enter button target.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Size', 'accounting'),
            'param_name' => 'size',
            'value' => array(
                esc_html__('Small', 'accounting') => 'small',
                esc_html__('Medium', 'accounting') => 'medium',
                esc_html__('Large', 'accounting') => 'large'
            ),
            'description' => esc_html__('Enter button size.', 'accounting'),
            'save_always' => true,
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style_button',
            'value' => array(
                esc_html__('Style1', 'accounting') => 'style-1',
                esc_html__('Style2', 'accounting') => 'style-2',
                esc_html__('Style3', 'accounting') => 'style-3',
                esc_html__('Style4', 'accounting') => 'style-4',
                esc_html__('Style5', 'accounting') => 'style-5',
                esc_html__('Style6', 'accounting') => 'style-6'
            ),
            'description' => esc_html__('Enter button style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Padding', 'accounting'),
            'param_name' => 'padding',
            'group' => esc_html__('Design Options', 'accounting'),
            'description' => esc_html__('Enter padding size. Example: 7px 15px', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Font size', 'accounting'),
            'param_name' => 'font_size',
            'group' => esc_html__('Design Options', 'accounting'),
            'description' => esc_html__('Enter font size. Example: 14px', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Color', 'accounting'),
            'param_name' => 'color',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__('Background', 'accounting'),
            'param_name' => 'background',
            'group' => esc_html__('Design Options', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'js_composer' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
                esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
                esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
                esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
                esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'js_composer' ),
            'save_always' => true
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'settings' => array(
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'settings' => array(
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'settings' => array(
                'type' => 'typicons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'settings' => array(
                'type' => 'entypo',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'settings' => array(
                'type' => 'linecons',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'settings' => array(
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
        )
     )
) );
/* END VC button */
/* VC Pricing table */
vc_map( array(
    'name' => esc_html__('Pricing table', 'accounting'),
    'base' => 'pricing_table',
    'content_element' => true,
    'category' => 'Accounting',
    'as_parent' => array('only' => 'pricing_table_item'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_pricingtable.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'description' => esc_html__('Enter pricing table title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Currency', 'accounting'),
            'param_name' => 'currency',
            'value' => '',
            'description' => esc_html__('Enter currency symbol.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Price', 'accounting'),
            'param_name' => 'price',
            'value' => '0',
            'description' => esc_html__('Enter price.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Period', 'accounting'),
            'param_name' => 'period',
            'description' => esc_html__('Enter period (optional).', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button Text', 'accounting'),
            'param_name' => 'button_text',
            'description' => esc_html__('Enter pricing table button text.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button Url', 'accounting'),
            'param_name' => 'button_url',
            'description' => esc_html__('Enter pricing table button url.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Featured', 'accounting'),
            'param_name' => 'featured',
            'save_always' => true,
            'value' =>array(
                esc_html__('No', 'accounting')=>'',
                esc_html__('yes', 'accounting')=>'true'),
            'description' => esc_html__('Featured pricing table.', 'accounting'),
            'admin_label' => false
        )
    ),
    'js_view' => 'VcColumnView'
) );
/* END VC Pricing table */
/* VC Pricing item */
vc_map( array(
    'name' => esc_html__('Pricing item', 'accounting'),
    'base' => 'pricing_table_item',
    'content_element' => true,
    'is_container' => true,
    'category' => 'Accounting',
    'as_child' => array('only' => 'pricing_table'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_pricingtable.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter pricing item text.', 'accounting'),
            'admin_label' => true
       )
    )
));
/* END VC Pricing item */
/* VC list (as parent) */
vc_map( array(
    'name' => esc_html__('List', 'accounting'),
    'base' => 'anps_list',
    'content_element' => true,
    'category' => 'Accounting',
    'as_parent' => array('only' => 'list_item'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_list.png",
    'js_view' => 'VcColumnView',
    'params' => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('List icon', 'accounting'),
            'param_name' => 'class',
            'save_always' => true,
            'value' =>array(
                esc_html__('Default', 'accounting') => '',
                esc_html__('Circle arrow', 'accounting') => 'circle-arrow',
                esc_html__('Triangle', 'accounting') => 'triangle',
                esc_html__('Hand', 'accounting') => 'hand',
                esc_html__('Square', 'accounting') => 'square',
                esc_html__('Arrow', 'accounting') => 'anps_arrow',
                esc_html__('Circle', 'accounting') => 'circle',
                esc_html__('Circle check', 'accounting') => 'circle-check',
            	esc_html__('Number', 'accounting') => 'number'
            ),
            'description' => esc_html__('Select type.', 'accounting'),
            'admin_label' => true
        )
    )
) );
/* END VC list */
/* VC list item (as child) */
vc_map( array(
    'name' => esc_html__('List item', 'accounting'),
    'base' => 'list_item',
    'content_element' => true,
    'category' => 'Accounting',
    'as_child' => array('only' => 'anps_list'),
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_list.png",
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Text', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter list text.', 'accounting'),
            'admin_label' => true
        )
    )
));
/* END VC list item */
/* VC accordion (as parent) */
vc_map( array(
    'name' => esc_html__('Accordion/Toggle', 'accounting'),
    'base' => 'accordion',
    'content_element' => true,
    'as_parent' => array('only' => 'accordion_item'),
    'show_settings_on_create' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_accordion.png",
    'params' => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Opened', 'accounting'),
            'param_name' => 'opened',
            'value' =>array(
                esc_html__('No', 'accounting') => 'false',
                esc_html__('Yes', 'accounting') => 'true'
            ),
            'description' => esc_html__('Opened Accordion/Toggle item.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Style', 'accounting'),
            'param_name' => 'style',
            'value' =>array(
                esc_html__('Style 1', 'accounting') => '',
                esc_html__('Style 2', 'accounting') => 'style-2'
            ),
            'description' => esc_html__('Select style.', 'accounting'),
            'save_always' => true,
            'admin_label' => false
        )
    ),
    'js_view' => 'VcColumnView'
) );
/* END VC accordion */
/* VC accordion item (as child) */
vc_map( array(
    'name' => esc_html__('Accordion/Toggle item', 'accounting'),
    'base' => 'accordion_item',
    'content_element' => true,
    'category' => 'Accounting',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_accordion.png",
    'as_child' => array('only' => 'accordion'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'description' => esc_html__('Accordion item title.', 'accounting'),
            'admin_label' => true
        ),
        array(
            'type' => 'textarea_html',
            'heading' => esC_html__('Content', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter accordion/toggle content.', 'accounting'),
            'admin_label' => false
        )
    )
) );
/* END VC accordion item */
/* VC Error 404 */
vc_map( array(
    'name' => esc_html__('Error 404', 'accounting'),
    'base' => 'error_404',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_error404.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Subtitle', 'accounting'),
            'param_name' => 'sub_title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button text (go back)', 'accounting'),
            'param_name' => 'content',
            'admin_label' => false
        )
    )
));
/* VC END Error 404 */
/* VC Tables */
vc_map( array(
    'name' => esc_html__('Table', 'accounting'),
    'base' => 'table',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_table.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'table',
            'heading' => esc_html__('Content', 'accounting'),
            'description' => 'Table content',
            'param_name' => 'content',
            'admin_label' => false
        ),
     )
));
/* END VC Tables */
/* VC Coming soon */
vc_map( array(
    'name' => esc_html__('Coming soon', 'accounting'),
    'base' => 'coming_soon',
    'icon' => get_template_directory_uri()."/images/visual-composer/anpsicon_commingsoon.png",
    'category' => 'Accounting',
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'accounting'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Subtitle', 'accounting'),
            'param_name' => 'subtitle',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Date', 'accounting'),
            'param_name' => 'date',
            'admin_label' => false
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image', 'accounting'),
            'param_name' => 'image_u',
            'admin_label' => false
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Image url', 'accounting'),
            'param_name' => 'image',
            'description' => esc_html__('Enter image url.', 'accounting'),
            'admin_label' => false
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__('Newslleter shortcode', 'accounting'),
            'param_name' => 'content',
            'description' => esc_html__('Enter newsletter shortcode [newsletter /].', 'accounting'),
            'admin_label' => false
        )
    )
));
/* VC END Coming soon */
/* Add parameter vc_row */
vc_add_param('vc_row', array("type" => "textfield", 'heading'=>esc_html__("Id", 'accounting'), 'param_name' =>'id'));
if(get_option("anps_vc_legacy", "0"=="on")) {
    vc_add_param('vc_row', array("type" => "dropdown", 'heading'=>esc_html__("Content wrapper", 'accounting'), 'param_name' =>'has_content', "value" =>array(esc_html__("On", 'accounting')=>'true', esc_html__("Off", 'accounting')=>'false', esc_html__("Inside content wrapper", 'accounting')=>'inside')));
    /* Add parameter vc_row_inner */
    vc_add_param('vc_row_inner', array("type" => "dropdown", 'heading'=>esc_html__("Content wrapper", 'accounting'), 'param_name' =>'has_content', "value" =>array(esc_html__("On", 'accounting')=>'true', esc_html__("Off", 'accounting')=>'false'), esc_html__("Inside content wrapper", 'accounting')=>'inside'));
}
/* Add parameter vc_tabs */
vc_add_param('vc_tta_tabs', array("type" => "dropdown", 'heading'=>__("Type", 'accounting'), 'param_name' =>'type', "value" =>array(esc_html__("Horizontal", 'accounting')=>'', esc_html__("Vertical", 'accounting')=>'vertical')));
vc_add_param('vc_tabs', array("type" => "dropdown", 'heading'=>__("Type", 'accounting'), 'param_name' =>'type', "value" =>array(esc_html__("Horizontal", 'accounting')=>'', esc_html__("Vertical", 'accounting')=>'vertical')));
/* Add anps style to backend vc_tta_tabs dropdown */
vc_add_param('vc_tta_tabs', array(
    "type" => "dropdown",
    "heading" => __( 'Style', 'js_composer' ),
    'param_name' =>'style',
    'value' => array(
                esc_html__( 'Anpsthemes', 'js_composer' ) => 'anps_tabs',
                esc_html__( 'Anpsthemes Style 2', 'js_composer' ) => 'anps-ts-2',
                esc_html__( 'Classic', 'js_composer' ) => 'classic',
                esc_html__( 'Modern', 'js_composer' ) => 'modern',
                esc_html__( 'Flat', 'js_composer' ) => 'flat',
                esc_html__( 'Outline', 'js_composer' ) => 'outline',
        ),
    'description' => __( 'Select tabs display style.', 'js_composer' )
    )
);
/* Add anps style to backend vc_tta_accordion dropdown */
vc_add_param('vc_tta_accordion', array(
    "type" => "dropdown",
    "heading" => __( 'Style', 'js_composer' ),
    'param_name' =>'style',
    'value' => array(
                esc_html__( 'Anpsthemes', 'js_composer' ) => 'anps_accordion',
                esc_html__( 'Anpsthemes Style 2', 'js_composer' ) => 'anps-as-2',
                esc_html__( 'Classic', 'js_composer' ) => 'classic',
                esc_html__( 'Modern', 'js_composer' ) => 'modern',
                esc_html__( 'Flat', 'js_composer' ) => 'flat',
                esc_html__( 'Outline', 'js_composer' ) => 'outline',
        ),
    'description' => esc_html__( 'Select accordion display style.', 'js_composer' )
    )
);
/* Add height to backend vc_round_chart dropdown */
vc_add_param('vc_round_chart', array(
    "name" => esc_html__("Height", 'accounting'),
    "type" => "textfield",
    "heading" => esc_html__( 'Height', 'js_composer' ),
    'param_name' =>'anps_height',
    'description' => esc_html__( 'Set the graph height in px.', 'js_composer' )
    )
);

/* Add height to backend vc_line_chart dropdown */
vc_add_param('vc_line_chart', array(
    "name" => esc_html__("Height", 'accounting'),
    "type" => "textfield",
    "heading" => esc_html__( 'Height', 'js_composer' ),
    'param_name' =>'anps_height',
    'description' => esc_html__( 'Set the graph height in px.', 'js_composer' )
    )
);
