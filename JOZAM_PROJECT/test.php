<?php
echo 'this is test<br>';
require_once ('Calendar.php');
?>
<html>
<head></head>
<body>
</body>

<button class='refresh_button' name='refresh_button' id='refresh_button'>
	<span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
</button>
<script>
$('#refresh_button').on('click', function() {
	$.ajax({
		type : 'POST', 	 	
   	    url : 'Calendar.php', 	 	
   	    data : { action: 'GetRefreshRate' }, 	 	
   	    success : function() {
   	   	    window.location.reload();
   	   	}
   	});
});
</script>
</html>