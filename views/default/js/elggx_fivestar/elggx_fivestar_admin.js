define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");

	function addFormField() {
		var id = document.getElementById("id").value;
		$("#divTxt").append("<p id='row" + id + "'>" + elgg.echo('elggx_fivestar:settings:add_view') + ":<input class='input-text mls' type='text' name='elggx_fivestar_views[]' id='txt" + id + "'><a class='fivestar-admin-remove-form-field elgg-button elgg-button-action mls' href='#' data-row='" + id + "'>" + elgg.echo('elggx_fivestar:settings:remove_view') + "</a><p>");

		$('#row' + id).fadeIn("slow");

		id = (id - 1) + 2;
		document.getElementById("id").value = id;
	}

	function removeFormField(id) {
		$(id).remove();
	}

	$.fn.collapse = function() {
		return this.each(function() {
			$(this).find("legend").addClass('fivestar-collapsible').click(function() {
				if ($(this).parent().hasClass('fivestar-collapsed')) {
					$(this).parent().removeClass('fivestar-collapsed').addClass('fivestar-collapsible');
				}

				$(this).removeClass('fivestar-collapsed');

				$(this).parent().children().filter("p,img,table,ul,div,span,h1,h2,h3,h4,h5").toggle("slow", function() {
					if ($(this).is(":visible")) {
						$(this).parent().find("legend").addClass('fivestar-collapsible');
					} else {
						$(this).parent().addClass('fivestar-collapsed').find("legend").addClass('fivestar-collapsed');
					}
				});
			});
		});
	};

	$(document).ready(function() {
		$("fieldset.fivestar-collapsible").collapse();
	});

	$(document).on('click', '.fivestar-admin-remove-form-field', function() {
		var row = $(this).data('row');
		removeFormField("#row" + row);
		return false;
	});

	$(document).on('click', '.fivestar-admin-add-form-field', function() {
		addFormField();
		return false;
	});
});