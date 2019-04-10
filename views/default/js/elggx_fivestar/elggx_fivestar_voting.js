define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
	require("elggx_fivestar/ui.stars");
	
	var Ajax = require('elgg/Ajax');

	// manage Spinner manually
	var ajax = new Ajax(false);

	function fivestar(guid, unique_id) {
		$("#fivestar-form-" + guid + "-" + unique_id).find('input[type=submit]').hide();

		// Create stars for: Rate this
		$("#fivestar-form-" + guid + "-" + unique_id).stars( {
			cancelShow: 0,
			cancelValue: 0,
			captionEl: $("#caption"),
			callback: function(ui, type, value) {
				// Disable Stars while AJAX connection is active
				ui.disable();

				// Display message to the user at the begining of request
				$("#fivestar-messages-" + guid + "-" + unique_id).text(elgg.echo('saving')).stop().css("opacity", 1).fadeIn(30);
				ajax.action("elggx_fivestar/rate", {
					data: {
						id: guid,
						vote: value
					}
				}).done(function(json, status, jqXHR) {
					if (jqXHR.AjaxData.status == -1) {
						console.log(status);
						return;
					}
					if (json && (typeof json === 'string')) {
						// Display confirmation message to the user
						$("#fivestar-messages-" + guid + "-" + unique_id).text(json).stop().css("opacity", 1).fadeIn(30);
						
						// Hide confirmation message and enable stars for "Rate this" control, after 2 sec...
						setTimeout(function() {
							$("#fivestar-messages-" + guid + "-" + unique_id).fadeOut(1000, function(){ui.enable()})
						}, 2000);
					} else if (json) {
						// Select stars from "Average rating" control to match the returned average rating value
						$("#fivestar-form-" + guid + "-" + unique_id).stars("select", Math.round(json.rating));

						// Update other text controls...
						$("#fivestar-votes-" + guid + "-" + unique_id).text(json.votes);
						$("#fivestar-rating-" + guid + "-" + unique_id).text(json.rating);

						// Display confirmation message to the user
						if (json.msg) {
							$("#fivestar-messages-" + guid + "-" + unique_id).text(json.msg).stop().css("opacity", 1).fadeIn(30);
						} else {
							$("#fivestar-messages-" + guid + "-" + unique_id).text(elgg.echo('elggx_fivestar:rating_saved')).stop().css("opacity", 1).fadeIn(30);
						}

						// Hide confirmation message and enable stars for "Rate this" control, after 2 sec...
						setTimeout(function() {
							$("#fivestar-messages-" + guid + "-" + unique_id).fadeOut(1000, function(){ui.enable()})
						}, 2000);
					}
				});
			}
		});

		// Create element to use for confirmation messages
		$('<div class="fivestar-messages" id="fivestar-messages-' + guid + "-" + unique_id + '"/>').appendTo("#fivestar-form-" + guid + "-" + unique_id);
	};

	$('.fivestar-ratings').each(function() {
		var guid = $(this).data('guid');
		var unique_id = $(this).data('uniqueid');
		fivestar(guid, unique_id);
	});
});
