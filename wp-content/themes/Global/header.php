<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php bloginfo('name'); ?></title>
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Oxygen:400,700" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">
</head>
<body>
  <div class="wrepper">
    <div class="logo">
      <a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/logo.png" alt="logo"></a>
    </div>
    <div class="poisk"> 
      <form method="get" name="searchform" id="searchform" action="<?php bloginfo('siteurl')?>">
      <input type="text" name="s" id="s" value="<?php echo wp_specialchars($s, 1); ?>" placeholder="Type here..."/>
      </form>
    </div>
    <div class="home">
        <a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/home.png" alt="home"></a>
    </div>
<?php wp_nav_menu(array(
                        'theme_location' => 'menu',
                        'container' => false,
                        'menu_id' => ''


)) ?>
    <div class="follow">
        <h1>Follow us:</h1>
        <a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/foto1.png" alt="foto1"></a>
        <a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/foto3.png" alt="foto2"></a>
        <a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/foto2.png" alt="foto3"></a>
    </div>
    
    