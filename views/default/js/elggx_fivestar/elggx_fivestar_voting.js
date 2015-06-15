define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
	require("elggx_fivestar/ui.stars");

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
				var url = elgg.security.addToken(elgg.get_site_url() + "action/elggx_fivestar/rate");
				$.post(url, {id: guid, vote: value}, function(db) {
					// Select stars from "Average rating" control to match the returned average rating value
					$("#fivestar-form-" + guid + "-" + unique_id).stars("select", Math.round(db.rating));

					// Update other text controls...
					$("#fivestar-votes-" + guid + "-" + unique_id).text(db.votes);
					$("#fivestar-rating-" + guid + "-" + unique_id).text(db.rating);

					// Display confirmation message to the user
					if (db.msg) {
						$("#fivestar-messages-" + guid + "-" + unique_id).text(db.msg).stop().css("opacity", 1).fadeIn(30);
					} else {
						$("#fivestar-messages-" + guid + "-" + unique_id).text(elgg.echo('elggx_fivestar:rating_saved')).stop().css("opacity", 1).fadeIn(30);
					}

					// Hide confirmation message and enable stars for "Rate this" control, after 2 sec...
					setTimeout(function() {
						$("#fivestar-messages-" + guid + "-" + unique_id).fadeOut(1000, function(){ui.enable()})
					}, 2000);
				}, "json");
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
