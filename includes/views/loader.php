<?php
/**
 * Adds script files to load assets required by the Popup plugin
 *
 * @package Mantra Audience
 */
?>
<script>
jQuery(function($){
	if( $( '.mantra-audience' ).length ) {
		var path = '<?php echo MANTRA_AUDIENCE_URI; ?>assets/';
		if( ! $( '#mantra-audience-builder-animate-css' ).length ) {
			$( 'head' ).append( '<link rel="stylesheet" id="mantra-audience-builder-animate-css" href="' + path + '/css/animate.min.css" />' );
		}
		if( ! $( '#magnific-css' ).length ) {
			$( 'head' ).append( '<link rel="stylesheet" id="magnific-css" href="' + path + '/css/lightbox.css" />' );
		}
		$( 'head' ).append( '<link rel="stylesheet" id="mantra-audience" href="' + path + '/css/styles.css" />' );
		function load() {
			window.mantraAudiencePopup = { ajaxurl : '<?php echo admin_url( 'admin-ajax.php' ); ?>' };
			$.getScript( path + '/js/scripts.js' );
		}
		if( typeof $.fn.magnificPopup != 'function' ) {
			$.getScript( path + '/js/lightbox.min.js' ).done( load );
		} else {
			load();
		}
	}
});
</script>