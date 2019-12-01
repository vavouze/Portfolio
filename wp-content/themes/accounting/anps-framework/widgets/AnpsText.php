<?php

class AnpsText extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'AnpsText', __('AnpsThemes - Text and icon', 'accounting'), array('description' => __('Enter text and/or icon to show on page. Can only be used in the Top bar widget areas.', 'accounting'),)
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'anps_enqueue_scripts' ) );
        add_action( 'admin_footer-widgets.php', array( $this, 'anps_print_scripts' ), 9999 );
    }

    function anps_enqueue_scripts( $hook_suffix ) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    function anps_print_scripts() {
        ?>
        <script>
            ( function( $ ){
                function initColorPicker( widget ) {
                    widget.find( '.anps-color-picker' ).wpColorPicker();
                }

                function onFormUpdate( event, widget ) {
                    initColorPicker( widget );
                }

                $( document ).on( 'widget-added widget-updated', onFormUpdate );
                $( document ).ready( function() {
                    $( '#widgets-right .widget:has(.anps-color-picker)' ).each( function () {
                        initColorPicker( $( this ) );
                    } );
                } );
            }( jQuery ) );
        </script>
        <?php
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array(
                'icon' => '',
                'text'=>'',
                'title'=>'',
                'subtitle'=>'',
                'icon_color'=>'',
                'title_color'=>'',
                'subtitle_color'=>''
            )
        );

        $icon = $instance['icon'];
        $icon = 'fa-' . trim($icon);
        $icon = str_replace('fa-fa-', 'fa-', $icon);

        $title = htmlentities($instance['title']);
        $subtitle = htmlentities($instance['subtitle']);
        $text = htmlentities($instance['text']);

        if($title == '' && $text != '') {
            $title = $text;
        }
        ?>
        <p>
            <div class="anps-iconpicker">
                <i class="fa <?php echo $icon; ?>"></i>
                <input type="text" value="<?php echo $icon; ?>" id="<?php echo esc_attr($this->get_field_id('icon')); ?>" name="<?php echo esc_attr($this->get_field_name('icon')); ?>">
                <button type="button"><?php esc_html_e('Select icon', 'accounting'); ?></button>
            </div>
        </p>
        <!-- Icon color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_color')); ?>"><?php _e("Icon color", 'accounting'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo $this->get_field_id('icon_color'); ?>" name="<?php echo $this->get_field_name('icon_color'); ?>" type="text" value="<?php echo esc_attr($instance['icon_color']); ?>" />
        </p>
        <!-- Title color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title_color')); ?>"><?php _e("Title color", 'accounting'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo $this->get_field_id('title_color'); ?>" name="<?php echo $this->get_field_name('title_color'); ?>" type="text" value="<?php echo esc_attr($instance['title_color']); ?>" />
        </p>
        <!-- Subtitle color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('subtitle_color')); ?>"><?php _e("Subtitle color", 'accounting'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo $this->get_field_id('subtitle_color'); ?>" name="<?php echo $this->get_field_name('subtitle_color'); ?>" type="text" value="<?php echo esc_attr($instance['subtitle_color']); ?>" />
        </p>
        <!-- Title -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e("Title", 'accounting'); ?></label><br />
            <input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" class="widefat" value="<?php echo esc_attr($title); ?>" />
        </p>
        <!-- Subtitle -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('subtitle')); ?>"><?php _e("Subtitle", 'accounting'); ?></label><br />
            <input id="<?php echo esc_attr($this->get_field_id('subtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('subtitle')); ?>" type="text" class="widefat" value="<?php echo esc_attr($subtitle); ?>" />
        </p>
        <!-- <p>
            <input id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text" class="widefat" value="<?php echo esc_attr($text); ?>" />
        </p> -->
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['icon'] = $new_instance['icon'];
        $instance['text'] = $new_instance['text'];
        $instance['title'] = $new_instance['title'];
        $instance['subtitle'] = $new_instance['subtitle'];
        $instance['icon_color'] = $new_instance['icon_color'];
        $instance['title_color'] = $new_instance['title_color'];
        $instance['subtitle_color'] = $new_instance['subtitle_color'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $icon = $instance['icon'];
        $icon = 'fa-' . trim($icon);
        $icon = str_replace('fa-fa-', 'fa-', $icon);
        $text = $instance['text'];

        $icon_color = '';
        if(isset($instance['icon_color'])) {
            $icon_color = $instance['icon_color'];
        }

        $title_color = '';
        if(isset($instance['title_color'])) {
            $title_color = $instance['title_color'];
        }

        $subtitle_color = '';
        if(isset($instance['subtitle_color'])) {
            $subtitle_color = $instance['subtitle_color'];
        }

        $title = isset($instance['title']) ? $instance['title'] : '';
        $subtitle = isset($instance['subtitle']) ? $instance['subtitle'] : '';

        if($icon == 'fa-') {
            $before_widget = str_replace('class="', 'class="widget_anpstext-no-icon ', $before_widget);
        }

        echo $before_widget;
        ?>

        <?php if($icon != 'fa-'): ?>
            <span class="fa <?php echo esc_attr($icon);?>"<?php if($icon_color!=''):?> style="color: <?php echo esc_attr($icon_color);?>"<?php endif; ?>></span>
        <?php endif; ?>
        <?php if($title != ''): ?>
            <span class="anpstext-desc"<?php if($subtitle_color!=''):?> style="color: <?php echo esc_attr($subtitle_color);?>"<?php endif; ?>>
                <span class="important"<?php if($title_color!=''):?> style="color: <?php echo esc_attr($title_color);?>"<?php endif; ?>><?php echo $title; ?></span>
                <?php if($subtitle !== ''): ?>
                    <br />
                    <?php echo $subtitle; ?>
                <?php endif; ?>
            </span>
        <?php else: ?>
            <span class="anpstext-desc"><?php echo $text; ?></span>
        <?php endif;

        echo $after_widget;
    }

}

add_action( 'widgets_init', create_function('', 'return register_widget("AnpsText");') );
