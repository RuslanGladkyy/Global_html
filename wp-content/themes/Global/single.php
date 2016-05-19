<?php get_header(); ?>


    <div class="content1">
            <br>
            <br>
          <?php if(have_posts() ) : while(have_posts() ) : the_post(); ?>
            <div class="block1">
              <?php the_post_thumbnail(); ?>
              <h1 class="text1"><?php the_title(); ?></h1>
              <h2 class="text2"><?php the_content(); ?></h2>
            </div>
          <?php endwhile; ?>

          <?php else: ?> 

          <?php endif; ?>

  


    </div>
  </div>

<?php get_footer(); ?>
