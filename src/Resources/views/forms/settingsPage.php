<div class="wrap">
    <h1>My Settings</h1>
    <form method="post" action="options.php">
        <?php
            settings_fields(GLOBAL_CFO_NAME.'_options');
            do_settings_sections(GLOBAL_CFO_NAME.'settings-page');
            submit_button();
        ?>
    </form>
</div>