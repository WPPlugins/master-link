<div class="wrap">
    <h2><?php _e('Master Link Plugin') ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'master_link_plugin' ); ?>
        <?php do_settings_sections('master_link_plugin'); ?>

        <?php submit_button(); ?>
    </form>
</div>
