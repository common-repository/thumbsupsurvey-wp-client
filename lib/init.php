<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ThumbsUpSurvey_InitClass
{
    public function __construct()
    {
        add_filter( 'plugin_action_links_' . plugin_basename( THUMBSUPSURVEY_WP_PLUGIN_FILE ), [ $this, 'add_plugin_action_link' ], 10, 1 );
    }

	public function add_plugin_action_link( $links ) {

		$custom['thumbsupsurvey-pro'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer" 
				style="color: #00a32a; font-weight: 700;" 
				onmouseover="this.style.color=\'#008a20\';" 
				onmouseout="this.style.color=\'#00a32a\';"
				>%3$s</a>',
			esc_url( 'https://thumbsupsurvey.com' ),
			esc_attr__( 'Get Thumbs Up Survey license', 'thumbsupsurvey' ),
			esc_html__( 'Get Thumbs Up Survey license', 'thumbsupsurvey' )
		);

		$custom['thumbsupsurvey-settings'] = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url( '/wp-admin/options-general.php?page=thumbs-up-survey-settings' ),
			esc_attr__( 'Go to Thumbs Up Survey Settings page', 'thumbsupsurvey' ),
			esc_html__( 'Settings', 'thumbsupsurvey' )
		);

		$custom['thumbsupsurvey-docs'] = sprintf(
			'<a href="%1$s" target="_blank" aria-label="%2$s" rel="noopener noreferrer">%3$s</a>',
			// phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
			esc_url( 'https://thumbsupsurvey.com/category/how-to/' ),
			esc_attr__( 'Go to Thumbs Up Survey documentation page', 'thumbsupsurvey' ),
			esc_html__( 'Docs', 'thumbsupsurvey' )
		);

		return array_merge( $custom, (array) $links );
	}

}
new ThumbsUpSurvey_InitClass();
