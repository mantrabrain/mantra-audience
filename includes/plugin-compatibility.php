<?php
/**
 * Houses codes that provide compatibility with other plugins
 */

class Mantra_Audience_Plugin_Compatibility {

	function __construct() {
		add_action( 'after_setup_theme', array( $this, 'mantra_audience_builder_compat' ), 20 );
		add_filter( 'mantra_audience_builder_layout_providers', array( $this, 'add_sample_popup_layouts' ) );
	}

	/**
	 * Disable Builder frontend editor for the popup posts loaded on frontend
	 *
	 * @since 1.0.0
	 */
	function mantra_audience_builder_compat() {
		global $mantraAudienceBuilder;

		if( isset( $mantraAudienceBuilder ) ) {
			add_action( 'mantra_audience_before_render', array( $this, 'mantra_audience_before_render' ) );
			add_action( 'mantra_audience_after_render', array( $this, 'mantra_audience_after_render' ) );
		}
	}

	/**
	 * Actions to perform before rendering the popup
	 *
	 * @since 1.0.5
	 */
	function mantra_audience_before_render() {
		global $mantraAudienceBuilder;

		/* disable Builder editor for popups */
		$GLOBALS['mantraAudienceBuilder']->in_the_loop = true;

		/* Fix bug in WC Shop page, see #6334 */
		$GLOBALS['mantraAudienceBuilder']->skip_display_check = true;

		/* disable the static stylesheet generation in Builder, forces Builder to make inline <style> tag for everything */
		add_filter( 'mantra_audience_builder_enqueue_stylesheet', array( $this, 'disable_static_stylesheet' ) );
		add_action( 'mantra_audience_builder_before_template_content_render', array( $mantraAudienceBuilder->stylesheet, 'enqueue_stylesheet' ), 10 );

		/* disable Row Width options: rows inside the popup cannot be displayed as fullwidth */
		add_filter( 'mantra_audience_builder_row_classes', array( $this, 'mantra_audience_builder_row_classes' ), 10, 3 );
	}

	/**
	 * Revert the changes made in self::mantra_audience_before_render()
	 *
	 * @since 1.0.5
	 */
	function mantra_audience_after_render() {
		remove_filter( 'mantra_audience_builder_enqueue_stylesheet', array( $this, 'disable_static_stylesheet' ) );
		$GLOBALS['mantraAudienceBuilder']->in_the_loop = false;
		$GLOBALS['mantraAudienceBuilder']->skip_display_check = false;
		remove_filter( 'mantra_audience_builder_row_classes', array( $this, 'mantra_audience_builder_row_classes' ), 10, 3 );
	}

	function disable_static_stylesheet( $enable ) {
		return false;
	}

	function mantra_audience_builder_row_classes( $row_classes, $row, $builder_id ) {
		$row_classes = str_replace( array( 'fullwidth_row_container', 'fullwidth' ), '', $row_classes );

		return $row_classes;
	}

	/**
	 * Add sample layouts bundled with Popup plugin to mantraAudience Builder
	 *
	 * @since 1.0.0
	 */
	function add_sample_popup_layouts( $providers ) {
		include MANTRA_AUDIENCE_DIR . 'includes/mantra-audience-builder-popup-layout-provider.php';
		$providers[] = 'Mantra_Audience_Builder_Layouts_Provider_Mantra_Audience';
		return $providers;
	}
}
new Mantra_Audience_Plugin_Compatibility;