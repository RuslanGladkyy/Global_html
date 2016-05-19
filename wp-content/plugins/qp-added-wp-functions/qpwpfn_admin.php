<?php

    if($_POST['qpwpfn_hidden'] == 'Y') {

        //From data sent
        $qpwpfn1 = $_POST['qpwpfn1'];
        update_option('qpwpfn1', $qpwpfn1);
        
        $qpwpfn2 = $_POST['qpwpfn2'];
        update_option('qpwpfn2', $qpwpfn2);
        
        $qpwpfn3 = $_POST['qpwpfn3'];
        update_option('qpwpfn3', $qpwpfn3);
        
        $qpwpfn4 = $_POST['qpwpfn4'];
        update_option('qpwpfn4', $qpwpfn4);
        
        $qpwpfn5 = $_POST['qpwpfn5'];
        update_option('qpwpfn5', $qpwpfn5);
        
        $qpwpfn6 = $_POST['qpwpfn6'];
        update_option('qpwpfn6', $qpwpfn6);
        
        $qpwpfn_lge = $_POST['qpwpfn_lge'];
        update_option('qpwpfn_lge', $qpwpfn_lge);
        
        $qpwpfn7 = $_POST['qpwpfn7'];
        update_option('qpwpfn7', $qpwpfn7);
        
        $qpwpfn_as = $_POST['qpwpfn_as'];
        update_option('qpwpfn_as', $qpwpfn_as);
        
        $qpwpfn8 = $_POST['qpwpfn8'];
        update_option('qpwpfn8', $qpwpfn8);
        
        $qpwpfn9 = $_POST['qpwpfn9'];
        update_option('qpwpfn9', $qpwpfn9);
        
        $qpwpfn_ch = $_POST['qpwpfn_ch'];
        update_option('qpwpfn_ch', $qpwpfn_ch);
        
        $qpwpfn10 = $_POST['qpwpfn10'];
        update_option('qpwpfn10', $qpwpfn10);
        
        $qpwpfn11 = $_POST['qpwpfn11'];
        update_option('qpwpfn11', $qpwpfn11);
        
        $qpwpfn12 = $_POST['qpwpfn12'];
        update_option('qpwpfn12', $qpwpfn12);
        
        $qpwpfn13 = $_POST['qpwpfn13'];
        update_option('qpwpfn13', $qpwpfn13);
        
        $qpwpfn_jq = $_POST['qpwpfn_jq'];
        update_option('qpwpfn_jq', $qpwpfn_jq);
        
        $qpwpfn14 = $_POST['qpwpfn14'];
        update_option('qpwpfn14', $qpwpfn14);
        
        $qpwpfn15 = $_POST['qpwpfn15'];
        update_option('qpwpfn15', $qpwpfn15);
        
        $qpwpfn_cdcon = stripslashes($_POST['qpwpfn_cdcon']);
        update_option('qpwpfn_cdcon', $qpwpfn_cdcon);
        
        $qpwpfn_cdtit = $_POST['qpwpfn_cdtit'];
        update_option('qpwpfn_cdtit', $qpwpfn_cdtit);
        
        $qpwpfn16 = $_POST['qpwpfn16'];
        update_option('qpwpfn16', $qpwpfn16);
        
        $qpwpfn_foo = stripslashes($_POST['qpwpfn_foo']);
        update_option('qpwpfn_foo', $qpwpfn_foo);
        
        $qpwpfn17 = $_POST['qpwpfn17'];
        update_option('qpwpfn17', $qpwpfn17);
        
        $qpwpfn_cl = $_POST['qpwpfn_cl'];
        update_option('qpwpfn_cl', $qpwpfn_cl);
        
        $qpwpfn18 = $_POST['qpwpfn18'];
        update_option('qpwpfn18', $qpwpfn18);
        
        $qpwpfn19 = $_POST['qpwpfn19'];
        update_option('qpwpfn19', $qpwpfn19);
        
        $qpwpfn_bf = $_POST['qpwpfn_bf'];
        update_option('qpwpfn_bf', $qpwpfn_bf);
        
        $qpwpfn20 = $_POST['qpwpfn20'];
        update_option('qpwpfn20', $qpwpfn20);
        
        $qpwpfn_ga = stripslashes($_POST['qpwpfn_ga']);
        update_option('qpwpfn_ga', $qpwpfn_ga);
        
        $qpwpfn21 = $_POST['qpwpfn21'];
        update_option('qpwpfn21', $qpwpfn21);
        
        $qpwpfn_grim = $_POST['qpwpfn_grim'];
        update_option('qpwpfn_grim', $qpwpfn_grim);
        
        $qpwpfn_grbn = $_POST['qpwpfn_grbn'];
        update_option('qpwpfn_grbn', $qpwpfn_grbn);
        
        $qpwpfn22 = $_POST['qpwpfn22'];
        update_option('qpwpfn22', $qpwpfn22);
        
        ?>
        <div class="updated"><p><strong><?php _e('Options Saved'); ?></strong></p></div>
        <?php
    }   else {
        //Normal page display
        $qpwpfn1 = get_option('qpwpfn1');
        $qpwpfn2 = get_option('qpwpfn2');
        $qpwpfn3 = get_option('qpwpfn3');
        $qpwpfn4 = get_option('qpwpfn4');
        $qpwpfn5 = get_option('qpwpfn5');
        $qpwpfn6 = get_option('qpwpfn6');
        $qpwpfn7 = get_option('qpwpfn7');
        $qpwpfn8 = get_option('qpwpfn8');
        $qpwpfn9 = get_option('qpwpfn9');
        $qpwpfn10 = get_option('qpwpfn10');
        $qpwpfn11 = get_option('qpwpfn11');
        $qpwpfn12 = get_option('qpwpfn12');
        $qpwpfn13 = get_option('qpwpfn13');
        $qpwpfn14 = get_option('qpwpfn14');
        $qpwpfn15 = get_option('qpwpfn15');
        $qpwpfn16 = get_option('qpwpfn16');
        $qpwpfn17 = get_option('qpwpfn17');
        $qpwpfn18 = get_option('qpwpfn18');
        $qpwpfn19 = get_option('qpwpfn19');
        $qpwpfn20 = get_option('qpwpfn20');
        $qpwpfn21 = get_option('qpwpfn21');
        $qpwpfn22 = get_option('qpwpfn22');
        $qpwpfn_as = get_option('qpwpfn_as');
        $qpwpfn_bf = get_option('qpwpfn_bf');
        $qpwpfn_cdcon = get_option('qpwpfn_cdcon');
        $qpwpfn_cdtit = get_option('qpwpfn_cdtit');
        $qpwpfn_cl = get_option('qpwpfn_cl');
        $qpwpfn_ch = get_option('qpwpfn_ch');
        $qpwpfn_furl1 = get_option('qpwpfn_furl1');
        $qpwpfn_furl2 = get_option('qpwpfn_furl2');
        $qpwpfn_ga = get_option('qpwpfn_ga');
        $qpwpfn_grbn = get_option('qpwpfn_grbn');
        $qpwpfn_grim = get_option('qpwpfn_grim');
        $qpwpfn_lge = get_option('qpwpfn_lge');
        $qpwpfn_jq = get_option('qpwpfn_jq');
        $qpwpfn_foo = get_option('qpwpfn_foo');
    }

?>
        <div class="qpwpfn_admin">
            <?php echo "<h2>" . _('Added WP Functions by QP') . "</h2>"; ?>

            <form class="qpwpfn_form" name="qpwpfn_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URL']); ?>">

                <input type="hidden" name="qpwpfn_hidden" value="Y">

                <?php echo "<h4>" . _('Added WP Functions Settings') . "</h4>"; ?>

                <p>Visit <a href="http://quatipixels.pw/wordpress/plugin/added-wp-functions" target="_blank">here for <b>Latest Version</b></a></p>

                <p>Check the box next to your desired functions to activate it.</p>

                <p><input type="checkbox" name="qpwpfn1" value="1" <?php if($qpwpfn1 == 1) { echo "checked"; } ?> ><?php _e("Redirect logout"); ?></p>

                <p><input type="checkbox" name="qpwpfn2" value="1" <?php if($qpwpfn2 == 1) { echo "checked"; } ?> ><?php _e("Remove admin bar from frontend for all users"); ?></p>

                <p><input type="checkbox" name="qpwpfn3" value="1" <?php if($qpwpfn3 == 1) { echo "checked"; } ?> ><?php _e("Remove unnecessary meta-data from your WordPress site"); ?></p>

                <p><input type="checkbox" name="qpwpfn4" value="1" <?php if($qpwpfn4 == 1) { echo "checked"; } ?> ><?php _e("Disable HTML in WordPress comments"); ?></p>

                <p><input type="checkbox" name="qpwpfn5" value="1" <?php if($qpwpfn5 == 1) { echo "checked"; } ?> ><?php _e("Change the Post Auto-Save Interval - 120secs"); ?></p>

                <p><input type="checkbox" name="qpwpfn6" value="1" <?php if($qpwpfn6 == 1) { echo "checked"; } ?> ><?php _e("Hide WP Login Error"); ?><br/>
                    <input type="text" name="qpwpfn_lge" value="<?php echo $qpwpfn_lge; ?>" placeholder="There seems to be a problem, Please try again" size="50" ></p>

                <p><input type="checkbox" name="qpwpfn7" value="1" <?php if($qpwpfn7 == 1) { echo "checked"; } ?> ><?php _e("Change Author slug"); ?><br/>
                    <input type="text" name="qpwpfn_as" value="<?php echo $qpwpfn_as; ?>" placeholder="author" ></p>

                <p><input type="checkbox" name="qpwpfn8" value="1" <?php if($qpwpfn8 == 1) { echo "checked"; } ?> ><?php _e("Add 'Next-page'-button in WYSIYG-editor"); ?></p>

                <p><input type="checkbox" name="qpwpfn9" value="1" <?php if($qpwpfn9 == 1) { echo "checked"; } ?> ><?php _e("Customize admin message"); ?><br/>
                    <input type="text" name="qpwpfn_ch" value="<?php echo $qpwpfn_ch; ?>" placeholder="Welcome" ></p>

                <p><input type="checkbox" name="qpwpfn10" value="1" <?php if($qpwpfn10 == 1) { echo "checked"; } ?> ><?php _e("Change Your WordPress Feed Links without .htaccess"); ?><br/>
                    <input type="url" size="55" name="qpwpfn_furl1" value="<?php echo $qpwpfn_furl1; ?>" placeholder="<?php _e("Feed URL"); ?>" ><br/>
                    <input type="url" size="55" name="qpwpfn_furl2" value="<?php echo $qpwpfn_furl2; ?>" placeholder="<?php _e("Other Feed URL"); ?>" ></p>

                <p><input type="checkbox" name="qpwpfn11" value="1" <?php if($qpwpfn11 == 1) { echo "checked"; } ?> ><?php _e("Set the number of post revisions available to '5' unless the constant was set in wp-config.php"); ?></p>

                <p><input type="checkbox" name="qpwpfn12" value="1" <?php if($qpwpfn12 == 1) { echo "checked"; } ?> ><?php _e("Delay feed update by 15minutes"); ?></p>

                <p><input type="checkbox" name="qpwpfn13" value="1" <?php if($qpwpfn13 == 1) { echo "checked"; } ?> ><?php _e("Loading jQuery from the External CDN. Default is Google CDN: https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js "); ?><br/>
                    <input type="url" size="55" name="qpwpfn_jq" value="<?php echo $qpwpfn_jq; ?>" placeholder="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></p>

                <p><input type="checkbox" name="qpwpfn14" value="1" <?php if($qpwpfn14 == 1) { echo "checked"; } ?> ><?php _e("Custom Admin Menu Link For All Settings"); ?></p>

                <p><input type="checkbox" name="qpwpfn15" value="1" <?php if($qpwpfn15 == 1) { echo "checked"; } ?> ><?php _e("Add Custom Dashboard Widgets in WordPress"); ?><br/>
                    <?php _e("Custom Dashboard Title"); ?><input type="text" name="qpwpfn_cdtit" value="<?php echo $qpwpfn_cdtit; ?>" placeholder="Theme Support" ><br/>
                    <?php _e("Custom Dashboard Content"); ?><br/><textarea rows="10" cols="30" name="qpwpfn_cdcon" ><?php echo $qpwpfn_cdcon; ?></textarea></p>

                <p><input type="checkbox" name="qpwpfn16" value="1" <?php if($qpwpfn16 == 1) { echo "checked"; } ?> ><?php _e("Change the Footer in WordPress Admin Panel"); ?><br/>
                    <?php _e("New Footer Text"); ?><br/><textarea rows="10" cols="30" name="qpwpfn_foo" ><?php if (empty($qpwpfn_foo)) {$qpwpfn_foo = 'Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | WordPress Tutorials: <a href="http://www.quatipixels.pw" target="_blank">Quati Pixels</a>';} echo $qpwpfn_foo; ?></textarea></p>

                <p><input type="checkbox" name="qpwpfn17" value="1" <?php if($qpwpfn17 == 1) { echo "checked"; } ?> ><?php _e("Add a Custom Dashboard Logo"); ?><br/>
                    <?php _e("Custom Dashboard Logo URL (must be a .gif file)"); ?><input type="url" size="55" name="qpwpfn_cl" value="<?php echo $qpwpfn_cl; ?>" placeholder="http://yourdomain.com/logo.gif" ></p>

                <p><input type="checkbox" name="qpwpfn18" value="1" <?php if($qpwpfn18 == 1) { echo "checked"; } ?> ><?php _e("Remove WordPress Version Number"); ?></p>

                <p><input type="checkbox" name="qpwpfn19" value="1" <?php if($qpwpfn19 == 1) { echo "checked"; } ?> ><?php _e("Add a Favicon to your Blog"); ?><br/>
                    <?php _e("Favicon Image URL (must be a .ico, .png, or .jpg file): "); ?><input type="url" size="55" name="qpwpfn_bf" value="<?php echo $qpwpfn_bf; ?>" placeholder="http://yourdomain.com/favicon.ico" ></p>

                <p><input type="checkbox" name="qpwpfn20" value="1" <?php if($qpwpfn20 == 1) { echo "checked"; } ?> ><?php _e("Add Google Analytics"); ?><br/>
                    <?php _e("Paste Google Analytics tracking code"); ?><br/><textarea rows="10" cols="30" name="qpwpfn_ga" ><?php echo $qpwpfn_ga; ?></textarea></p>

                <p><input type="checkbox" name="qpwpfn21" value="1" <?php if($qpwpfn21 == 1) { echo "checked"; } ?> ><?php _e("Change the Default Gravatar in WordPress"); ?><br/>
                    <?php _e("New Gravatar Image URL (must be a .gif file): "); ?><input type="url" size="55" name="qpwpfn_grim" value="<?php echo $qpwpfn_grim; ?>" placeholder="http://yourdomain.com/gravatar.gif" ><br/>
                    <?php _e("New Gravatar Brand Name: "); ?><input type="text" name="qpwpfn_grbn" value="<?php echo $qpwpfn_grbn; ?>" placeholder="Brand Name" ></p>

                <p><input type="checkbox" name="qpwpfn22" value="1" <?php if($qpwpfn22 == 1) { echo "checked"; } ?> ><?php _e("Disable Search in WordPress"); ?></p>

                <p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options', 'qpwpfn'); ?>" ></p>
            </form>
        </div>