
  <?php
  global $anps_options_data;
  $coming_soon = get_option('coming_soon', '0');
  if((!$coming_soon || $coming_soon=="0") || is_super_admin()) {
    get_sidebar( 'footer' );
  }
  ?>

  <?php global $anps_parallax_slug;
  if (count($anps_parallax_slug)>0) : ?>
  <script>
      jQuery(function($) {
          <?php for($i=0;$i<count($anps_parallax_slug);$i++) : ?>
              $("#<?php echo esc_js($anps_parallax_slug[$i]); ?>").parallax("50%", 0.6);
          <?php endfor; ?>
      });
  </script>
  <?php endif;?>
  <?php  if(anps_get_option($anps_options_data, 'preloader')=="on") : ?>
  <script>
    jQuery(function ($) {
      $("body").queryLoader2({
        backgroundColor: "#fff",
        barColor: "333",
          barHeight: 0,
        percentage: true,
          onComplete : function() {
            $(".site-wrapper, .colorpicker").css("opacity", "1");
          }
      });
    });
  </script>
  <?php endif; ?>
</div>

<div id="scrolltop" class="fixed scrollup"><button title="<?php esc_html_e('Scroll to top', 'accounting'); ?>"><i class="fa fa-angle-up"></i></button></div>
<input type="hidden" id="theme-path" value="<?php echo get_template_directory_uri(); ?>" />
<?php wp_footer(); ?>
</body>
</html>
