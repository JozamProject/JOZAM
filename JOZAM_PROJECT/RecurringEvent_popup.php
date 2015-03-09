<!DOCTYPE html>
<html>
<head>
<title>Recurring Event</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-glyphicons.css">
<!-- ptTimeSelect CSS -->
<link rel="stylesheet" type="text/css"
	href="assets/css/jquery.ptTimeSelect.css" />
<!-- Custom CSS styles-->
<link type="image/png" rel="shortcut icon"
	href="assets/css/JOZAM_Logo.png" />
<link rel="stylesheet" href="popup/jquery-ui.css">
</head>
<body>
	<!-- Recurring Event popup -->
	<div id='dialog-form-config' title='Configuration'
		style='height: 410px !important;'>
		<fieldset>
			<label for='name'>Name : &nbsp;</label><input
			placeholder='Name' name='name' id='name'
			class='text ui-widget-content ui-corner-all'><br> <br>
			<label for='startTime'>Start Time : </label> <input
				readonly='readonly' value='12:00 AM' type='text' name='startTime'
				id='startTime' class='time text ui-widget-content ui-corner-all'> <br></br>
			<label for='endTime'>End Time : </label> <input readonly='readonly'
				value='12:00 AM' type='text' name='endTime' id='endTime'
				class='time text ui-widget-content ui-corner-all'> <br> <br> <label
				class='checkbox-inline'> <input readonly='readonly' type='checkbox'
				id='Monday' name='Monday' value='Monday'> Monday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
				type='checkbox' id='Thursday' name='Thursday' value='Thursday'>
				Thursday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
				type='checkbox' id='Wednesday' name='Wednesday' value='Wednesday'>
				Wednesday
			</label> <br> <label class='checkbox-inline'> <input
				readonly='readonly' type='checkbox' id='Tuesday' name='Tuesday'
				value='Tuesday'> Tuesday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
				type='checkbox' id='Friday' name='Friday' value='Friday'> Friday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
				type='checkbox' id='Saturday' name='Saturday' value='Saturday'
				checked> Saturday
			</label> <label class='checkbox-inline'> <input readonly='readonly'
				type='checkbox' id='Sunday' name='Sunday' value='Sunday' checked>
				Sunday
			</label><br></br> <label for='timeSlot'> Time Slot : </label><br> <label
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
			</div>
			<label for='timeSlot_to'> to </label>
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
			</div>

			<input readonly='readonly' type='submit' tabindex='-1'
				style='position: absolute; top: -1000px'>
		</fieldset>
	</div>
	<!-- end popup -->

	<!-- JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="bootstrap/js/jquery.min.js"></script>
	<script src="popup/jquery-ui.js"></script>
	<script src="bootstrap/js/timepicki.js"></script>
	<script src="assets/jquery.ptTimeSelect.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script>
		$('.time').ptTimeSelect();
		
		$('.date').datepicker();
		$( '#startDate_date' ).datepicker( 'setDate', '<?php echo date('m/d/Y'); ?>' );
		$( '#endDate_date' ).datepicker( 'setDate', '<?php echo date('m/d/Y',strtotime('+1 Week')); ?>' );
	
		$('.add_to_recurringEvents_button').on(
				'click',
				function() {
					var options = $(
							'select.default_recurringEvents option:selected')
							.sort().clone();
					$('select.recurringEvents').append(options);
				});
		$('.add_recurringEvent_button').on('click', function() {
			$('select.recurringEvents').append('<option> value </option>');
		});

		$('.remove_recurringEvent_button').on('click', function() {
			$('select.recurringEvents option:selected').remove();
		});

		$('.edit_recurringEvent_button').on('click', function() {
			//
		});
	</script>
</body>
</html>