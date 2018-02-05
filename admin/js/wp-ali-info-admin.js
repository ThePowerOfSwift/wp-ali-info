(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function($) {

		$("#wp-ali-info-search-button").click(function (e) {
			e.preventDefault();
			$(this).addClass( "disabled" );
			$(this).html('<span class="dashicons dashicons-search"></span>Searching...');
			$(".wp-ali-info-table td").parent().remove();
			$( ".wp-ali-info-table" ).before('<span class="searching">Searching...</span>');
			var product = $.trim($('#post_product').val());
			var request = 'action=product_search&product='+product;

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/wpdev/wp-admin/admin-ajax.php',
				data: request,
				success:function(data) {
					if(data && data == "") {
						$( ".wp-ali-info-table" ).append('<tr><td>No Results.</td></tr>');
						console.log(data);
					}
					else
					console.log(data);
					$(".searching").remove();
					$( ".wp-ali-info-table" ).show();
					$("#wp-ali-info-search-button").removeClass( "disabled" );
					$("#wp-ali-info-search-button").html('<span class="dashicons dashicons-search"></span>Search');

				  },
				error: function (xhr, ajaxOptions, thrownError) {
					$(".searching").remove();
				}
			});
		});
	});


})( jQuery );
