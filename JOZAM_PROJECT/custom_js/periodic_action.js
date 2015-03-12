function refresh_time_left() {
	$.ajax({
		type : 'POST',
		url : 'Calendar.php',
		data : {
			action : 'RefreshTimeLeft'
		},
		success : function() {
			window.location.reload();
		}
	});
}

function initialize_refresh_time_left() {
	$.ajax({
		type : "POST",
		url : "Calendar.php",
		data : {
			action : 'GetRefreshRate'
		},
		success : function(refresh_rate) {
			setInterval(refresh_time_left, refresh_rate);
		}
	});
}

initialize_refresh_time_left();