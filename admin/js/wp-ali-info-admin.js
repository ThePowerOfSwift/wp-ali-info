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
			$(this).html('<span class="dashicons dashicons-download"></span>Fetching...');
			$(".wp-ali-info-table td").parent().remove();
			$( ".wp-ali-info-table" ).before('<span class="searching">Fetching...</span>');
			var product = $.trim($('#post_product').val());
			var request = 'action=product_search&product='+product;

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '/wpdev/wp-admin/admin-ajax.php',
				data: request,
				success:function(data) {
					if(data && data == "") {
						// handle no results.
					}
					else
					printProduct(data);
					$("#wp-ali-info-search-button").hide();
					$("#wp-ali-info-delete-button").show();
					$(".wp-ali-info-search-field").prop('disabled', true);

				  },
				error: function (xhr, ajaxOptions, thrownError) {
					refreshSearchButton();
				}
			});
		});

		function refreshSearchButton(){
			$(".searching").remove();
			$("#wp-ali-info-search-button").removeClass( "disabled" );
			$("#wp-ali-info-search-button").html('<span class="dashicons dashicons-download"></span>Fetch');
		}

		function printProduct(data){
			$("input[name='product-id']" ).val(data['productId']);
			$("input[name='product-name']" ).val(data['productTitle']);
			$("input[name='product-price']" ).val(data['localPrice']);
			$("input[name='product-discount']" ).val(data['discount']);
			$("input[name='product-volume']" ).val(data['volume']);
			$("input[name='product-affiliate-link']" ).val(data['affiliate_link']);
			$("input[name='product-product-link']" ).val(data['productUrl']);
			$("input[name='product-short-affiliate-link']" ).val(data['shortUrl']);
		}


		$('#price-history-range').on('change', updateChart);

		function updateChart(){
			var newChart = $("#price-history-range").val();

			console.log('dropdown test');
			if(chart){chart.destroy();}
			var params = dataMap[newChart]
			chart = new Chart(ctx, params);
		}

		$('#wp-ali-info-delete-button').click(function () {
			if(confirm("Are you sure you want to delete all data?")){
				$('#wp-ali-info-product-data input[type="text"]').val('');
				$('#wp-ali-info-product-data textarea').val('');
				refreshSearchButton();
				$("#wp-ali-info-search-button").show();
				$("#wp-ali-info-delete-button").hide();
				$(".wp-ali-info-search-field").prop('disabled', false);
			}
		});

	});




})( jQuery );



var d = [];


  function getPreviousMonths(numberOfmonths) {
	  var months = [];

	  for (i = 0; i < numberOfmonths; i++) {
		  var month = moment().subtract(i, 'months').format('MMMM Y');
		  months.push(month);
	  }
	  return months.reverse();
  }

  function getPreviousDays(numberOfDays) {
	var days = [];

	for (i = 0; i < numberOfDays; i++) {
		var day = moment().subtract(i, 'day').format("LL");
		days.push(day);
	}
	return days.reverse();
  }

  function getPreviousWeeks(numberOfWeeks) {
	var weeks = [];

	for (i = 0; i < numberOfWeeks; i++) {
		var week = moment().subtract(i, 'week').format('LLLL');
		weeks.push(week);
	}
	return weeks.reverse();
  }

  var dateFormat = 'DD\/MM\/YYYY';
  var data = [];
  for (var i in d) {
		date = moment(d[i].date, dateFormat);
	  data.push({
		t: date.valueOf(),
		y: d[i].price
	  });
  }
  console.log(data)

  var ctx = document.getElementById("priceChart").getContext("2d");
  console.log(getPreviousDays(30));

  var dataMap = {
	'30-days': {
		type: 'bar',
		data: {
			labels: getPreviousDays(30),
			datasets: [{
				label: "Price trend",
				data: data,
				type: 'line',
				pointRadius: 0,
				fill: false,
				borderColor: 'red',
				lineTension: 0,
				borderWidth: 2
			}]
		},
		options: {
			scales: {
				xAxes: [{
				type: 'time',
				distribution: 'linear',
				ticks: {
					source: 'labels'
				},
				time: {
				unit: 'day',
				unitStepSize: 1
				}
				}],
				yAxes: [{
				scaleLabel: {
					display: true,
					labelString: 'price'
				}
				}]
			}
		}
	},
	'3-months': {
		type: 'bar',
		data: {
			labels: getPreviousWeeks(12),
			datasets: [{
				label: "Price trend",
				data: data,
				type: 'line',
				pointRadius: 0,
				fill: false,
				borderColor: 'red',
				lineTension: 0,
				borderWidth: 2
			}]
		},
		options: {
			scales: {
				xAxes: [{
				type: 'time',
				distribution: 'linear',
				ticks: {
					source: 'labels'
				},
				time: {
				unit: 'month',
				unitStepSize: 1,
				displayFormats: {
					'month': 'MMM'
					}
				}
				}],
				yAxes: [{
				scaleLabel: {
					display: true,
					labelString: 'price'
				}
				}]
			}
		}
	},
	'12-months': {
		type: 'bar',
		data: {
			labels: getPreviousMonths(12),
			datasets: [{
				label: "Price trend",
				data: data,
				type: 'line',
				pointRadius: 0,
				fill: false,
				borderColor: 'red',
				lineTension: 0,
				borderWidth: 2
			}]
		},
		options: {
			scales: {
				xAxes: [{
				type: 'time',
				distribution: 'linear',
				ticks: {
					source: 'labels'
				},
				time: {
				unit: 'month',
				unitStepSize: 1,
				displayFormats: {
					'month': 'MMM'
					}
				}
				}],
				yAxes: [{
				scaleLabel: {
					display: true,
					labelString: 'price'
				}
				}]
			}
		}
	}

};



  var chart = new Chart(ctx, dataMap['30-days']);