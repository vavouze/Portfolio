<?php
function anps_get_style($styles) {
    $style = '';

    foreach($styles as $property => $value) {
        if($value != '') {
            $style .= "{$property}: {$value};";
        }
    }

    if($style != '') {
        $style = ' style="' . $style . '"';
    }

    return $style;
}

function anps_cta_func($atts, $content) {
    extract( shortcode_atts( array(
        'title' => '',
        'subtitle' => '',
        'button_text' => '',
        'button_link' => '',
        'button_target' => '',
        'icon_type' => '',
        'icon_fontawesome' => '',
        'icon_openiconic' => '',
        'icon_typicons' => '',
        'icon_entypo' => '',
        'icon_linecons' => '',
        'icon_monosocial' => '',
        'bg_color' => '',
        'title_color' => '',
        'subtitle_color' => '',
        'button_color' => '',
        'button_bg_color' => ''
    ), $atts ) );

    $data = '';
    $icon = '';

    $icon_type_name = 'icon_' . $icon_type;

    if( $icon_type && $$icon_type_name ) {
        $icon = $$icon_type_name;
    }

    $data .= '<div class="cta"' . anps_get_style(array('background-color' => $bg_color)) . '>';
        $data .= '<div class="container">';

        $data .= '<div class="cta__wrap">';
            $data .= '<div class="cta__left">';
            if ($title != '') {
                $data .= '<div class="cta__title"' . anps_get_style(array('color' => $title_color)) . '>' . $title . '</div>';
            }

            if ($subtitle != '') {
                $data .= '<div class="cta__subtitle"' . anps_get_style(array('color' => $subtitle_color)) . '>' . $subtitle . '</div>';
            }
            $data .= '</div>';

            $data .= '<div class="cta__right">';
            if ($button_text != '') {
                $data .= do_shortcode('[button link="' . $button_link . '" size="medium" style_button="style-1" icon_type="' . $icon_type . '" color="' . $button_color . '" background="' . $button_bg_color . '" icon_' . $icon_type . '="' . $icon . '"]' . $button_text . '[/button]');
            }
            $data .= '</div>';
        $data .= '</div>';

        $data .= '</div>';
    $data .= '</div>';

    return $data;
}
/* Appointment (added ps) */
function anps_appointment_func($atts, $content) {
    extract( shortcode_atts( array(
        'image_u' => '',
        'location' => '',
        'title' => '',
        'text' => '',
        'contact_form' => ''
    ), $atts ) );
    $data = '';
    $data .= '<div class="appointment">';
        /* Left side */
        $data .= '<div class="appointment-content">';
            $data .= '<div class="appointment-media">';
            if($image_u) {
                $data .= wp_get_attachment_image($image_u, 'full');
            } elseif($location!='') {
                $data .= do_shortcode("[google_maps zoom='14' map_type='ROADMAP' height='300' scroll='true'][google_maps_item marker_center='' pin='']".$location."[/google_maps_item][/google_maps]");
            }
            $data .= '</div>';
            $data .= '<div class="text-center"><h3 class="heading appointment-title"><span>' . $title . '</span></h3></div>';
            $data .= '<p class="appointment-text">' . $text . '</p>';
        $data .= '</div>';

        /* Right side */
        $data .= '<div class="appointment-form">';
            $data .= do_shortcode("[contact-form-7 id='$contact_form']");
        $data .= '</div>';
    $data .= '</div>';
    return $data;
}
/* END Appointment */
/* Blog shortcode */
function anps_blog_func($atts, $content) {
    extract( shortcode_atts( array(
        'category' => '',
        'orderby' => '',
        'order' => '',
        'type' => '',
        'columns' => ''
    ), $atts ) );
    global $wp_rewrite;

    wp_enqueue_script('anps-isotope');

    if(get_query_var('paged')>1) {
        $current = get_query_var('paged');
    } elseif(get_query_var('page')>1) {
        $current = get_query_var('page');
    } else {
        $current = 1;
    }
    $args = array(
        'posts_per_page'   => $content,
        'category_name'    => $category,
        'orderby'          => $orderby,
        'order'            => $order,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'paged'            => $current
    );

    $posts = new WP_Query( $args );

    $pagination = array(
        'base' => @esc_url(add_query_arg('page','%#%')),
        'format' => '',
        'total' => $posts->max_num_pages,
        'current' => $current,
        'show_all' => false,
        'prev_text'    => '',
        'next_text'    => '',
        'type' => 'list',
    );

    switch($type) {
        case '': $blog_type = "content"; break;
        case "grid": $blog_type = "content-blog-grid"; break;
        case "masonry": $blog_type = "content-blog-masonry"; break;
    }
    global $blog_columns;
    switch($columns) {
        case "3": $blog_columns = " col-md-4"; break;
        case "4": $blog_columns = " col-md-3"; break;
        default : $blog_columns = " col-md-4"; break;
    }
    $post_text = '';
    if($posts->have_posts()) :
        if($type=="masonry") {
            $post_text .= "<div class='blog-masonry'>";
        } else  if ($type == "grid"){
            $post_text .= "<div class='row'>";
        } else {
             $post_text .= "<div>";
        }

        global $counter_blog;
        $counter_blog = 1;
        while($posts->have_posts()) :
            $posts->the_post();
            ob_start();
            get_template_part( $blog_type, get_post_format() );
            $counter_blog++;
            $post_text .= ob_get_clean();
        endwhile;
        if( $wp_rewrite->using_permalinks() ) {
            $pagination['base'] = user_trailingslashit( trailingslashit( esc_url(remove_query_arg('s',get_pagenum_link(1)) ) ) . 'page/%#%/', 'paged');
        }
        if( !empty($wp_query->query_vars['s']) ) {
            $pagination['add_args'] = array('s'=>get_query_var('s'));
        }
        $post_text .= "</div>";
        $post_text .= paginate_links( $pagination );
        wp_reset_postdata();
    else :
        $post_text .= "<h2>".__('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'accounting')."</h2>";
    endif;
    return $post_text;
}
/* Recent portfolio slider shortcode */
function anps_recent_portfolio_slider_func($atts, $content) {
    extract( shortcode_atts( array(
        'recent_title' => '',
        'title_color' => "#c1c1c1",
        'nex_prev_color' => "#c1c1c1",
        'nex_prev_bg_color' => "#3d3d3d",
        'number' => '',
        'number_in_row' => "4",
        'category'=> '',
        'orderby' => 'post_date',
        'order' => 'DESC'
    ), $atts ) );
    $tax_query='';
    if($category && $category!='0') {
        $tax_query = array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $category
            )
       );
    }

    $args = array(
        'post_type' => 'portfolio',
        'orderby' => $orderby,
        'order' => $order,
        'showposts' => $number,
        'tax_query' => $tax_query
    );
    $portfolio_posts = new WP_Query( $args );

    $portfolio_data = '';
    $portfolio_data .= "<div class='container'>";
    $portfolio_data .= "<div class='row owly'><div class='col-md-6 col-xs-6'>";
    if($recent_title) {
        $portfolio_data .= "<h2 style='color:$title_color;'>".$recent_title."</h2>";
    }
    $portfolio_data .= "</div><div class='col-xs-6 col-md-6 align-right'><a class='owlprev' style='background:".$nex_prev_bg_color.";'><i class='fa fa-chevron-left' style='color:".$nex_prev_color.";'></i></a><a class='owlnext' style='background:".$nex_prev_bg_color.";'><i class='fa fa-chevron-right' style='color:".$nex_prev_color.";'></i></a></div>";
    $portfolio_data .= "</div></div>";

    $portfolio_data .= "<ul class='owl-carousel recentportfolio clearfix' data-col=".$number_in_row.">";
    while($portfolio_posts->have_posts()) :
        $portfolio_posts->the_post();
        $portfolio_cat = '';
        if (get_the_terms(get_the_ID(), 'portfolio_category')) {
            $first_item = false;
            foreach (get_the_terms(get_the_ID(), 'portfolio_category') as $cat) {
                if($first_item) {
                    $portfolio_cat .= " ";
                }
                $first_item = true;
                $portfolio_cat .= strtolower(str_replace(" ", "-", $cat->name));
            }
        }
        if(has_post_thumbnail(get_the_ID())) {
            $image = get_the_post_thumbnail(get_the_ID(), 'post-thumb');
        }
        elseif(get_post_meta(get_the_ID(), $key ='gallery_images', $single = true )) {
            $exploded_images = explode(',',get_post_meta(get_the_ID(), $key ='gallery_images', $single = true ));
            $image_url = wp_get_attachment_image_src($exploded_images[0], array(360, 267));
            $image = "<img src='".$image_url[0]."' />";
        }
        $portfolio_data .= "<li class='item item-type-line'>";
        $portfolio_data .= "<div class='mask'></div>";
        $portfolio_data .= "<div class='item-info'>";
        $portfolio_data .= "<div class='headline'>";
        //$portfolio_data .= "<a class='item-hover' href=".get_permalink().">";
        $portfolio_data .= "<h3><a class='item-hover' href=".get_permalink().">".get_the_title()."</a></h3>";
        $portfolio_data .= "<a class='btn btn-sm' href=".get_permalink().">".__("Read more", 'accounting')."</a>";
        //$portfolio_data .= "</a>";
        $portfolio_data .= "</div>";
        $portfolio_data .= "</div>";
        $portfolio_data .=  "<div class='item-img'>".$image."</div>";
        $portfolio_data .= "</li>";
    endwhile;
    wp_reset_postdata();
    $portfolio_data .= "</ul>";
    return $portfolio_data;
}
/* Recent portfolio shortcode */
function anps_recent_portfolio_func($atts, $content) {
    extract( shortcode_atts( array(
        'number' => '5',
        'category'=> '',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'mobile_class' => '2'
    ), $atts ) );
    $tax_query='';
    if($category && $category!='0') {
        $tax_query = array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $category
            )
       );
    }

    $args = array(
        'post_type' => 'portfolio',
        'orderby' => $orderby,
        'order' => $order,
        'showposts' => $number,
        'tax_query' => $tax_query
    );
    $portfolio_posts = new WP_Query( $args );

    if($mobile_class=="2") {
        $m_class = " col-xs-6";
    } else {
        $m_class = " col-xs-12";
    }
    $portfolio_data = '';
    $portfolio_data .= "<ul class='recentportfolio clearfix'>";
    while($portfolio_posts->have_posts()) :
        $portfolio_posts->the_post();
        $portfolio_cat = '';
        if (get_the_terms(get_the_ID(), 'portfolio_category')) {
            $first_item = false;
            foreach (get_the_terms(get_the_ID(), 'portfolio_category') as $cat) {
                if($first_item) {
                    $portfolio_cat .= " ";
                }
                $first_item = true;
                $portfolio_cat .= strtolower(str_replace(" ", "-", $cat->name));
            }
        }
        if(has_post_thumbnail(get_the_ID())) {
            $image = get_the_post_thumbnail(get_the_ID(), 'post-thumb');
        }
        elseif(get_post_meta(get_the_ID(), $key ='gallery_images', $single = true )) {
            $exploded_images = explode(',',get_post_meta(get_the_ID(), $key ='gallery_images', $single = true ));
            $image_url = wp_get_attachment_image_src($exploded_images[0], array(360, 267));
            $image = "<img src='".$image_url[0]."' />";
        }
        $portfolio_data .= "<li class='item item-type-line$m_class'>";
       // $portfolio_data .= "<a class='item-hover' href=".get_permalink().">";
        $portfolio_data .= "<div class='mask'></div>";
        $portfolio_data .= "<div class='item-info'>";
        $portfolio_data .= "<div class='headline'>";
        //$portfolio_data .= "<a class='item-hover' href=".get_permalink().">";
        $portfolio_data .= "<h3><a class='item-hover' href=".get_permalink().">".get_the_title()."</a></h3>";
        $portfolio_data .= "<a class='btn btn-sm' href=".get_permalink().">".__("Read more", 'accounting')."</a>";
        //$portfolio_data .= "</a>";
        $portfolio_data .= "</div>";
        //$portfolio_data .= "<div class='headline'><h2>".get_the_title()."</h2></div>";
        //$portfolio_data .= "</div></a>";
        $portfolio_data .= "</div>";
        $portfolio_data .=  "<div class='item-img'>".$image."</div>";
        $portfolio_data .= "</li>";
    endwhile;
    wp_reset_postdata();
    $portfolio_data .= "</ul>";
    return $portfolio_data;
}
/* Portfolio shortcode */
function anps_portfolio_func($atts, $content) {
    extract( shortcode_atts( array(
            'filter' => 'on',
            'columns' => '4',
            'category'=> '',
            'orderby' => '',
            'order' => '',
            'type' => 'classic',
            'style' => 'style-1',
            'per_page' => -1,
            'mobile_class' => '2',
            'filter_color' => '#000000'
	), $atts ) );

   wp_enqueue_script('anps-isotope');

    $type_class = '';
    if($type=="classic") {
        $type_class = " classic";
    } elseif($type=="random") {
        $type_class = " random";
    } else {
        $type_class = " default";
    }
    $tax_query='';
    $parent_cat = '';
    if($category && $category!='0') {
        $parent_cat = $category;
        $tax_query = array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $category
            )
       );
    }

    $args = array(
        'post_type' => 'portfolio',
        'orderby' => $orderby,
        'order' => $order,
        'showposts' => $per_page,
        'tax_query' => $tax_query
    );
    $portfolio_posts = new WP_Query( $args );

    /*desktop-class*/
    if($type!="random") {
        $mdclass = " col-md-3";
        if ($columns=="3") {
            $mdclass = " col-md-4";
        } elseif ($columns=="6") {
            $mdclass = " col-md-2";
        } elseif($columns=="2") {
            $mdclass = " col-md-6";
        } elseif($columns=="4") {
            $mdclass = " col-md-3";
        }
    } else {
        $mdclass = '';
    }



    /* Mobile class */
    if($mobile_class=="2") {
        $m_class = " col-xs-6";
    } else {
        $m_class = " col-xs-12";
    }

    /* Portfolio isotope filter */

    $portfolio_data = '';
    if($filter=="on") {
        $portfolio_data .= "<ul class='filter ".$style."'>";
        $portfolio_data .= '<i style="color: '.$filter_color.';" class="fa fa-filter"></i>';
        $portfolio_data .= '<li><button style="color: '.$filter_color.';" class="selected" data-filter="*">'.__("All", 'accounting')."</button></li>";
        $filters = get_terms('portfolio_category', "orderby=none&hide_empty=true&parent=$parent_cat");
        foreach ($filters as $item) {
            $portfolio_data .= '<span style="color: '.$filter_color.';">/</span>';
            $portfolio_data .= '<li><button style="color: '.$filter_color.';" data-filter="' . strtolower(str_replace(" ", "-", $item->name)) . '">' . $item->name . '</button></li>';
        }
        $portfolio_data .= "</ul>";
    }
    if($type=="random") {
        $i=1;
    }

    /* Portfolio isotope filter enabled posts */
    if($type!="random")
    {
    $portfolio_data .= "<ul class='portfolio isotope".$type_class."'>";
    }
    else
    {
    $portfolio_data .= "<ul class='isotope".$type_class."'>";
    }

    while($portfolio_posts->have_posts()) :
        $portfolio_posts->the_post();
        $portfolio_cat = '';
        if (get_the_terms(get_the_ID(), 'portfolio_category')) {
            $first_item = false;
            foreach (get_the_terms(get_the_ID(), 'portfolio_category') as $cat) {
                if($first_item) {
                    $portfolio_cat .= " ";
                }
                $first_item = true;
                $portfolio_cat .= strtolower(str_replace(" ", "-", $cat->name));
            }
        }
        $portfolio_subtitle = get_post_meta( get_the_ID(), $key = 'anps_subtitle', $single = true );

        $rand_class='';
        $image_class = "post-thumb";
        if($type=="random") {
            switch($i) {
                case 1 :
                    $rand_class = " width-2";
                    $image_class = "portfolio-random-width-2-height-2";
                    break;
                case 4 :
                    $rand_class = " height-2";
                    $image_class = "portfolio-random-width-2-height-1";
                    break;
                case 5 :
                    $rand_class = " width-2 height-2";
                    $image_class = "portfolio-random-width-4-height-4";
                    break;
                case 10 :
                    $rand_class = " width-2";
                    $image_class = "portfolio-random-width-2-height-2";
                    break;
            }
        }
        if(has_post_thumbnail(get_the_ID())) {
                $image = get_the_post_thumbnail(get_the_ID(), $image_class);
        }
        elseif(get_post_meta(get_the_ID(), $key ='gallery_images', $single = true )) {
            $exploded_images = explode(',',get_post_meta(get_the_ID(), $key ='gallery_images', $single = true ));
            $image_url = wp_get_attachment_image_src($exploded_images[0], $image_class);
            $image = "<img src='".$image_url[0]."' />";
        }



        if($type!="random") {
            $portfolio_data .= "<li class='isotope-item ".$portfolio_cat.$rand_class.$m_class.$mdclass."'><article class='inner'>";
            $portfolio_data .= "<div class='item-hover '>";
            $portfolio_data .= "<div class='mask'></div>";
            $portfolio_data .= "<div class='item-info item item-type-line'>";

            if($type=="default") {
                $portfolio_data .= "<div class='headline'>";
                $portfolio_data .= "<a href=".get_permalink().">";
                $portfolio_data .= "<h3><i class='fa fa-link'></i></h3>";
                $portfolio_data .= "</a>";
                $portfolio_data .= "</div>";
            }
            else {
                $portfolio_data .= "<div class='headline'>";
                $portfolio_data .= "<h3><a class='item-hover' href=".get_permalink().">".get_the_title()."</a></h3>";
                $portfolio_data .= "<a class='btn btn-sm' href=".get_permalink().">".__("Read more", 'accounting')."</a>";
                $portfolio_data .= "</div>";
            }

            //$portfolio_data .= "<div class='line'></div>";
            //$portfolio_data .= "<div class='fa fa-search'></div>";
            $portfolio_data .= "</div></div>";
            $portfolio_data .=  "<div class='item-img'>".$image."</div>";
            $portfolio_data .= "</article>";

            if($type=="default") {
                $portfolio_data .= "<a class='portfolio-title' href=".get_permalink()."><h3 class='text-center'>".get_the_title()."</h3></a>";
                $portfolio_data .= "<div class='subtitle text-center'>".$portfolio_subtitle."</div>";
            }

            $portfolio_data .= "</li>";
        }
        else {
            $portfolio_data .= "<li class='isotope-item ".$portfolio_cat.$rand_class.$mdclass."'>";
            $portfolio_data .= "<article class='inner'>";
            //$portfolio_data .= "<a class='item-hover' href=".get_permalink().">";
            $portfolio_data .= "<div class='mask'></div>";
            $portfolio_data .= "<div class='item-info'>";
            //$portfolio_data .= "<div class='headline'><h2>".get_the_title()."</h2></div>";
            $portfolio_data .= "<div class='headline'>";
            $portfolio_data .= "<h3><a class='item-hover' href=".get_permalink().">".get_the_title()."</a></h3>";
            $portfolio_data .= "<a class='btn btn-sm' href=".get_permalink().">".__("Read more", 'accounting')."</a>";
            $portfolio_data .= "</div>";

            //$portfolio_data .= "<div class='line'></div>";
            //$portfolio_data .= "<div class='fa fa-search'></div>";
            $portfolio_data .= "</div>";
            //$portfolio_data .="</a>";
            $portfolio_data .=  "<div class='item-img'>".$image."</div>";






        //$portfolio_data .= $image;
        // $portfolio_data .= "<div>";
        // $portfolio_data .= "<h2>".get_the_title()."</h2>";
        // $portfolio_data .= "<p>".get_post_meta( get_the_ID(), $key = 'anps_portfolio_shorttext', $single = true )."</p>";
        // $portfolio_data .= "<a href='".get_permalink()."' class='fa fa-link'></a>";
        // $portfolio_data .= "</div>";
        // $portfolio_data .= "</header>";
        // $portfolio_data .= "<h2>".get_post_meta( get_the_ID(), $key = 'anps_subtitle', $single = true )."</h2>";
        $portfolio_data .= "</article>";
        $portfolio_data .= "</li>";
        }


        if($type=="random") {
            $i++;
        }
    endwhile;
    wp_reset_postdata();
    $portfolio_data .= "</ul>";
    return $portfolio_data;
}
/* Image */
function anps_image_func($atts, $content) {
    extract( shortcode_atts( array(
        'alt' => '',
        'url' => '',
        'target' => '_blank'
    ), $atts ) );

    $url = str_replace("&quot;", '', $url);
    $alt = str_replace("&quot;", '', $alt);
    $target = str_replace("&quot;", '', $target);
    $img_data = '';
    if($url) {
        $img_data .= "<a href='".$url."' target='".$target."'>";
    }
    $img_data .= "<img alt='" . $alt . "' src='".$content."' />";
    if($url) {
        $img_data .= "</a>";
    }
    return $img_data;
}
/* END Image */
/* Team shortcode */
if(!function_exists('anps_team_func')) {
    function anps_team_func($atts, $content) {
        extract( shortcode_atts( array(
            'columns' => '4',
            'category'=> '',
            'ids' => '',
            'number_items' => '-1',
            'large_images' => false,
            'member_pages' => false
        ), $atts ) );

        $tax_query='';
        if($category && $category!='All' && $category!='0') {
            $tax_query = array(
                array(
                    'taxonomy' => 'team_category',
                    'field' => 'id',
                    'terms' => (int)$category
                )
           );
        }
        /* Select team by member id */
        $array_ids = '';
        $order_by = "date";
        if($ids) {
            $array_ids = explode(",", $ids);
            $array_ids = array_map("trim", $array_ids);
            $order_by = "post__in";
        }

        $args = array(
            'post_type' => 'team',
            'showposts' => $number_items,
            'columns' => $columns,
            'post__in' => $array_ids,
            'tax_query' => $tax_query,
            'orderby' => $order_by
        );
        global $text_only;
        $class = "6";
        $class_lg = "3";
        $image_class = "team-3";

        $team_class = 'team';

        if ($columns=="3") {
            $class = "4";
            $class_lg = "4";
            $image_class = "team-3";
        } elseif ($columns=="6") {
            $class = "4";
            $class_lg = "2";
            $image_class = "team-3";
        } elseif($columns=="2") {
            $class = "6";
            $class_lg = "6";
            $image_class = "full";
        }

        if( $large_images ) {
            $image_class = "full";
        }

        $team_posts = new WP_Query( $args );
        $team_data = "<div class='row'>";
        $text_only = true;
        while($team_posts->have_posts()) :
            $team_posts->the_post();

            if( get_the_content() != '' ) {
                $team_class .= ' team-hover';
            }

            $subtitle = get_post_meta( get_the_ID(), $key = 'anps_team_subtitle', $single = true );
            $team_data .= "<div class='col-lg-".$class_lg." col-sm-".$class."'><div class='" . $team_class . "'>";
            $team_data .= "<header>";
            $team_data .= get_the_post_thumbnail(get_the_ID(), $image_class);
            if( get_the_content() != '' ) {
                $text = get_the_content();
                if(has_excerpt()) {
                    $text = get_the_excerpt();
                }
                $team_data .= "<div class='hover'>".do_shortcode($text)."</div>";
            }
            $team_data .= "</header>";
            if($member_pages) {
                $team_data .= '<a href="' . get_the_permalink() . '">';
            }
            $team_data .= "<h2>".get_the_title()."</h2>";
            if($member_pages) {
                $team_data .= '</a>';
            }
            $team_data .= "<em>".$subtitle."</em>";
            $team_data .= "</div></div>";
        endwhile;
        wp_reset_postdata();
        $text_only = false;
        $team_data .= "</div>";
        return $team_data;
    }
}
/* Recent blog posts */
function anps_recent_blog_func($atts, $content) {
    extract( shortcode_atts( array(
		'number' => '3',
        'col_number' => '3',
        'style' => '1',
        'title_color' => '',
        'text_color' => '',
        'links_color' => '',
        'bg_color' => '',
	), $atts ) );

    $column_class = '';
    $class = '';
    $title_style = '';
    $item_style = '';
    $divider_style = '';
    $link_style = '';

    /* Title color */

    if($title_color != '') {
        $title_style = ' style="color: '. $title_color . ';"';
    }

    /* Item style (bg color & text color) */

    if($bg_color != '') {
        $item_style .= 'background-color: '. $bg_color . ';';
    }

    if($text_color != '') {
        $item_style .= 'color: '. $text_color . ';';
    }

    if($item_style != '') {
        $item_style = ' style="' . $item_style . '"';
    }

    /* Divider color */

    if($links_color != '') {
        $divider_style = ' style="background-color: '. $links_color . ';"';
    }

    /* Link color */

    if($links_color != '') {
        $link_style = ' style="color: '. $links_color . ';"';
    }

    /* Columns */

    switch($col_number) {
        case "2": $column_class = " col-lg-6"; break;
        case "3": $column_class = " col-lg-4"; break;
        case "4": $column_class = " col-lg-3"; break;
        case "6": $column_class = " col-lg-2"; break;
    }

    $column_class .= ' col-md-6 col-sm-6 col-xs-12';

    $class .= ' recent-blog--style-' . $style;

    $args = array(
    	'posts_per_page' => $number,
    );

    $posts = new WP_Query( $args );

    ob_start();

    if($posts->have_posts()): ?>
        <div class="row">
        <?php while($posts->have_posts()): $posts->the_post(); ?>
            <div class="<?php echo esc_attr($column_class); ?>">
                <article class="recent-blog<?php echo esc_attr($class); ?>"<?php echo $item_style; ?>>
                    <?php if(has_post_thumbnail()): ?>
                        <header class="recent-blog__header">
                            <a class="recent-blog__hover" href="<?php the_permalink(); ?>">
                                <i class="fa fa-search"></i>
                                <span class="sr-only"><?php the_title(); ?></span>
                            </a>
                            <div class='recent-blog__image'>
                                <?php the_post_thumbnail(get_the_ID(), 'post-thumb'); ?>
                            </div>
                        </header>
                    <?php endif; ?>

                    <div class="recent-blog__content">
                        <a<?php echo $title_style; ?> class="recent-blog__title" href="<?php the_permalink(); ?>">
                            <h2><?php the_title();  ?></h2>
                        </a>

                        <?php if($style == '2'): ?>
                        <div<?php echo $divider_style; ?> class="recent-blog__divider"></div>
                        <?php endif; ?>

                        <?php if($style == '1' || $style == '5'): ?>
                        <ul class='recent-blog__meta'>
                            <?php if( get_option('anps_post_meta_date') != '1' ): ?>
                                <li class="recent-blog__meta-item recent-blog__meta-item--date">
                                    <?php echo get_the_date(); ?>
                                </li>
                            <?php endif; ?>

                            <?php if( get_option('anps_post_meta_author') != '1' ): ?>
                                <li class="recent-blog__meta-item recent-blog__meta-item--author">
                                    <?php _e('by', 'accounting'); ?>
                                    <?php the_author(); ?>
                                </li>
                            <?php endif; ?>

                            <?php if( get_option('anps_post_meta_comments') != '1' ): ?>
                                <li class="recent-blog__meta-item recent-blog__meta-item--comment">
                                    <?php comments_number(); ?>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class='recent-blog__excerpt'>
                            <?php the_excerpt(); ?>
                        </div>
                        <?php else: ?>
                        <div class="recent-blog__info">
                            <?php if($style == '4'): ?>
                            <i class="recent-blog__info-icon fa fa-calendar"></i>
                            <?php endif; ?>
                            <?php echo get_the_date(); ?>
                            <?php _e('in', 'accounting'); ?>
                            <span<?php echo $link_style; ?> class="recent-blog__info-cat">
                                <?php the_category(', '); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif;

    return ob_get_clean();
}
/* Progress */
function anps_progress_func($atts, $content) {
    extract( shortcode_atts( array(
		'procent' => "0",
        'striped' => '',
        'active' => '',
        'color_class' => 'progress-bar-success'
        ), $atts ) );

    if($striped) {
        if($active) {
            $active = " active";
        }
        $striped = " progress-striped".$active;
    }
    $progress_data = '';

    if( $content ) {
        $progress_data .= "<h4>" . $content . "</h4>";
    }

    $progress_data .= "<div class='progress".$striped."'>";
    $progress_data .= "<div class='progress-bar ".$color_class."' role='progressbar' aria-valuenow='".$procent."' aria-valuemin='0' aria-valuemax='100' style='width: ".$procent."%;'></div>";
    $progress_data .= "</div>";
    return $progress_data;
}
/* Counter */
function anps_counter_func($atts, $content) {
    extract( shortcode_atts( array(
		'icon' => '',
        'icon_type' => '',
        'icon_fontawesome' => '',
        'icon_openiconic' => '',
        'icon_typicons' => '',
        'icon_entypo' => '',
        'icon_linecons' => '',
        'icon_monosocial' => '',
        'max' => '',
        'min' => "0",
        "icon_color" => '',
        "number_color" => '',
        "subtitle_color" => '',
        "border_color" => ''
    ), $atts ) );

    wp_enqueue_script( 'countto' );
    $icon_style = '';
    $number_style = '';
    $subtitle_style = '';
    $border_style = '';
    $icon_class = 'fa fa-' . $icon;

    /* Check for VC icon types */
    if( $icon_type ) {
        vc_icon_element_fonts_enqueue( $icon_type );
        $icon_type = 'icon_' . $icon_type;
        if( $$icon_type ) { $icon_class = $$icon_type; }
    }

    /* Check styles */
    if($icon_color) {
        $icon_style = " style='color:".$icon_color."'";
    }
    if($number_color) {
        $number_style = " style='color:".$number_color."'";
    }
    if($subtitle_color) {
        $subtitle_style = " style='color:".$subtitle_color."'";
    }
    if($border_color) {
        $border_style = " style='border-color:".$border_color."'";
    }
    return "<div class='counter'>
            <div class='wrapbox'$border_style>
                <i class='".$icon_class."'$icon_style></i>
                <h2 class='counter-number' data-to='".$max."'$number_style>".$min."</h2>
                <h3$subtitle_style>".$content."</h3>
            </div>

            </div>";
}
/* Newsletter widget */
function newsletter_widget_func($atts) {
    global $wp_widget_factory;
    extract(shortcode_atts(array(
        'widget_name' => "NewsletterWidget"
    ), $atts));

    $widget_name = esc_html($widget_name);

    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));

        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct", 'accounting'),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;

    ob_start();
    the_widget($widget_name, $instance=array(), array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
/* Coming soon */
function anps_coming_soon_func($atts, $content) {
    extract( shortcode_atts( array(
        'image_u' => '',
        'image' => '',
        'title' => '',
        'subtitle'=>'',
        'date' => ''
    ), $atts ) );

    $img_bg = '';

    if($image_u) {
        $image = wp_get_attachment_image_src($image_u, 'full');
        $image = $image[0];
    }
    if($image) {
        $img_bg = " style='background-image: url(".$image.");'";
    }
    return '<div class="coming-soon"'.$img_bg.'>
		<h1>'.$title.'</h1>
		<h2 class="primary">'.$subtitle.'</h2>
		<ul class="countdown primary"></ul>'.
                do_shortcode($content)
	.'</div>
	<script src="'.get_template_directory_uri()  . "/js/countdown.js".'"></script>
        <script>
		jQuery(".countdown").countdown("'.$date.'", function(event) {
		     jQuery(this).html(event.strftime("<li>%D<label>days</label></li><li>%H<label>hours</label></li><li>%M <label>minutes</label></li><li>%S<label>seconds</label></li>"));
		});
	</script>';
}
/* Twitter */
global $anps_parallax_slug;
$anps_parallax_slug = array();
function anps_twitter_func($atts, $content) {
    extract( shortcode_atts( array(
		'title' => 'Stay informed',
                'parallax' => 'false',
                'parallax_overlay' => '',
                'image' => '',
                'color' => '',
                'slug' => '',
                'image_u' => ''
	), $atts ) );
    if($image_u) {
        $image = wp_get_attachment_image_src($image_u, 'full');
        $image = $image[0];
    }
    global $anps_parallax_slug;
    $parallax_class = '';
    $parallax_id = '';
    if($parallax=="true") {
        $parallax_class = " parallax";
        $parallax_id = " $slug";
        $anps_parallax_slug[] = $slug;
    }

    $parallax_overlay_class = '';
    if($parallax_overlay=="true") {
        $parallax_overlay_class = " parallax-overlay";
    }

    $style = '';
    if($image) {
        $style = "background-image: url('$image');";
    } elseif($color) {
        $style = "background-color: $color;";
    }

    include_once WP_PLUGIN_DIR . '/anps_theme_plugin/twitter/TwitterAPIExchange.php';
    $settings = array(
        'oauth_access_token' => "1485322933-oo8YU1ZTz5E4Zt92hTTbCdJoZxIJIabghjnsPkX",
        'oauth_access_token_secret' => "RfXHN2OXMkBYp3IaEqrBmPhUYR2N61P8pyHf8QXqM",
        'consumer_key' => "Zr397FLlTFM4RVBsoLVgA",
        'consumer_secret' => "3Z2wNAG2vvunam2mfJATxnJcThnqw1qu02Xy8QlqFI"
    );
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name=' . $content . '&count=3';
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);
    $tweets = json_decode($twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest());
    $return = '<section id="carousel'.$parallax_id.'" class="carousel slide twitter'.$parallax_class.$parallax_overlay_class.'" style="'.$style.'" data-ride="carousel">';
    $return .= '<h1>'.$title.'</h1>';
    $return .= '<div class="carousel-inner">';
    $j=0;
    foreach( $tweets as $tweet ) {
        if($j=="0") {
            $class_active = ' active';
        } else {
            $class_active = '';
        }
        $tweet_text = $tweet->text;
        $tweet_text = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">http://$1</a>', $tweet_text); //replace links
        $tweet_text = preg_replace('/@([a-z0-9_]+)/i', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $tweet_text); //replace users
        $return .= '<div class="item'.$class_active.'">' . $tweet_text . '</div>';
        $j++;
    }
    $return .= "</div>";
    $return .= "<ol class='carousel-indicators'>";
    for($i=0;$i<count($tweets);$i++) {
        if($i==0) {
            $active_class = "active";
        } else {
            $active_class = '';
        }
        $return .= "<li data-target='#carousel' data-slide-to='".$i."' class='".$active_class."'></li>";
    }
    $return .= "</ol>";
    $return .= "<a class='btn btn-md' href='http://twitter.com/".$content."'><i class='fa fa-twitter'></i>".__("Follow us", 'accounting')."</a>";
    $return .= '</section>';
    return $return;
}
/* END twitter */
/*************************************
****** Column layout shortcodes ******
**************************************/
function content_half_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );

    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-md-6 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-md-6 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-md-6 ' . $class . '">' . $content . '</div>';
    }
}
function content_third_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );
    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-sm-4 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-sm-4 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-sm-4 ' . $class . '">' . $content . '</div>';
    }
}
function content_two_third_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );
    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-sm-8 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-sm-8 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-sm-8 ' . $class . '">' . $content . '</div>';
    }
}
function content_quarter_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );
    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-md-3 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-md-3 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-md-3 ' . $class . '">' . $content . '</div>';
    }
}
function content_two_quarter_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );
    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-md-6 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-md-6 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-md-6 ' . $class . '">' . $content . '</div>';
    }
}
function content_three_quarter_func( $atts,  $content ) {
	extract( shortcode_atts( array(
		'id' => '',
        'class' => ''
	), $atts ) );
    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );
    if ( $id == "first" ) {
        return '<div class="row">
                <div class="col-md-9 ' . $class . '">' . $content . '</div>';
    }
    elseif ( $id == "last" ) {
        return '<div class="col-md-9 ' . $class . '">' . $content . '</div>
                </div>';
    }
    else {
        return '<div class="col-md-9 ' . $class . '">' . $content . '</div>';
    }
}
/*************************************
**** END Column layout shortcodes ****
**************************************/
/* Icon shortcode */
function icon_func( $atts,  $content ) {
	extract( shortcode_atts( array(
        'url' => '',
        'target' => '_self',
        'icon' => '',
        'icon_type' => '',
        'icon_fontawesome' => '',
        'icon_openiconic' => '',
        'icon_typicons' => '',
        'icon_entypo' => '',
        'icon_linecons' => '',
        'icon_monosocial' => '',
        'icon_anps_icons' => '',
        'title' => '',
        'subtitle' => '',
        'position' => '',
        'position_2' => '',
        'class' => '',
        'icon_size' => '',
        'title_color'=>'',
        'subtitle_color'=>'',
        'text_color'=>'',
        'icon_color'=>'',
        'icon_bg_color'=>''
    ), $atts ) );

    if ($class === '') {
        $class = 'style-1';
    }

    if ($icon_size === '') {
        $icon_size = '22';
    }

    if($class=="style-2") {
        switch($position) {
            case '': $position = "icon-left"; break;
            case "left": $position = "icon-left"; break;
            case "right": $position = "icon-right"; break;
        }
    } else if($class=="style-3") {
        switch($position_2) {
            case '': $position = "icon-left"; break;
            case "left": $position = "icon-left"; break;
            case "right": $position = "icon-right"; break;
            case "center": $position = "icon-center"; break;
        }
    } else {
        $position = '';
    }
    $title_c = '';
    $subtitle_c = '';
    $text_c = '';
    $icon_c = '';
    $el_pre = '<span>';
    $el_after = '</span>';
    $icon_type_class = 'fa fa-' . $icon;

    /* Check if VC icon is added */
    if( $icon_type  && $icon_type !== 'anps_icons' ) {
        vc_icon_element_fonts_enqueue( $icon_type );
        $icon_type = 'icon_' . $icon_type;
        if( $$icon_type ) { $icon_type_class = $$icon_type; }
    }

    /* Title Color */
    if($title_color) {
        $title_c = " style='color: ".$title_color.";'";
    }

    /* Sub Title Color */
    if($subtitle_color) {
        $subtitle_c = " style='color: ".$subtitle_color.";'";
    }

    /* Text Color */
    if($text_color) {
        $text_c = " style='color: ".$text_color.";'";
    }

    /* Icon Colors */
    if($icon_color&&$icon_bg_color=='') {
        $icon_c = " style='color: ".$icon_color."; font-size: {$icon_size}px;'";
    } elseif($icon_color==''&&$icon_bg_color&&$class!="style-2") {
        $icon_c = " style='background-color: ".$icon_bg_color."; font-size: {$icon_size}px;'";
    } elseif($icon_color&&$icon_bg_color) {
        if($class!="style-2") {
            $icon_c = " style='color: ".$icon_color.";background-color: ".$icon_bg_color."; font-size: {$icon_size}px;'";
        } else {
            $icon_c = " style='color: ".$icon_color."; font-size: {$icon_size}px;'";
        }
    }

    /* Check if URL */
    if($url) {
        $el_pre = ' <a href="'.$url.'" target="'.$target.'">';
        $el_after = '</a>';
    }

    $icon_html = '';

    if ($icon_anps_icons !== '') {
        $icon_media = wp_remote_get(get_template_directory_uri() . '/images/accounting-icons/' . str_replace('anps-icon-', '', $icon_anps_icons) . '.svg');
        $icon_media = str_replace('<svg', "<svg style='width: {$icon_size}px; height: {$icon_size}px;'", $icon_media);
        $icon_html = '<div class="icon-media icon-svg" '.$icon_c.'>' . $icon_media['body'] . '</div>';
    } else {
        $icon_html = '<div class="icon-media"><i class="'.$icon_type_class.'"'.$icon_c.'></i></div>';
    }

    $title_html = '';
    $subtitle_html = '';

    if ($title !== '') {
        $title_html = '<h2'.$title_c.'>'.$title.'</h2>';
    }

    if ($subtitle !== '') {
        $subtitle_html = '<h3 class="style-2"'.$subtitle_c.'>'.$subtitle.'</h3>';
    }

    /* Return shortcode */
    return '<div class="icon '.$class.' '.$position.'">
            '.$el_pre.'
                <div class="icon-header">
                '.$icon_html.'
                <div class="icon-titles">
                    '.$title_html.'
                    '.$subtitle_html.'
                </div>
                </div>
            '.$el_after.'
            <p'.$text_c.'>'.$content.'</p>
    </div>';

}
/* Quote */
function quote_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'style' => "style-1"
    ), $atts ) );
    return '<blockquote class="'.$style.'"><p>' . $content . '</p></blockquote>';
}
/* Color (mark) */
function color_func( $atts,  $content ) {
    extract( shortcode_atts( array(
            'style' => '',
            'custom' => ''
        ), $atts ) );
    $custom = ' style="color: ' . $custom . '"';
    if( $style && $style != '' ) {
        return '<span' . $custom . ' class="mark ' . $style . '">' . do_shortcode($content) . '</span>';
    } else {
        return '<span' . $custom . ' class="mark">' . do_shortcode($content) . '</span>';
    }
}
/* Vimeo */
function vimeo_func( $atts,  $content ) {
    return '<div class="video-wrapper"><iframe src="http://player.vimeo.com/video/' . $content . '" width="320" height="240" style="border: none !important"></iframe></div>';
}
/* Youtube */
function youtube_func( $atts,  $content ) {
    return '<div class="video-wrapper"><iframe src="http://www.youtube.com/embed/' . $content . '?wmode=transparent" width="560" height="315" style="border: none !important"></iframe></div>';
}
/* Button */
global $button_counter;
$button_counter = 0;
function button_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'link'       => '',
        'target'     => '_self',
        'size'       => 'small',
        'style_button'      => 'style-1',
        'padding' => '',
        'font_size' => '',
        'color'      => '',
        'background' => '',
        'color_hover' => '',
        'background_hover' => '',
        'icon'=>'',
        'icon_type' => '',
        'icon_fontawesome' => '',
        'icon_openiconic' => '',
        'icon_typicons' => '',
        'icon_entypo' => '',
        'icon_linecons' => '',
        'icon_monosocial' => ''
    ), $atts ) );
    global $button_counter;

    $style_attr = '';
    if(isset($color) && $color!="#ffffff") {
        $style_attr .= "color: " . $color . " !important;";
    }
    if (isset($padding) && $padding != '') {
        $style_attr .= "padding: " . $padding . ";";
    }
    if (isset($font_size) && $font_size != '') {
        $style_attr .= "font-size: " . $font_size . ";";
    }
    if($background) {
        $style_attr .= "background-color: " . $background . ";";
        if( $style_button == 'style-3' ) {
            $style_attr .= "border-color: " . $background . ";";
        }
    }
    if ( $target != '' && $link ) {
        $target = ' target="' . $target . '"';
    } else {
        $target = '';
    }

    switch($size) {
        case "large": $size = "btn-lg"; break;
        case "medium": $size = "btn-md"; break;
        case "small": $size = "btn-sm"; break;
    }

    $icon_type_class = 'icon_' . $icon_type;
    $icon_el = '';
    $icon_class = '';

    if($icon||($icon_type&&$$icon_type_class)) {
        $icon_class = 'fa fa-'.$icon;

        /* Check if VC icon is added */
        if( $icon_type ) {
            vc_icon_element_fonts_enqueue( $icon_type );
            if( $$icon_type_class ) { $icon_class = $$icon_type_class; }
        }

        $icon_el = "<i class='".$icon_class."'></i>";
    }

    $style_id = "custom-id-".$button_counter;
    $button_counter++;
    $style_css='';
    if( !$link ) {
        $style_css .= '<button' . $target . ' class="btn ' . $size . ' ' . $style_button . '" id="'.$style_id.'" style="'.$style_attr.'">' .$icon_el.$content . '</button>';
    } else {
        $style_css .= '<a' . $target . ' href="' . $link . '" class="btn ' . $size . ' ' . $style_button . '" id="'.$style_id.'" style="'.$style_attr.'">' . $icon_el.$content . '</a>';
    }
    return $style_css;
}
/* Error 404 */
function error_404_func( $atts,  $content ) {
	extract( shortcode_atts( array(
            'title' => '',
            'sub_title' => ''
    ), $atts ) );

	return '<div class="error-404">
                    <h1>'.$title.'</h1>
                    <h2>'.$sub_title.'</h2>
                    <a href="javascript:javascript:history.go(-1)" class="btn btn-wide">'.$content.'</a>
                </div>';
}
/* Alert */
function anps_alert_func($atts, $content) {
    extract( shortcode_atts( array(
        'type' => ''
    ), $atts ) );
    wp_enqueue_script('alert');
    switch($type) {
        case '':
            $type_class = '';
            $icon = "bell-o";
            break;
        case "warning":
            $type_class = " alert-warning";
            $icon = "exclamation";
            break;
        case "info":
            $type_class = " alert-info";
            $icon = "info";
            break;
        case "success":
            $type_class = " alert-success";
            $icon = "check";
            break;
        case "useful":
            $type_class = " alert-useful";
            $icon = "lightbulb-o";
            break;
        case "normal":
            $type_class = " alert-normal";
            $icon = "hand-o-right";
            break;
    }
    return '<div class="alert'.$type_class.'" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="fa fa-'.$icon.'"></i> '.$content.'
            </div>';
}
/* Dropcaps */
function anps_dropcaps_func($atts, $content) {
    extract( shortcode_atts( array(
                'style' => ''
        ), $atts ) );
    $style_class = '';
    if($style) {
        $style_class = " style-2";
    }
    return '<p class="dropcaps'.$style_class.'">'.$content.'</p>';
}
/* Load VC shortcodes support */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (function_exists('vc_add_shortcode_param')) {
    load_template(TEMPLATEPATH . '/anps-framework/vc_shortcodes_map.php');

    /* Set as theme (no licence message) */
    add_action( 'vc_before_init', 'anps_vcSetAsTheme' );
    function anps_vcSetAsTheme() {
        vc_set_as_theme(true);
    }
    /* END Set as theme (no licence message) */
} else {
function remove_wpautop($content, $autop = false) {
  if($autop) { // Possible to use !preg_match('('.WPBMap::getTagsRegexp().')', $content)
      $content = wpautop(preg_replace('/<\/?p\>/', "\n", $content)."\n");
  }
  return do_shortcode( shortcode_unautop($content) );
}
/* Google maps */
$google_maps_counter = 0;
function google_maps_func( $atts,  $content ) {
    global $google_maps_counter;
        $google_maps_counter++;
        extract( shortcode_atts( array(
                    'zoom'   => '15'
        ), $atts ) );
        preg_match_all( '#\](.*?)\[/google_maps_item]#', $content, $matches);
        $location = $matches[1][0];
        wp_enqueue_script('gmap3_link');
        wp_enqueue_script('gmap3');
        return "<script>
            jQuery(document).ready(function( $ ) {
                    $('#map$google_maps_counter').gmap3({
                        map:{
                            options:{
                                zoom: $zoom
                            }
                        },
                        marker:{
                          values:[
                            ".do_shortcode($content)."
                          ],
                          options:{
                            draggable: false
                          },
                          events: {
                            mouseover: function(marker, event, context){
                                var map = $(this).gmap3('get'),
                                infowindow = $(this).gmap3({get:{name:'infowindow'}});
                                if (infowindow){
                                  infowindow.open(map, marker);
                                  infowindow.setContent(context.data);
                                } else {
                                  $(this).gmap3({
                                    infowindow:{
                                      anchor:marker,
                                      options:{content: context.data}
                                    }
                                  });
                                }
                              },
                            mouseout: function(){
                                var infowindow = $(this).gmap3({get:{name:'infowindow'}});
                                if (infowindow){
                                  infowindow.close();
                                }
                              }
                          }
                        },
                        getlatlng:{
                            address: '".$location."',
                            callback: function(results){
                                if(!results) return;
                                $(this).gmap3({
                                    map:{
                                        options: {
                                            center: [results[0].geometry.location.lat(), results[0].geometry.location.lng()]
                                        }
                                    }
                                });
                            }
                        }
                    });
                });
            </script>
            <div class='map' id='map$google_maps_counter'></div>";
}
function anps_google_maps_item( $atts,  $content ) {
    extract( shortcode_atts( array(
            'info' => '',
            'pin' => ''
        ), $atts ) );
        $info = preg_replace('/[\n\r]+/', '', $info);
        if(isset($pin) && $pin!='') {
            $pin_icon = wp_get_attachment_image_src($pin, 'full');
            $pin_icon = $pin_icon[0];
        } else {
            $pin_icon = get_template_directory_uri()."/images/gmap/map-pin.png";
        }
    return "{address: '$content', data:'".$info."', options:{icon:'$pin_icon'}},";
}
/* Section */
function section_func($atts, $content) {
    return "<div class='container'>
                <div class='row'>
                    <div class='col-md-12'>".
                        do_shortcode($content)."
                    </div>
                </div>
            </div>";
}
/* VC single image */
function anps_vc_single_image($atts, $content) {
    extract( shortcode_atts( array(
        'image' => '',
        'border_color' => '',
        'img_link_target' => '',
        'img_size' => '',
        'el_class' => ''
    ), $atts ) );

    if($image) {
        $image_src = wp_get_attachment_image_src($image, 'full');
        $image_src = $image_src[0];
    }
    $data = '';
    $data .= "<img src='".$image_src."' />";
    return $data;
}
/* VC layer slider */
function anps_vc_layer_slider($atts, $content) {
    return do_shortcode("[layerslider id='".$atts['id']."']");
}
/* VC rev slider */
function anps_vc_rev_slider($atts, $content) {
    return do_shortcode("[rev_slider id='".$atts['alias']."']");
}
/* VC Row */
function vc_theme_rows($atts, $content) {
    $extra_class = '';
        $extra_id = '';
        $matches = array();

        global $no_container, $row_inner, $text_only;

        if ( $row_inner ) {
            return vc_theme_rows_inner($atts, $content);
        }

        if ( $text_only ) {
            return do_shortcode( shortcode_unautop($content));
        }

        /* Check for any user added styles */

        $css = '';
        $style = '';
        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }
        $temp = preg_match('/\.vc_custom_(.*?){(.*?)}/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];
            $temp_style = $matches[2];

            if( $temp ) {
                $extra_class .= ' vc_custom_' . $temp;

            }
            if($temp_style) {
                $style = 'style="' . $temp_style . '"';
            }
        }

        /* Check for any user added classes */

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class .= ' '. $atts['el_class'];
        }

        /* Check for any user added IDs */

        if(isset($atts['id']) && $atts['id']) {
            $extra_id = 'id= "'. $atts['id'].'"';
        }

        $coming_soon = get_option('coming_soon', '0');

        if($coming_soon=="0"||is_super_admin()) {
            if(!isset($atts['has_content']) || $atts['has_content']=="true") {
                /* Content inside a container */
                $no_container = false;
                return '<section class="container"><div '.$extra_id.' class="row' . $extra_class . '" '.$style.'>'.do_shortcode( shortcode_unautop($content)).'</div></section>';
            }
            elseif($atts['has_content']=="inside") {
                $no_container = false;
                return '<section '.$extra_id.' class="' . $extra_class . '" '.$style.'><div class="container no-margin"><div class="row">'.do_shortcode( shortcode_unautop($content)).'</div></div></section>';
            }
            else {
                /* Fullwidth Content */
                $no_container = true;
                return '<section '.$extra_id.' class="row no-margin' . $extra_class . '" '.$style.'>'.do_shortcode( shortcode_unautop($content)).'</section>';

            }
        } else {
            /* No wrappers, only when Cooming soon is active */
            $no_container = true;
            return do_shortcode( shortcode_unautop($content));
        }
}
function vc_theme_rows_inner($atts, $content) {

        /* Check for any user added styles */

        $style = '';

        $css = '';

        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }

        $temp = preg_match('/{(.*?)}/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];
            if( $temp ) {
                $style = 'style="' . $temp . '"';
            }
        }

        return '<div class="row"' . $style . '>'.do_shortcode( shortcode_unautop($content)).'</div>';
    }
function vc_theme_vc_row($atts, $content = null) {
    return vc_theme_rows($atts, $content);
}
function vc_theme_vc_row_inner($atts, $content = null) {
    return vc_theme_rows_inner($atts, $content);
}
/* VC Columns */
function vc_theme_columns($atts, $content = null) {
    if( !isset($atts['width']) ) {
            $width = '1/1';
        } else {
            $width = explode('/', $atts['width']);
        }

        global $no_container, $text_only;

        if($width[1] > 0) {
            $col = (12/$width[1])*$width[0];
        } else {
            $col = 12;
        }
        $extra_class = '';

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class = ' ' . $atts['el_class'];
        }

        if ( $no_container || $text_only ) {
            return do_shortcode( shortcode_unautop($content));
        } else {
            return '<div class="col-md-' . $col . $extra_class . '">'.do_shortcode( shortcode_unautop($content)).'</div>';
        }
}
/* VC Column Text */
function vc_column_text_func($atts, $content = null) {
    return do_shortcode(force_balance_tags($content));
}
/* VC Tabs */
function vc_tabs_func ($atts, $content = null) {
    if(!isset($atts['type'])) {
        $atts['type'] = '';
    } else {
        $atts['type'] = $atts['type'];
    }
    $content2 = str_replace("vc_tab", "tab", $content);
    return do_shortcode("[tabs type='".$atts['type']."']".$content2."[/tabs]");
}
/* Logos */
function anps_logos_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'in_row' => '3',
        'style' => 'style-1'
    ), $atts ) );
    $logos_array = explode("[/logo]", $content);
    foreach($logos_array as $key=>$item) {
        if($item=="") {
            unset($logos_array[$key]);
        }
    }
    $data_col = "";
    $logos_class = "";
    $count_logos = count($logos_array);
    if ($count_logos > $in_row && $style == 'style-2') {
       $data_col = "data-col=" . $in_row;
       $logos_class = 'owl-carousel';
    }

    $return = "<div class='logos-wrapper $style'>";
    $return .= "<ul class='logos ". $logos_class ."' ".$data_col.">";

    $i = 0;
    foreach($logos_array as $item) {
        if($i%$in_row==0 && $i!=0 && $style == 'style-1') {
                $return .= "</ul><ul class='logos'>".do_shortcode($item."[/logo]");
        } else {
            $return .= do_shortcode($item."[/logo]");
        }
        $i++;
    }
    $return .= "</ul></div>";
    return $return;
}
/* Single logo */
function anps_logo_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'url' => '',
        'alt' => '',
        'image_u' => '',
        'image_u_hover' => '',
        'img_hover' => '',
        'alt_hover' => ''
    ), $atts ) );
    if($image_u) {
        $content = wp_get_attachment_image_src($image_u, 'full');
        $content = $content[0];
    }

    if($image_u_hover) {
        $img_hover = wp_get_attachment_image_src($image_u_hover, 'full');
        $img_hover = $img_hover[0];
    }
    if($url) {
        return "<li><a href='".$url."' target='_blank'><img src='".$content."' alt='".$alt."'><span class='hover'><img src='".$img_hover."' alt='".$alt_hover."'></span></a></li>";
    } else {
        return "<li><span><img src='".$content."' alt='".$alt."'><span class='hover'><img src='".$img_hover."' alt='".$alt_hover."'></span></span></li>";
    }
}
/* List */
global $list_number;
$list_number = false;
function anps_list_func($atts, $content) {
    extract( shortcode_atts( array(
        'class' => ''
    ), $atts ) );

    global $list_number;

    if( $class == "number" ) {
        $list_number = true;
        $return = "<ol class='list'>".do_shortcode($content)."</ol>";
        $list_number = false;
        return $return;
    }
    return "<ul class='list ".$class."'>".do_shortcode($content)."</ul>";
}
/* List item */
function anps_list_item_func($atts, $content) {
    global $list_number;
    if($list_number) {
        return "<li><span>".$content."</span></li>";
    } else {
        return "<li>".$content."</li>";
    }
}
/* Social icons */
function anps_social_icons_func( $atts,  $content ) {
    return "<ul class='socialize'>".do_shortcode($content)."</ul>";
}
/* Single social icon */
function anps_social_icon_item_func( $atts,  $content ) {
    extract( shortcode_atts( array(
            'url' => '#',
            'icon' => '',
            'target' => '_blank'
        ), $atts ) );
        return "<li><a href='".$url."' target='".$target."' class='fa fa-".$icon."'></a></li>";
}
/* Statement */
function statement_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'parallax' => 'false',
        'parallax_overlay' => 'false',
        'image' => '',
        'color' => '',
        'container' => 'false',
        'slug' => '',
        'image_u' => ''
    ), $atts ) );
    if($image_u) {
        $image = wp_get_attachment_image_src($image_u, 'full');
        $image = $image[0];
    }
    global $anps_parallax_slug;
    $parallax_class = '';
    $parallax_id = '';
    if($parallax=="true") {
        $parallax_class = " parallax";
        $anps_parallax_slug[] = $slug;
        $parallax_id = "id='$slug'";
    }
    $parallax_overlay_class = '';
    if($parallax_overlay=="true") {
        $parallax_overlay_class = " parallax-overlay";
    }
    $containe_class = '';
    $container_before = '';
    $container_after = '';
    $container_class='';
    if($container=="true") {
        $container_before = '<div class="container text-center">';
        $container_after = '</div>';
    }
    $style = '';
    if($image) {
        $style = "background-image: url('$image');";
    } elseif($color) {
        $style = "background-color: $color;";
    }
    return '<section '.$parallax_id.' class="statement'.$parallax_class.$parallax_overlay_class.'" style="'.$style.'">'.$container_before.do_shortcode($content).$container_after.'</section>';
}
/* END statement */
/* Tabs shortcodes */
global $tabs_counter, $indiv_tab_counter;
$tabs_counter = 0;
$indiv_tab_counter = 0;
function tabs_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'type' => ''
    ), $atts ) );
    wp_enqueue_script('tab');
    global $tabs_counter, $indiv_tab_counter, $tabs_single;
    $tabs_counter++;
    $sub_tabs_counter = 1;
    $indiv_tab_counter = 0;
    $tabs_single = 0;
    /* Everything inside [tab] shortcode */
    preg_match_all( '#\[tab(.*?)\]#', $content, $matches);
    if ( isset($matches[1]) ) { $tab_titles = $matches[1]; }
    $class = '';
    $class_before = '';
    $class_after = '';
    $class_content = '';
    if($type == 'vertical') {
        $class = ' vertical';
        $class_before = "<div class='col-2-5'>";
        $class_after = "</div>";
        $class_content = " col-9-5";
    }
    $tabs_menu = '';
    $tabs_menu .= $class_before;
    $tabs_menu .= '<ul class="nav nav-tabs'.$class.'" id="tab-' . $tabs_counter . '">';
    $i=0;
    foreach ( $tab_titles as $tab ) {
        preg_match_all( '/title="(.*?)\"/', $tab, $title_match);
        preg_match_all( '/icon="(.*?)\"/', $tab, $icon_match);
        if(isset($icon_match[1][0])) {
            $icon[$i] = " <i class='fa fa-".$icon_match[1][0]."'></i>";
        } else {
            $icon[$i] = '';
        }
        if( $sub_tabs_counter == 1 ) {
            $tabs_menu .= '<li class="active"><a data-toggle="tab" href="#tab' . $tabs_counter . '-' . $sub_tabs_counter . '">' . $title_match[1][0].$icon[$i] . '</a></li>';
        } else {
            $tabs_menu .= '<li><a data-toggle="tab" href="#tab' . $tabs_counter . '-' . $sub_tabs_counter . '">' . $title_match[1][0].$icon[$i] . '</a></li>';
        }
        $i++;
        $sub_tabs_counter++;
    }
    $tabs_menu .= '</ul>';
    $tabs_menu .= $class_after;
    return $tabs_menu . '<div class="tab-content'.$class_content.'">' . do_shortcode($content) . '</div>';
}
/* Tab */
function tab_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        "title" => '',
        "icon" => ''
    ), $atts ) );
    global $tabs_counter, $tabs_single;
    $active = '';
    if( $tabs_single == 0 ) {
        $active = " active";
    }
    //$content = str_replace('&nbsp;', '<p class="blank-line clearfix"><br /></p>', $content);
    $tabs_single++;
    return '<div id="tab' . $tabs_counter . '-' . $tabs_single . '" class="tab-pane' . $active . '">' . do_shortcode( $content ) . '</div>';
}
$accordion_counter = 0;
$accordion_opened = false;
function accordion_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        "opened" => "false",
        'style' => ''
    ), $atts ) );
    wp_enqueue_script('collapse');
    global $accordion_counter, $accordion_opened;
    $accordion_counter++;
    if($opened=="true") {
        $accordion_opened = true;
    }
    $style_class='';
    if($style=="style-2") {
        $style_class = " style-2 collapsed";
    }
    return '<div class="panel-group'.$style_class.'" id="accordion' . $accordion_counter . '">' .  do_shortcode($content) . '</div>';
}
$accordion_item_counter = 0;
function accordion_item_func( $atts,  $content ) {
    extract( shortcode_atts( array(
            'title' => ''
    ), $atts ) );
    $opened_class = '';
    global $accordion_item_counter, $accordion_opened;
    if( $accordion_opened ) {
        $opened_class = " in";
        $closed_class = '';
        $accordion_opened = false;
    } else {
        $closed_class = " class='collapsed'";
    }
    $accordion_item_counter++;
    return '<div class="panel">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" '.$closed_class.' href="#collapse' . $accordion_item_counter . '">' . $title . '</a>
                    </h4>
                </div>
                <div id="collapse' . $accordion_item_counter . '" class="panel-collapse collapse'.$opened_class.'">
                    <div class="panel-body">' .  do_shortcode($content) . '</div>
                </div>
            </div>';
}
/* Contact info */
function anps_contact_info_func( $atts,  $content ) {
    return "<ul class='contact-info'>".do_shortcode($content)."</ul>";
}
/* Contact info item */
function anps_contact_info_item_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'icon' => '',
        'icon_type' => '',
        'icon_fontawesome' => '',
        'icon_openiconic' => '',
        'icon_typicons' => '',
        'icon_entypo' => '',
        'icon_linecons' => '',
        'icon_monosocial' => '',
    ), $atts ) );

    $icon_class = 'fa fa-' . $icon;
    /* Check for VC icon types */
    if( $icon_type ) {
        vc_icon_element_fonts_enqueue( $icon_type );
        $icon_type = 'icon_' . $icon_type;
        if( $$icon_type ) { $icon_class = $$icon_type; }
    }

    return "<li><i class='".$icon_class."'></i>".$content."</li>";
}
/* Faq */
$faq_counter = 0;
function anps_faq_func($atts, $content) {
    wp_enqueue_script('collapse');
    global $faq_counter;
    $faq_counter++;
    return "<div class='panel-group faq' id='accordion".$faq_counter."'>".do_shortcode($content)."</div>";
}
/* Faq item */
$faq_item_counter = 0;
function anps_faq_item_func($atts, $content) {
    extract( shortcode_atts( array(
        'title' => '',
        'answer_title' => ''
    ), $atts ) );
    global $faq_counter;
    global $faq_item_counter;
    $faq_item_counter++;
    $faq_data = "<div class='panel'>";
    $faq_data .= "<div class='panel-heading'>";
    $faq_data .= "<h4 class='panel-title'>";
    $faq_data .= "<a class='collapsed' data-toggle='collapse' data-parent='#accordion".$faq_counter."' href='#collapse".$faq_item_counter."'>".$title."</a>";
    $faq_data .= "</h4>";
    $faq_data .= "</div>";
    $faq_data .= "<div id='collapse".$faq_item_counter."' class='panel-collapse collapse'>";
    $faq_data .= "<div class='panel-body'>";
    $faq_data .= "<h4>".$answer_title."</h4>";
    $faq_data .= "<p>".$content."</p>";
    $faq_data .= "</div>";
    $faq_data .= "</div>";
    $faq_data .= "</div>";
    return $faq_data;
}
/* Pricing table */
function anps_pricing_table_func( $atts,  $content ) {
    extract( shortcode_atts( array(
            'title' => '',
            'currency' => '&euro;',
            'price' => '0',
            'period' => '',
            'button_text' => '',
            'button_url' => '',
            'featured' => ''
        ), $atts ) );

        if( $button_text != '' ) {
        	$button_text = '<li><a class="btn btn-md" href="' . $button_url . '">' . $button_text . '</a></li>';
        }
        $exposed_class = '';
        if($featured) {
            $exposed_class = " exposed";
        }
        $pricing_data = "<div class='pricing-table$exposed_class'>";
        $pricing_data .= "<header>";
        $pricing_data .= "<h2>".$title."</h2>";
        $pricing_data .= "<span class='currency'>".$currency."</span><span class='price'>".$price."</span>";
        if($period) {
            $pricing_data .= "<div class='date'>".$period."</div>";
        }
        $pricing_data .= "</header>";
        $pricing_data .= "<ul>".do_shortcode($content).$button_text."</ul>";
        $pricing_data .= "</div>";
        return $pricing_data;
}
/* END Pricing table */
/* Pricing item */
function anps_pricing_table_item_func( $atts,  $content ) {
    extract( shortcode_atts( array(), $atts ) );
    return '<li>'.$content ."</li>";
}
/* Testimonials */
global $testimonial_counter;
$testimonial_counter = 0;
function anps_testimonials($atts,  $content) {
extract( shortcode_atts( array(
            'style' => 'style-1'
        ), $atts ) );
        if ($style=="") {$style = 'style-1';}
        $testimonials_number = substr_count($content, "[testimonial");
        $class = "testimonials testimonials-"  . $style;
        $data_return = "";
        $style_class = "";
        $randomid = substr( md5(rand()), 0, 7);

        if( $style == 'style-3' ) {
            global $testimonials_style;
            $testimonials_style = 'style-3';

            $data_return .= "<div id='".$randomid."' class='owl-carousel " . $class . "' data-col='2'>";
            $data_return .= do_shortcode($content);
            $data_return .= "</div>";

            if($testimonials_number > 2) {
                $data_return .= '<div class="owl-navigation">';
                $data_return .= '<a class="owlprev"><i class="fa fa-chevron-left"></i></a>';
                $data_return .= '<a class="owlnext"><i class="fa fa-chevron-right"></i></a>';
                $data_return .= '</div>';
            }

        } else {
            if($testimonials_number > 1) {
                $data_return .= "<div id='".$randomid."' class='carousel " . $class . " slide' data-ride='carousel'>";
                $class = "carousel-inner";
            }
            global $testimonial_counter;
            $testimonial_counter = 0;
            $data_return .= '<div class="'.$class.'">'.do_shortcode($content).'</div>';
            if($testimonials_number>1) {
                $data_return .= '<a class="left carousel-control" href="#'.$randomid.'" data-slide="prev">';
                $data_return .= '<i class="fa fa-chevron-left"></i>';
                $data_return .= "</a>";
                $data_return .= '<a class="right carousel-control" href="#'.$randomid.'" data-slide="next">';
                $data_return .= '<i class="fa fa-chevron-right"></i>';
                $data_return .= '</a>';
                $data_return .= "</div>";
            }
        }

        return $data_return;
}
/* Testimonial item */
$testimonial_counter = 0;
function anps_testimonial($atts,  $content) {
        extract( shortcode_atts( array(
            'image' => '',
            'image_u' => '',
            "user_name" => '',
            "user_url" => ''
        ), $atts ) );
        global $testimonial_counter;
        $testimonial_counter++;
        $class = '';
        if($testimonial_counter=="1") {
            $class = " active";
        }
        if($image_u) {
            $image = wp_get_attachment_image_src($image_u, 'full');
            $image = $image[0];
        }
        $data = '';
        $data .= "<blockquote class='item".$class."'>";
        $data .= "<header>";
        if($image) {
            $data .= "<img src='".$image."' >";
        }
        $data .= "</header>";
        $data .= "<p>".$content."</p>";
        $data .= "<cite>";
        $data .= $user_name;
        if($user_url) {
            $data .= " / ";
            $data .= "<a href='".$user_url."' target='_blank'>".$user_url."</a>";
        }
        $data .= "</cite>";
        $data .= "</blockquote>";
        return $data;
}
}
/* Table */
function anps_table_func( $atts,  $content ) {
    return "<table class='table'>".do_shortcode($content)."</table>";
}
/* thead */
function anps_table_thead_func( $atts,  $content ) {
    return "<thead>".do_shortcode($content)."</thead>";
}
/* tbody */
function anps_table_tbody_func( $atts,  $content ) {
    return "<tbody>".do_shortcode($content)."</tbody>";
}
/* tfoot */
function anps_table_tfoot_func( $atts,  $content ) {
    return "<tfoot>".do_shortcode($content)."</tfoot>";
}
/* data row */
function anps_table_row_func( $atts,  $content ) {
    return "<tr>".do_shortcode($content)."</tr>";
}
/* data column */
function anps_table_td_func( $atts,  $content ) {
    return "<td>".do_shortcode($content)."</td>";
}
/* data head column */
function anps_table_th_func( $atts,  $content ) {
    return "<th>".do_shortcode($content)."</th>";
}
/* Heading */
function anps_heading_func( $atts,  $content ) {
    extract( shortcode_atts( array(
        'size' => '1',
        'heading_class' => "heading",
        'h_class' => '',
        'h_id' => '',
        'heading_style' => 'style-1',
        'color' => ''
    ), $atts ) );
    $id = '';
    if($h_id) {
        $id = " id='".$h_id."'";
    }
    switch($heading_class) {
        case "content_heading" : $head_class = " class='heading-content $h_class $heading_style'"; break;
        case "heading" : $head_class = " class='heading-middle $h_class $heading_style'"; break;
        case "style-3" : $head_class = " class='heading-left $h_class $heading_style'"; break;
    }
    /* heading color */
    $color_h = '';
    if(isset($color)&&$color!='') {
        $color_h = " style='color: $color'";
    }
    return '<h'.$size.$head_class.' '.$id.$color_h.'><span>' . $content . '</span></h'.$size.'>';
}
/* Timeline */
if(!function_exists('anps_timeline_func')) {
    function anps_timeline_func($atts, $content) {
        extract( shortcode_atts( array(), $atts ) );

        return '<div class="timeline">' . do_shortcode($content) . '</div>';
    }
}
/* END Timeline */
/* Timeline Item */
if(!function_exists('anps_timeline_item_func')) {
    function anps_timeline_item_func($atts, $content) {
        extract( shortcode_atts( array(
            'title' => '',
            'year'  => '2016'
        ), $atts ) );

        $return = '<div class="timeline-item">';
            $return .= '<div class="timeline-year">' . $year . '</div>';
            $return .= '<div class="timeline-content">';
                $return .= '<h3 class="timeline-title">' . $title . '</h3>';
                $return .= '<div class="timeline-text">' . $content . '</div>';
            $return .= '</div>';
        $return .= '</div>';

        return $return;
    }
}
/* END Timeline Item */
