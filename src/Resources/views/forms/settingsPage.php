<?php
$page = isset($_GET['page']) ? $_GET['page'] : GLOBAL_CFO_NAME . 'settings-page';
?>

<div class="wrap">
    <h2>CFO Settings</h2>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=' . GLOBAL_CFO_NAME . 'settings-page'); ?>"
            class="nav-tab <?php echo $page == GLOBAL_CFO_NAME . 'settings-page' ? 'nav-tab-active' : ''; ?>">
            Page Settings
        </a>
        <a href="<?php echo admin_url('admin.php?page=' . GLOBAL_CFO_NAME . '_cache-clearance-page'); ?>"
            class="nav-tab <?php echo $page == GLOBAL_CFO_NAME . '_cache-clearance-page' ? 'nav-tab-active' : ''; ?>">
            Clear Cache
        </a>
    </h2>

</div>
<div class="wrap">

    <?php
    if ($page == GLOBAL_CFO_NAME . 'settings-page') :
    ?>

        <h1>My Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields(GLOBAL_CFO_NAME . '_options');
            do_settings_sections(GLOBAL_CFO_NAME . 'settings-page');
            submit_button();
            ?>
        </form>

    <?php
    else:
    ?>
        <h1>Cache Clearance</h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php
            settings_fields(GLOBAL_CFO_NAME . '_clearance');
            do_settings_sections(GLOBAL_CFO_NAME . '_cache-clearance-page');
            submit_button('Validate URLs');
            ?>
        </form>

    <?php
    endif;
    ?>

</div>