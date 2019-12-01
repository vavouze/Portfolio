<?php
    $subtitle = get_post_meta(get_the_ID(), 'anps_team_subtitle', true);
?>

<div class="row">
    <div class="col-sm-3">
        <div class="team-single-image">
            <?php the_post_thumbnail('full'); ?>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="team-single-wrap">
            <h1 class="team-single-title"><?php the_title(); ?></h1>
            <?php
            if(!empty($subtitle)) {
                echo "<div class='team-single-subtitle'>$subtitle</div>";
            }
            ?>
            <div class="team-single-content"><?php the_content(); ?></div>
        </div>
    </div>
</div>
