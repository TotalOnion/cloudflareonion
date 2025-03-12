<div class="wrap">
    <h3>Purge by Market</h3>
    <p><? echo __('Click on the market you wish to clear:', GLOBAL_CFO_NAME);?></p>
    <div class="cfo-purge-container" id="cfo-purge-container">
        <?php
            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                $languages = apply_filters( 'wpml_active_languages', NULL );
                foreach ($languages as $language) { ?>
                    <div data-cfo-market="<?php echo $language['id']; ?>" class="button primary">
                        <span><?php echo $language['translated_name'];?></span>
                        <img src="<?php echo $language['country_flag_url'];?>">
                    </div>
                <?php }
            } else {
                echo "WPML is not enabled on this website.";
            }
        ?>
    </div>
    <div id="cfo-purge-result"></div>
</div>