<!DOCTYPE html>
<html>
<head>
<title>Recurring Event</title>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<!-- Bootstrap core CSS -->
<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
<link rel='stylesheet' href='bootstrap/css/bootstrap-glyphicons.css'>
<!-- ptTimeSelect CSS -->
<link rel='stylesheet' type='text/css'
	href='assets/css/jquery.ptTimeSelect.css' />
<!-- Custom CSS styles-->
<link type='image/png' rel='shortcut icon'
	href='assets/css/JOZAM_Logo.png' />
<link rel='stylesheet' href='popup/jquery-ui.css'>
</head>
<body>
	<!-- Recurring Event popup -->
	<table align=center>
		<tr>
			<td><br> <label for='name'>Name : &nbsp;</label> <input value=''
				name='name' id='name' class='text ui-widget-content ui-corner-all'>
				<br></br> <label> Time Slot : </label> <br> <label
				for='timeSlot_from'> from </label>
				<div id='timeSlot_from'>
					<table>
						<tr>
							<td><input readonly='readonly'
								value='<?php echo date('m/d/Y'); ?>' type='text'
								name='startDate_date' id='startDate_date'
								class='date text ui-widget-content ui-corner-all'></td>
							<td>&nbsp;<input readonly='readonly' value='12:00 AM'
								name='startDate_time' id='startDate_time'
								class='time text ui-widget-content ui-corner-all'>
							</td>
						</tr>
					</table>
				</div> <label for='timeSlot_to'> to </label>
				<div id='timeSlot_to'>
					<table>
						<tr>
							<td><input readonly='readonly' type='text'
								value='<?php echo date('m/d/Y',strtotime('+1 Week')); ?>'
								name='endDate_date' id='endDate_date'
								class='date text ui-widget-content ui-corner-all'></td>
							<td>&nbsp;<input readonly='readonly' value='12:00 AM' type='text'
								name='endDate_time' id='endDate_time'
								class='time text ui-widget-content ui-corner-all'>
							</td>
						</tr>
					</table>
				</div> <br> <label for='startTime'>Start Time : </label> <input
				readonly='readonly' value='12:00 AM' type='text' name='startTime'
				id='startTime' class='time text ui-widget-content ui-corner-all'> <br></br>
				<label for='endTime'>End Time : </label> <input readonly='readonly'
				value='12:00 AM' type='text' name='endTime' id='endTime'
				class='time text ui-widget-content ui-corner-all'> <br> <br> <label
				class='checkbox-inline'> <input readonly='readonly' class='day'
					type='checkbox' id='Monday' name='Monday' value='Monday'> Monday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
					class='day' type='checkbox' id='Thursday' name='Thursday'
					value='Thursday'> Thursday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
					class='day' type='checkbox' id='Wednesday' name='Wednesday'
					value='Wednesday'> Wednesday
			</label> <br> <label class='checkbox-inline'> <input
					readonly='readonly' class='day' type='checkbox' id='Tuesday'
					name='Tuesday' value='Tuesday'> Tuesday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
					class='day' type='checkbox' id='Friday' name='Friday'
					value='Friday'> Friday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
					class='day' type='checkbox' id='Saturday' name='Saturday'
					value='Saturday'> Saturday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
					class='day' type='checkbox' id='Sunday' name='Sunday'
					value='Sunday'> Sunday
			</label></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<button id='cancel'>
					<span class='glyphicon glyphicon-remove' aria-hidden='true'></span>
				</button>
				<button id='validate'>
					<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				</button>
			</td>
		</tr>
	</table>


	<!-- end popup -->

	<!-- JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src='bootstrap/js/jquery.min.js'></script>
	<script src='popup/jquery-ui.js'></script>
	<script src='bootstrap/js/timepicki.js'></script>
	<script src='assets/jquery.ptTimeSelect.js'></script>
	<script src='bootstrap/js/bootstrap.min.js'></script>
	<script>
		if (window.opener.edit) {
			recurringEvent_id = window.opener.selected_value;
			$.ajax({ 	 	
                   type : 'POST', 	 	
                   url : 'Calendar.php', 	 	
                   data : { action : 'GetRecurringEventAttributes' , recurringEvent_id : recurringEvent_id }, 	 	
                   success : function(data) { 	 	
                       	var attributes = data.split('|');
                       
                       	$("#name").val(attributes[0]);

                       	var days = attributes[1].split(', ');
                       	$('.day').each(function() {
                           var day = $(this);
                           day[0].checked = ($.inArray(day.val(), days) != -1);
                       	});

                   	 	$("#startTime").val(attributes[2]);
                   		$("#endTime").val(attributes[3]);
                   		$("#startDate_date").val(attributes[4]);
                   		$("#startDate_time").val(attributes[5]);
                   		$("#endDate_date").val(attributes[6]);
                   		$("#endDate_time").val(attributes[7]);
                   }
             });
		} else {
			$('#name').val('New Recurring Event');
			$('#Saturday')[0].checked = true;
			$('#Sunday')[0].checked = true;
		}
	
		$('.time').ptTimeSelect();
		
		$('.date').datepicker();
		$( '#startDate_date' ).datepicker( 'setDate', '<?php echo date('m/d/Y'); ?>' );
		$( '#endDate_date' ).datepicker( 'setDate', '<?php echo date('m/d/Y',strtotime('+1 Year')); ?>' );

		$('#cancel').on('click', function() {
			window.close();
		});

		$('#validate').on('click', function() {
			var edit = window.opener.edit;
			var id = edit ? window.opener.selected_value : 'id is name';
			var days = [];
			$('.day').each(function() {
        		if ($(this).is(':checked')) {
            		days.push("'" + $(this).val() + "'");
        		}
			});
			$.ajax({
				type : 'POST',
   	            url : 'Calendar.php',
   	            data : {action: 'SetRecurringEvent',
   	            		id : id,
   	   	            	name : $('#name').val(), 
   	   	            	startTime : $('#startTime').val(), 
   	   	            	endTime : $('#endTime').val(), 
   	   	            	days : days,
   	   	            	timeSlot_startDate : $('#startDate_date').val(), 
   	   	          		timeSlot_startTime : $('#startDate_time').val(), 
   	   	          		timeSlot_endDate :$('#endDate_date').val(), 
   	   	    			timeSlot_endTime : $('#endDate_time').val(),
   	   	    			edit : edit}, 	 	
   	            success : function() { 	 	
   	            	window.opener.location.reload();
   	            	window.close();
   	            }
   	        });
		});
	</script>
</body>
</html>