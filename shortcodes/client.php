<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ThumbsUpSurvey_ShortcodeClientClass
{
    public function __construct()
    {
        add_shortcode('thumbsupsurvey-web', array($this, 'tus_web_func'));
        add_shortcode('thumbsupsurvey-form', array($this, 'tus_form_func'));
        add_shortcode('thumbsupsurvey-salesforce', array($this, 'tus_salesforce_func'));

        // This is for the footer, adding automatically embedded chat scripts.
        add_action('wp_footer', array($this, 'tus_footer_func'));
    }

    public function tus_web_func($atts = [], $content = null, $tag = '') {
        return $this->tus_the_func("web", $atts, $content, $tag);
    }
    public function tus_form_func($atts = [], $content = null, $tag = '') {
        return $this->tus_the_func("form", $atts, $content, $tag);
    }
    public function tus_salesforce_func($atts = [], $content = null, $tag = '') {
        return $this->tus_the_func("salesforce", $atts, $content, $tag);
    }

    public function tus_the_func($client = "", $atts = [], $content = null, $tag = '')
    {
        global $ThumbsUpSurvey_Functions, $ThumbsUpSurvey_Settings;

        $settings = $ThumbsUpSurvey_Settings->get_settings();

        $atts = array_change_key_case((array) $atts, CASE_LOWER);
        $calc_atts = shortcode_atts(
            array(
                'license' => '',
                'wait-for-method' => '0',
                'wait-for-time' => '',
                'wait-for-click' => '',
            ),
            $atts,
            $tag
        );

        $license = strtolower(trim($calc_atts['license']));
        $client = strtolower(trim($client));

        if (!in_array($client, $ThumbsUpSurvey_Functions->get_available_clients()) || $license == '') {
            ob_start();
            ?>
            <!-- Thumbs Up Survey: Please check your license. -->
            <script>
                console.error("Thumbs Up Survey - Please check your shortcode parameters");
            </script>
            <?php
        } else {
            if ($client !== 'salesforce') {
                wp_enqueue_script( 'thumbsupsurvey-' . esc_html($client), THUMBSUPSURVEY_SITE . '/clients/' . esc_html($client) . THUMBSUPSURVEY_WP_PLUGIN_DEV . '.js', array(), THUMBSUPSURVEY_WP_PLUGIN_VER );

                ob_start();
                ?>
                <div id="tus-<?php echo esc_html($license); ?>" class="thumbsupsurvey--app <?php echo esc_html($client); ?>" 
                    <?php if ($calc_atts["wait-for-method"] == '1'): ?>
                        wait-for-method
                    <?php elseif ($calc_atts["wait-for-click"] !== ''): ?>
                        wait-for-click="<?php echo $ThumbsUpSurvey_Functions->escape_css_selector( $calc_atts["wait-for-click"] )?>"
                    <?php endif; ?>

                    <?php if ($calc_atts["wait-for-time"] !== ''  && intval($calc_atts["wait-for-time"]) > 0): ?>
                        wait-for-time="<?php echo intval($calc_atts["wait-for-time"]); ?>"
                    <?php endif; ?>
                ></div>
                <?php
            } else {
                echo esc_html( '<script type="text/javascript" src="' . THUMBSUPSURVEY_SITE . '/clients/salesforce.js?ver=' . THUMBSUPSURVEY_WP_PLUGIN_VER . '" id="thumbsupsurvey-salesforce" data-id="' . $license . '"></script>' );
            }
        }

        $output = ob_get_clean();
        return $output;
    }

    public function tus_footer_func() {
        global $ThumbsUpSurvey_Settings;

        $settings = $ThumbsUpSurvey_Settings->get_settings();

        if ($settings["salesforce_enabled"]) {
            echo do_shortcode( '[thumbsupsurvey-salesforce license="' . esc_html($settings["salesforce_survey_id"]) . '"]' );
        }
    }
}
new ThumbsUpSurvey_ShortcodeClientClass();