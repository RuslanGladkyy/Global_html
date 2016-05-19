<?php get_header(); ?>

  <h1 class="content"><?php the_title(); ?></h1>
    <div class="content1">

          <?php if(have_posts() ) : while(have_posts() ) : the_post(); ?>
            <div class="block1">
            </div>
          <?php endwhile; ?>

          <?php else: ?> 

          <?php endif; ?>

  


    </div>
  </div>

<?php get_footer(); ?>
