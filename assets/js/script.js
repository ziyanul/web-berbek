$(document).ready(function () {
	new DataTable('#datatables', {
		"paging": true,   // Mengaktifkan paging
        "searching": true, // Mengaktifkan pencarian
        "info": true 
	});

	$('.datepickers').datepicker();

	var window_width = $(window).width();

	// console.log(window_width)

	if (window_width <= 768) {
		console.log('ab')
		$('body').addClass('sidebar-toggled');
		$('body').find('.navbar-nav').addClass('toggled');
	}
})
