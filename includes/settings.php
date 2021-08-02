<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

add_action('admin_init', 'emcs_settings_init');

function emcs_settings_init()
{
    register_setting('emcs', 'emcs_settings', ['sanitize_callback' => 'emcs_sanitize_input']);

    add_settings_section(
        'emcs_api_section',
        __('Setup Calendly connection', 'emcs'),
        '',
        'emcs'
    );

    add_settings_field(
        'emcs_api_field',
        __('API Key', 'emcs'),
        'emcs_api_field_cb',
        'emcs',
        'emcs_api_section',
        array(
            'label_for' => 'emcs_api_key'
        )
    );
}

function emcs_api_field_cb($args)
{
    $options = get_option('emcs_settings');
?>
    <div class="form-row">
        <div class="form-group col-md-8">
            <input id="<?php echo esc_attr($args['label_for']); ?>" name="emcs_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo !empty($options['emcs_api_key']) ? esc_attr($options['emcs_api_key']) : ''; ?>" class="form-control" placeholder="Paste your API key here"/>
            <p id="<?php echo esc_attr($args['label_for']); ?>_description">
                Your API Key can be found on Calendly <a href="https://calendly.com/integrations" target="_blank"><em>integerations</em></a> page
            </p>
        </div>
    </div>
<?php
}

add_action('admin_menu', 'emcs_settings_page');

function emcs_settings_page()
{
    add_submenu_page(
        'emcs-event-types',
        __('', 'emcs'),
        __('Settings', 'emcs'),
        'manage_options',
        'emcs-settings',
        'emcs_settings_page_html',
    );
}

function emcs_settings_page_html()
{
    // Show the settings page to only admins
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('emcs_messages', 'emcs_message', __('Settings Saved', 'emcs'), 'updated');
    }
?>
    <div class="emcs-title">
        <img src="<?php echo esc_url(EMCS_URL . 'assets/img/emc-logo.svg') ?>" alt="embed calendly logo" width="200px" />
    </div>
    <div class="emcs-subtitle">Settings</div>
    <?php settings_errors('emcs_messages'); ?>
    <div class="sc-wrapper">
        <div class="sc-container">
            <div class="row emcs-settings-form">
                <div class="col-md-9">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('emcs');
                        do_settings_sections('emcs');
                        submit_button('Save Settings');
                        ?>
                    </form>
                </div>
                <div class="col-md-3 emcs-promotion-container">
                    <h3>Like this plugin?</h3>
                    <p>
                        If you find this plugin useful, please show your love and support by
                        rating it
                        <span class="dashicons dashicons-star-filled emcs-dashicon emcs-dashicon-rating"></span>
                        <span class="dashicons dashicons-star-filled emcs-dashicon emcs-dashicon-rating"></span>
                        <span class="dashicons dashicons-star-filled emcs-dashicon emcs-dashicon-rating"></span>
                        <span class="dashicons dashicons-star-filled emcs-dashicon emcs-dashicon-rating"></span>
                        <span class="dashicons dashicons-star-filled emcs-dashicon emcs-dashicon-rating"></span>
                        on<a href="https://wordpress.org/support/plugin/embed-calendly-scheduling/reviews/#new-post" target="_blank"> WordPress.org </a>
                        - much appreciated! :-D
                    </p><br>
                    <div class="emcs-promotion">
                        <h2>Need Support?</h2>
                        <p>
                            Please use the <a href="https://wordpress.org/support/plugin/embed-calendly-scheduling/" target="_blank"> support forums on WordPress.org </a> to
                            submit a support ticket or report a bug.
                        </p>
                    </div>
                    <div class="emcs-promotion">
                        <h2>Donate</h2>
                        <p>
                            Your generous donation will help me keep supporting and improving the plugin. Thank you :)
                        <ul>
                            <li><strong>BTC:</strong> 1DbpWbEAcfme2oEs2fTP67sCMqzz8sxktW</li>
                            <li><strong>Ethereum:</strong> 0xeadc1c4e0103a8239345ab4dee1bea27a81a0b60</li>
                            <li><strong>USDT:</strong> 0xeadc1c4e0103a8239345ab4dee1bea27a81a0b60</li>
                        </ul>
                        </p>
                        <div class="emcs-text-center">
                            <a href="https://flutterwave.com/pay/emc-donate" target="_blank">
                                <img src="<?php echo esc_url(EMCS_URL . 'assets/img/donate.png'); ?>" width="100" alt="Donate" />
                            </a>
                        </div>
                    </div>
                    <div class="emcs-thankyou" id="emcs-thankyou">
                        <h3>Thank you for downloading Embed Calendly</h3>
                        <p>
                            I built this plugin during one of the most challenging times
                            I've been through, I was depressed and I didn't feel like my life meant much.
                            So I thought to try out a random personal challenge during a weekend, nothing serious,
                            then I built the plugin. I never expected it to have any downloads at all,
                            but then it started coming in; and the fact that I saw my plugin,
                            something from me, was actively used on 10 websites, then 100, 400, and 1000+,
                            gave me a different perspective about myself, it gave me more meaning
                            and that set me on a path that is changing my life.
                            Thank you very much for your download, I sincerely appreciate it. :)
                            <span class="emcs-author">- Shycoder</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

function emcs_sanitize_input($input) {
    
    $sanitized_input = [];
    $api_key = str_replace(' ', '', $input['emcs_api_key']);
    $api_key = strip_tags(stripslashes($api_key));
    $sanitized_input['emcs_api_key'] = sanitize_text_field($api_key);

    return $sanitized_input;
}