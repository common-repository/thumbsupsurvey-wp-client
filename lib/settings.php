<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ThumbsUpSurvey_SettingsClass
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'settings_add_on_menu'));
        add_action('admin_post', array($this, 'tus_settings_save_func'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function settings_add_on_menu()
    {
        // Form calculation variables
        add_submenu_page(
            'options-general.php',
            'Thumbs Up Survey Settings',
            'Thumbs Up Survey👍',
            'manage_options',
            'thumbs-up-survey-settings',
            array($this, 'tus_settings_func')
        );
    }

    public function admin_enqueue_scripts($hook)
    {
        if ('settings_page_thumbs-up-survey-settings' == $hook) {
            wp_enqueue_code_editor(array('type' => 'text/javascript'));
            wp_enqueue_code_editor(array('type' => 'text/css'));

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            wp_enqueue_script('tus-javascript', THUMBSUPSURVEY_WP_PLUGIN_URL . 'assets/settings.js', array('jquery'), THUMBSUPSURVEY_WP_PLUGIN_VER);
            wp_enqueue_style('tus-style', THUMBSUPSURVEY_WP_PLUGIN_URL . 'assets/settings.css', array(), THUMBSUPSURVEY_WP_PLUGIN_VER);
        }
    }

    public function get_settings()
    {
        $settings = get_option('thumbsupsurvey-settings');

        if (!$settings) {
            $settings = array();
        }

        // Salesforce
        if (!isset($settings["salesforce_on"]) || $settings["salesforce_on"] == '' || $settings["salesforce_on"] !== "1") {
            $settings["salesforce_on"] = "0";
        }
        if (!isset($settings["salesforce_survey_id"]) || $settings["salesforce_survey_id"] == '') {
            $settings["salesforce_survey_id"] = "";
        }

        $settings["salesforce_enabled"] = $settings["salesforce_on"] == "1" && $settings["salesforce_survey_id"] !== "";

        return $settings;
    }

    public function set_settings($settings)
    {
        update_option('thumbsupsurvey-settings', $settings);
    }

    public function tus_settings_func()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = $this->get_settings();

?>
        <!-- Our admin page content should all be inside .wrap -->
        <div id="thumbsupsurvey-settings-wrapper" class="wrap">
            <!-- Print the page title -->
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <!-- Here are our tabs -->
            <nav class="nav-tab-wrapper">
                <a href="#" class="tus-nav-tab nav-tab nav-tab-active" rel="general">General settings</a>
                <a href="#" class="tus-nav-tab nav-tab" rel="builder">Builder</a>
                <a href="#" class="tus-nav-tab nav-tab" rel="styling">Styling</a>
                <a href="#" class="tus-nav-tab nav-tab" rel="scripting">Scripting</a>
                <a href="#" class="tus-nav-tab nav-tab" rel="information">Information</a>
            </nav>

            <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" novalidate="novalidate">

                <div class="tus-tab-content tab-content general">
                    <div class="tus-block">
                        <table class="form-table email-settings" role="presentation">
                            <tr>
                                <th scope="row"><label for="salesforce_on">Add Salesforce&copy; client</label></th>
                                <td>
                                    <p>
                                        <label><input name="salesforce_on" type="radio" value="0" <?php if ($settings["salesforce_on"] == "0") : ?>checked="checked" <?php endif; ?>> No</label><br>
                                        <label><input name="salesforce_on" type="radio" value="1" <?php if ($settings["salesforce_on"] == "1") : ?>checked="checked" <?php endif; ?>> Yes</label>
                                    </p>
                                    <p class="description">In case you have Salesforce&copy; embedded chat and you want to add a survey on it. <a href="https://wordpress.org/documentation/article/wordpress-feeds/">Read more</a>.</p>
                                </td>
                            </tr>
                            <tr id="salesforce-survey-id-wrapper" style="<?php if ($settings["salesforce_on"] == "0") : ?>display:none<?php endif; ?>">
                                <th scope="row"><label for="salesforce_survey_id">Salesforce&copy; Survey ID</label></th>
                                <td>
                                    <p>
                                        <input name="salesforce_survey_id" type="text" value="<?php echo esc_html($settings["salesforce_survey_id"]); ?>"><br>
                                    </p>
                                    <p class="description">Add the Survey ID for the Salesforce&copy; embedded chat. </p>
                                </td>
                            </tr>
                        </table>

                        <p class="submit"><input type="button" name="validate_btn" id="validate_btn" class="button button-primary" value="Save Changes"></p>

                    </div>
                </div>

                <div class="tus-tab-content tab-content builder">
                    <div class="tus-block">
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row"><label>Survey ID</label></th>
                                <td>
                                    <p>
                                        <input id="builder_survey_id" type="text" placeholder="Survey ID"><br>
                                    </p>
                                    <?php $parse = parse_url($_SERVER['SERVER_NAME']); ?>
                                    <p class="description">Give the Survey ID for the shortcode. Don't you have one? <a href="https://thumbsupsurvey.com?utm_source=<?php echo esc_url($parse['host']) ;?>&utm_campaign=plugin" target="_blank">Sign up now!</a></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Type</label></th>
                                <td>
                                    <p><strong>What is the type of the survey?</strong></p>
                                    <div class="mt-4 vertical-flex">
                                        <label for="builder_type_web"><input type="radio" name="builder_type" id="builder_type_web" checked value="web"> Web (chat style)</label>
                                        <label for="builder_type_form"><input type="radio" name="builder_type" id="builder_type_form" value="form"> Form</label>
                                    </div>

                                    <p class="description builder_type_web_more_info">The chat style version, which is like you're talking with someone, progressing step by step.</p>
                                    <p class="description builder_type_form_more_info">Full page form, submitting all information at once.</p>

                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Display</label></th>
                                <td>
                                    <p><strong>How is the survey going to open?</strong></p>
                                    <div class="mt-4 vertical-flex">
                                        <label for="builder_display_auto"><input type="radio" name="builder_display" id="builder_display_auto" checked value="auto"> Automatically</label>
                                        <label for="builder_display_method"><input type="radio" name="builder_display" id="builder_display_method" value="method"> Through a method call</label>
                                        <label for="builder_display_event"><input type="radio" name="builder_display" id="builder_display_event" value="event"> Through a click event <span class="builder_display_event_more_info">using the following selector (ID or class name):</span></label>
                                        <input id="builder_display_event_id_class" class="smaller builder_display_event_more_info" type="text" placeholder="ID or class name">
                                    </div>

                                    <p class="description builder_display_auto_more_info">The default option, it will show the survey automatically; you don't have to do anything else.</p>
                                    <p class="description builder_display_method_more_info">Survey opens programmatically when calling the method mentioned below.</p>
                                    <p id="builder_display_event_more_info_details" class="description builder_display_event_more_info">You will need to add (waiting for more info) on the element which will be used to trigger the survey.</p>

                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Delays</label></th>
                                <td>
                                    <p><strong>Do you need some opening delay?</strong></p>
                                    <div class="mt-4 vertical-flex">
                                        <label for="builder_delay_no"><input type="radio" name="builder_delay" id="builder_delay_no" value="no" checked> No</label>
                                        <label for="builder_delay_yes"><input type="radio" name="builder_delay" id="builder_delay_yes" value="yes"> Yes<span class="builder_delay_yes_more_info">, in time in milliseconds:</span></label>
                                        <div class="builder_delay_yes_more_info">
                                            <div class="horizontal-flex">
                                                <input id="builder_delay_milliseconds" class="smaller" type="text" placeholder="Time in milliseconds">
                                                <button class="builder_delay_add_milliseconds button" rel="100">0.1 sec</button>
                                                <button class="builder_delay_add_milliseconds button" rel="200">0.2 sec</button>
                                                <button class="builder_delay_add_milliseconds button" rel="500">0.5 sec</button>
                                                <button class="builder_delay_add_milliseconds button" rel="1000">1 sec</button>
                                                <button class="builder_delay_add_milliseconds button" rel="2000">2 sec</button>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="description builder_delay_no_more_info">Default option, survey is loaded instantly when triggered.</p>
                                    <p id="builder_delay_more_info_details" class="description builder_delay_yes_more_info"></p>

                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Shortcode</label></th>
                                <td>
                                    <input id="builder_survey_shortcode" type="text" readonly><br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                    <div class="tus-tab-content tab-content styling">
                    <div class="tus-block">
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row">Style snippets</th>
                                <td>
                                    <p>Snippets you can add in your <strong>.thumbsupsurvey--app</strong> wrapper for quick adjustments:</p>
                                    <p><strong>Fully transparent survey and blocks:</strong></p>
                                    <div class="style-with-button">
                                        <textarea class="css-scripting">.thumbsupsurvey--app {
  --thumbsupsurvey--sitebackground: transparent;
  --thumbsupsurvey--appbackground: transparent;
  --thumbsupsurvey--block-background: transparent;
}</textarea>
                                    </div>
                                    <p><strong>Use web site's fonts:</strong></p>
                                    <div class="style-with-button">
                                        <textarea class="css-scripting">.thumbsupsurvey--app {
  --thumbsupsurvey--font: inherit;
  --thumbsupsurvey--fontsize: 22px; // In case you want to change the font size
}</textarea>
                                    </div>
                                    <p><strong>Change the default height and width (for Web view only):</strong></p>
                                    <div class="style-with-button">
                                        <textarea class="css-scripting">.thumbsupsurvey--app {
  --thumbsupsurvey--max-width: 640px; // Replace with new width
  --thumbsupsurvey--max-height: 480px; // Replace with new height
}</textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">More info</th>
                                <td>
                                    <p>To add the scripts in your website, we strongly suggest using plugins like <a href="https://www.silkypress.com/simple-custom-css-js-pro/ref/nexusmedia/" target="_blank">Simple Custom CSS and JS</a> by <a href="https://www.silkypress.com/ref/nexusmedia/" target="_blank">SilkyPress.com</a></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="tus-tab-content tab-content scripting">
                    <div class="tus-block">
                    <table class="form-table email-settings" role="presentation">
                        <tr>
                            <th scope="row">Script snippets</th>
                                <td>
                                    <p>ThumbsUpSurvey gives you the option to send custom information with the survey by using a global variable called <strong>tusExtraInformation</strong>.</p>
                                    <p>The information can be something like a simple <strong>string</strong>, like this:</p>
                                    <div class="script-with-button">
                                        <textarea class="js-scripting">window.tusExtraInformation = 'TUS Survey';</textarea>
                                    </div>
                                    <p>Or an <strong>object</strong>:</p>
                                    <div class="script-with-button">
                                        <textarea class="js-scripting">window.tusExtraInformation = {
    'name': 'TUS',
    'project': 'Survey'
};</textarea>
                                    </div>
                                    <p>Or even a <strong>function</strong>, accepting survey ID as parameter, returning an object :</p>
                                    <div class="script-with-button">
                                        <textarea class="js-scripting">window.tusExtraInformation = function(surveyID) {
    return {
        'name': 'TUS',
        'project': 'Survey'
    }
};</textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">More info</th>
                                <td>
                                    <p>To add the scripts in your website, we strongly suggest using plugins like <a href="https://www.silkypress.com/simple-custom-css-js-pro/ref/nexusmedia/" target="_blank">Simple Custom CSS and JS</a> by <a href="https://www.silkypress.com/ref/nexusmedia/" target="_blank">SilkyPress.com</a></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="tus-tab-content tab-content information">
                    <div class="tus-block">
                        <p>Experience our product first-hand in its exciting pre-production phase by joining our pre-launch team. Your participation and feedback will be instrumental in shaping the final version of the system. As a token of our gratitude, we're offering a <strong>50% discount</strong> for your first year:</p>
                        <ul>
                            <li><i class="bi bi-check"></i> Salesforce embedded chat</li>
                            <li><i class="bi bi-check"></i> Form campaigns</li>
                            <li><i class="bi bi-check"></i> Email/web campaigns</li>
                            <li><i class="bi bi-check"></i> Unlimited surveys</li>
                            <li><i class="bi bi-check"></i> Up to 120,000 replies per year</li>
                            <li><i class="bi bi-check"></i> Detailed reports and statistics</li>
                        </ul>
                        <p><strong>Upgrade and get full access to to the Business package with 50% discount for the first year. You'll get everything other ThumbsUpSurvey subscribers get in the future, but we'll work with you on a custom installation, and follow up with you for feedback on the product.</strong></p>
                        <p><a href="https://thumbsupsurvey.com#contact" target="_blank" class="button button-tus">Contact us</a></p>
                    </div>
                </div>

                <div style="display:none">
                    <?php
                    wp_nonce_field('thumbsupsurvey-settings-save', 'thumbsupsurvey-nonce');
                    submit_button();
                    ?>
                </div>

            </form>

            <script>
                var ajaxurl = '<?php echo esc_html( admin_url('admin-ajax.php') ); ?>';
            </script>
        </div>
<?php

    }

    public function tus_settings_save_func()
    {
        // First, validate the nonce and verify the user as permission to save.
        if ( isset( $_POST['thumbsupsurvey-nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['thumbsupsurvey-nonce'] )), 'thumbsupsurvey-settings-save' ) ) {

            $settings = $this->get_settings();

            $settings["styling"] = _sanitize_text_fields(htmlentities($_POST["styling"]), true);
            $settings["scripting"] = _sanitize_text_fields(htmlentities($_POST["scripting"]), true);
            
            $settings["salesforce_on"] = sanitize_text_field(trim($_POST["salesforce_on"]));
            if ($settings["salesforce_on"] !== "1") {
                $settings["salesforce_on"] = "0";
                $settings["salesforce_survey_id"] = "";
            }
            $settings["salesforce_survey_id"] = sanitize_text_field(trim($_POST["salesforce_survey_id"]));

            $this->set_settings($settings);

            // To make the Coding Standards happy, we have to initialize this.
            if (!isset($_POST['_wp_http_referer'])) { // Input var okay.
                $_POST['_wp_http_referer'] = wp_login_url();
            }
        
            // Sanitize the value of the $_POST collection for the Coding Standards.
            $url = sanitize_text_field(
                wp_unslash($_POST['_wp_http_referer']) // Input var okay.
            );
        
            // Finally, redirect back to the admin page.
            wp_safe_redirect(urldecode($url));
            exit;
        }
    }

}
$ThumbsUpSurvey_Settings = new ThumbsUpSurvey_SettingsClass();
