<!DOCTYPE html>
<html>
<head>
<title>Configuration</title>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<!-- Bootstrap core CSS -->
<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
<link rel='stylesheet' href='bootstrap/css/bootstrap-glyphicons.css'>
<!-- TimePicki CSS -->
<link rel='stylesheet' href='bootstrap/css/timepicki.css'>
<!-- Custom CSS styles-->
<link type='image/png' rel='shortcut icon'
	href='assets/css/JOZAM_Logo.png' />
<link rel='stylesheet' href='popup/jquery-ui.css'>
</head>
<body>
<?php
require_once ('Calendar.php');
?>
	<!-- Configuration popup -->
	<br>
	<table align=center>
		<tr>
			<td><label for='calendar_url'>Calendar URL : &nbsp;</label></td>
			<td><input placeholder='Calendar URL'
				value='<?php echo getCalendar_url();?>' name='calendar_url'
				id='calendar_url' class='text ui-widget-content ui-corner-all'></td>
			<td>&nbsp;
				<button id='refresh_calendar_url'>
					<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				</button>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td><label for='refresh_rate'>Refresh Rate : &nbsp;</label></td>
			<td><input readonly='readonly'
				value='<?php $refresh_rate = getRefresh_Rate(); echo $refresh_rate;?>'
				name='refresh_rate' id='refresh_rate'
				class='text ui-widget-content ui-corner-all'></td>


			<td>&nbsp;
				<button id='refresh_refresh_rate'>
					<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				</button>
			</td>


		</tr>
	</table>
	<br>
	<label for='recurringEvents'>Recurring Events :</label>
	<table>
		<tr>
			<td><select class='recurringEvents' name='recurringEvents'
				id='recurringEvents' size='8'>
				<?php
				foreach ( getRecurringEvents () as $re ) {
					echo "'<option value='" . $re ['id'] . "' selected>" . $re ['name'] . '</option>';
				}
				?>
				</select></td>

			<td>
				<table>
					<tr>
						<td>
							<button class='add_recurringEvent_button'
								name='add_recurringEvent_button'>
								<span class='glyphicon glyphicon-plus' aria-hidden='true'></span>
							</button>
						</td>
					</tr>

					<tr>
						<td>
							<button class='remove_recurringEvent_button'
								name='remove_recurringEvent_button'>
								<span class='glyphicon glyphicon-remove' aria-hidden='true'></span>
							</button>
						</td>
					</tr>

					<tr>
						<td>
							<button class='edit_recurringEvent_button'
								name='edit_recurringEvent_button'
								id='edit_recurringEvent_button'>
								<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>
							</button>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<label for='default_recurringEvents'> Default Recurring Events :
		<button class='add_to_recurringEvents_button'
			name='add_to_recurringEvents_button'>
			<span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span>
		</button>
	</label>
	<br>
	<select class='default_recurringEvents' name='default_recurringEvents'
		size='5'>
			<?php
			$defaultRecurringEvents_files = glob ( 'DefaultRecurringEvents/*.xml' );
			foreach ( $defaultRecurringEvents_files as $f ) {
				$dre = new SimpleXMLElement ( file_get_contents ( $f ) );
				echo "'<option value='" . $dre->getName () . "' selected>" . $dre ['name'] . '</option>';
			}
			?>
		</select>
	<!-- end popup -->


	<!-- JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src='bootstrap/js/jquery.min.js'></script>
	<script src='bootstrap/js/timepicki.js'></script>
	<script src='bootstrap/js/bootstrap.min.js'></script>
	<script src='custom_js/popupCenter.js'></script>
	<script>
		$('#refresh_rate').timepicki({
			increase_direction : 'up',
			min_hour_value : 0,
			max_hour_value : 24,
			overflow_minutes : true,
			start_time : [ '<?php echo substr($refresh_rate, 0, 2)?>', '<?php echo substr($refresh_rate, 4, 2)?>', 'AM' ],
			step_size_minutes : 5,
			show_meridian : false
		});
	</script>
	<script>
		$('#refresh_calendar_url').on('click', function() {
			$.ajax({
				type : 'POST', 	 	
   	            url : 'Calendar.php', 	 	
   	            data : { action: 'ModifyCalendar', calendar_url: $('#calendar_url').val()}, 	 	
   	            success : function() {}
   	        });
		});

		$('#refresh_refresh_rate').on('click', function() {
			$.ajax({
				type : 'POST', 	 	
   	            url : 'Calendar.php', 	 	
   	            data : { action: 'RefreshRefreshRate', refresh_rate: $('#refresh_rate').val()}, 	 	
   	            success : function() {}
   	        });
		});

		$('.remove_recurringEvent_button').on('click', function() {
			var selected = $('select.recurringEvents option:selected');
			$.ajax({
				type : 'POST', 	 	
   	            url : 'Calendar.php', 	 	
   	            data : { action: 'removeRecurringEvent', selected_value: selected.val()}, 	 	
   	            success : function() { 	 	
   	            	selected.remove();
   	            }
   	        });
		});
	
		$('.add_to_recurringEvents_button').on(
				'click',
				function() {
					var selected = $('select.default_recurringEvents option:selected');
					$.ajax({
						type : 'POST', 	 	
		   	            url : 'Calendar.php', 	 	
		   	            data : { action: 'add_to_recurringEvents', selected_value: selected.val()}, 	 	
		   	            success : function() { 	 	
		   	            	var options = selected.sort().clone();
							$('select.recurringEvents').append(options);
		   	            }
		   	        });
				});

		var edit;
		var selected_value;
		$('.add_recurringEvent_button').on('click', function() {
			edit = false;
			return popupCenter('RecurringEvent_popup.php', 'Recurring Event', 480, 500);
		});

		$('.edit_recurringEvent_button').on('click', function() {
			edit = true;
			selected_value = $('select.recurringEvents option:selected').val();
			return popupCenter('RecurringEvent_popup.php', 'Recurring Event', 480, 500);
		});
	</script>
</body>
</html>