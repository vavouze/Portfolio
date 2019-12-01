<?php get_header();
//var_dump(get_post());
//die;

?>
<section class="blog-single">
    <div class="container">
        <div class="row">
                <div class="col-md-12">
    <article class='post style-2' id='site-content'>
        <header class="text-center">
                <?php
                            $args = array(
                                'post_type' => 'offres',
                                'post_status' => 'publish',
                                'ignore_sticky_posts' => true,
                                'posts_per_page' => 100,
                                'meta_query' => array(),
                                
                            );
                            $query = new WP_Query( $args );

                        if ( $query->have_posts() ) {
                            $fields = get_fields($post);
                            ?>
                            <h1 class="single-blog"><?php echo($post->post_title);?></h1>
                            <h2 class="single-blog"><?php echo($fields['reference']);?></h2>
                            <div class='post-meta'>
                                <ul>
                                    <li class="post-meta-categories"><i class='fa fa-folder-open'></i>
                                    </li>
                                    <li class="post-meta-date"><i class='fa fa-calendar'></i><?php echo($post->post_date);?></li>
                                </ul>
                            </div>
        </header>
        <div class='post-content'>
            <p><?php echo($post->post_content);?></p>
            <p><?php echo($fields['lieu_de_travail']);?></p>
            <p><?php echo($fields['candidature']);?></p>
        </div>
     </article>                  
                    </div>
        </div>
    </div>
 </section>
 <script type="text/javascript">
    document.getElementById('refJob').value = "<?php echo($fields['reference']);?>";
     </script>
                    <?php
                        }?>
<?php get_footer(); ?>