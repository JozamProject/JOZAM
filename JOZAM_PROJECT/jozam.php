<?php
session_start ();
require_once ('Calendar.php');
// Test the variable session for connection
if (isset ( $_GET ["state"] )) {
	if ($_GET ["state"] == "ok") {
		$_SESSION ['connect'] = true;
	}
}

if (isset ( $_SESSION ['connect'] )) {
	if ($_SESSION ['connect'] == true) {
		?>

<?php
		// Function to display tasks in each project
		function showTask($task) {
			?>
<button id="modify-task" value="<?php echo $task['id'];?>"
	onclick="popupmodify()"
	style="color: black; font-size: 16px; border: none">+</button>
<abbr id="<?php echo $task['id'];?>"
	title="Description :<?php echo $task->description . "\r\n";?>Comment :<?php echo $task->comment . "\r\n";?>"> 
            <?php if($task['archive']=="true"){?>
            	<s id="<?php echo "underline".$task['id'];?>">
            		<?php
				$var = "- " . $task ['title'] . " || " . $task ['deadLine'] . ' || time left : ' . $task ['timeLeft'];
				echo $var;
				?> 
            	</s>
            <?php
			} else
				echo "- " . $task ['title'] . " || " . $task ['deadLine'] . '|| time left : ' . $task ['timeLeft'];
			?>
        </abbr>
</br>
<?php
		}
		?>

<?php
		// function to load projects from XML dynamically and display them on boards
		function showProject($project) {
			?>
<li id="<?php echo $project['id']?>" data-row="<?php echo $project['data-row']?>" data-col="<?php echo $project['data-col']?>" data-sizex="<?php echo $project['data-sizex']?>" data-sizey="<?php echo $project['data-sizey']?>" class="gs-w scrollable-menu" style= "background:<?php echo $project['color']?>">
	<div id="<?php echo "my-widget".$project['id'];?>"
		value="<?php echo $project['id'];?>" onload="ch(this.id)"
		style="overflow: auto;">
		<header id="<?php echo $project['id']?>"
			ondblclick="selectItem(this.id,0)" onclick="unselectItem(this.id,0)">
			<p id="<?php echo $project['id']?>"
				style="cursor: move; background: #DAD5D5; height: 3px; float: left;"
				class="glyphicon glyphicon-move"></p>
			<div class="dragDiv">
				<button id="<?php echo $project['id']; ?>"
					value="<?php echo $project['name']?>"
					class="btn btn-default btn-xs"
					style="background: none; font-weight: bold; border: none;"
					onclick="changeProjectName(this.id,this.value)">
					<?php echo $project['name']?>
					</button>
				<div id="<?php echo $project['id'];?>" class="delete"
					onclick="deleteproj(this.id)">
					<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
				</div>
				<div id="<?php echo $project['id'];?>" onclick="createproj(this.id)"
					class="load">
					<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
				</div>
				<button id="create-user" value="<?php echo $project['id'];?>"
					style="background: none; border: none;" class="loadtask">
					<span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
				</button>
				<button id="changeColor" value="<?php echo $project['id'];?>"
					style="background: none; border: none;" class="chcolor">
					<span class="glyphicon glyphicon-leaf" aria-hidden="true"></span>
				</button>
			</div>
		</header>
		<div style="text-align: left; margin-left: 3%;"> 
                <?php
			// Iteretor to fetch tasks from XML and display them for each project
			foreach ( $project->task as $stask ) {
				showTask ( $stask );
			}
			?>
                <ul style="background: #DDDDDD;">
                     <?php
			// Iteretor to fetch sub projects and display the recurssevilly
			foreach ( $project->project as $sproject ) {
				showProject ( $sproject );
			}
			?>	
                </ul>
		</div>
	</div>
</li>
<?php
		}
		?>
<?php
		// Load the language of JOZAM
		$languages = new SimpleXMLElement ( 'Languages.xml', 0, true );
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		foreach ( $languages->Language as $l ) {
			if ($l ['chosen'] == "true") {
				$lstring = str_replace ( ' ', '', $l );
				$language = new SimpleXMLElement ( $lstring, 0, true );
			}
		}
		?>
<!-- Startin the HTML document -->
<!DOCTYPE html>
<html charset="UTF-8">
<head>
<!-- SRC needd Bootstrap , Ajax, Jquery et les CSS -->
<title><?php echo $language->Title ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="assets/css/JOZAM_Logo.png"
	type="image/png" />
<link rel="stylesheet" type="text/css"
	href="assets/css/jquery.gridster.css">
<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
<link rel="stylesheet" type="text/css" href="assets/css/boardsStyle.css">
<link rel="stylesheet" href="assets/css/bootstrap.netdna.min.css" />
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-glyphicons.css">
<link rel="stylesheet" href="assets/changeColorAssets/jquery-ui.css">
<link rel="stylesheet" href="popup/jquery-ui.css">

<script src="assets/jquery.min.js"></script>
<script src="assets/css/bootstrap.min.js"></script>
<script src="popup/jquery-ui.js"></script>
<script src="assets/jquery-1.11.2.js"></script>
<script src="assets/jquery.gridster.min.js" charster="utf-8"></script>
<script src='custom_js/popupCenter.js'></script>
<script src='custom_js/periodic_action.js'></script>
</head>
<!-- Body of the project -->
<body>
	<!-- the  bootstrap navigation Bar -->
	<div>
		<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" data-target="#navbarCollapse"
						data-toggle="collapse" class="navbar-toggle">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a href="#" class="navbar-brand"><img
						src="assets/css/JOZAM_Logo.png"
						style="width: 40px; height: 40px; margin-top: -10px;" /></a> <a
						href="#" class="navbar-brand"><?php echo $language->Project ?></a>
				</div>
				<!-- Collection of nav links and other content for toggling -->
				<div id="navbarCollapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a id="addBoard" href="#"><?php echo $language->AddBoard ?></a></li>
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $language->Toggle ?> <span
								class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<!-- Adding elements dynamically to the navigaftion bar "Toggle"-->
                                        <?php
		foreach ( $boards->board as $board ) {
			?>                                        
                                                <li><button
										id="btnshowhide" value="<?php echo $board['id'];?>" href="#"
										style="width: 100%; background: none;"><?php echo $board['name'];?></button></li>
                                            <?php } ?>
                                </ul></li>
						<li><a id="synchronize" href="#"><?php echo $language->Synchronize ?></a></li>
						<li><a id="rollBack" href="#"><?php echo $language->RollBack ?></a></li>
						<li><a id="archive" href="#"><?php echo $language->Archive ?></a></li>
						<li><button
								style='background: none; border: none; margin-top: 15px;'
								class='refresh_button' name='refresh_button' id='refresh_button'>
								<span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
							</button></li>

					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown" role="button" aria-expanded="false">
                                        <?php echo $language->Abb;?>
                                        <span class="caret"></span>
						</a> <!-- choosing the language -->
							<ul class="dropdown-menu" role="menu">
                                        <?php
		foreach ( $languages->Language as $l ) {
			if ($l ['chosen'] == "true")
				$chosen = 'v ';
			else
				$chosen = " ";
			?>
                                    <li href="#">
									<button id="<?php echo $l['name'];?>"
										style="width: 100%; background: none;"
										onclick="change(this.id)">
                                            <?php echo $chosen.$l['name'];?>
                                        </button>
								</li>  
                                        <?php } ?>
                                </ul></li>
						<!-- disconnect -->
						<li><a href="logout.php" id="disconnect"><?php echo $language->Disconnect; ?></a>
						</li>
						<!--Config-->
						<li>
							<button
								onclick="return popupCenter('Configuration_popup.php', 'Configuration', 425, 425)"
								id="config"
								style="background: none; border: none; margin-top: 15px;">
								<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
							</button>
						</li>
						<li><a href="help/help.html" target="_blank"
							class="glyphicon glyphicon-question-sign" aria-hidden="true"></a>

						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<div class="navbar" style="margin-top: 60px;">
		<div class="container" style="margin-left: -20px !important;">
			<!--Charge and display boards on the main menu-->
            <?php
		foreach ( $boards->board as $board ) {
			?> 
                <div id="<?php echo "showorHide".$board['id'];?>"
				style="margin-right: -150px;">
				<div id="<?php echo "duplicater".$board['id'];?>">
					<div id="<?php echo "demo-".$board['id'];?>" class="gridster">

						<div class="btn-group" role="group" aria-label="...">
							<button id="<?php echo $board['id']; ?>"
								value="<?php echo $board['name'];?>" class="btn btn-default"
								style="background: none; font-weight: bold; border: none;"
								onclick="changeBoardName(this.id,this.value)">
                            		<?php echo $board['name'];?>
                            	</button>
							<button type="button" id="addWidgetButton"
								value="<?php echo $board['id'];?>"
								class="btn btn-primary btn-sm">
                                    <?php echo $language->AddProject?>
                                </button>
							<button type="button" id="deleteWidgetButton"
								value="<?php echo $board['id'];?>"
								class="btn btn-primary btn-sm">
                                    <?php echo $language->DeleteBoard?>
                                </button>
						</div>
						<ul id="<?php echo $board['id'];?>"
							style="width: 105% !important; padding: 0;"
							ondblclick="selectItem(this.id,1)"
							onclick="unselectItem(this.id,1)">
							<!--Display projects in this board-->
                                    <?php
			foreach ( $board->project as $project ) {
				showProject ( $project );
			}
			?>
                            </ul>
					</div>
				</div>
			</div>
            <?php
		}
		?>
            </div>
	</div>

	<!-- end of the html code of -->
	<!-- the JS Script needed -->
	<script>
	$('#refresh_button').on('click', function() {
		$.ajax({
			type : 'POST', 	 	
   	    	url : 'Calendar.php', 	 	
   	    	data : { action: 'RefreshTimeLeft' }, 	 	
   	    	success : function() {
	   	   	    window.location.reload();
   	   		}
   		});
	});
	</script>
	<script>
		$(document).on( "click", "#deleteWidgetButton", function(e) {
            e.preventDefault(); 
            var action = "DeleteBoard";
            var me = $(this);
            var idBoard = me.val();
            //alert(idBoard);
            $.ajax({
                type : "POST",
                url  : "trait.php",
                data : { action : action , idBoard : idBoard },
                success: function(data)
                {
                    if(data!="")
                    	alert(data);
                    else 
               		 window.location.reload();
                }
            });
		});
        </script>
	<script>
                //Creating new board ans send action to trait.php to save it in input XML             
                var i = 2;
                var idcpt = 0;
                function duplicate() {
                    var action = "CreateBoard";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action },
                        success: function(data)
                        {
                        	if(data!="")
                            	alert(data);
                        	else 
                        		 window.location.reload();
                        }
                    });
                   
                }
                document.getElementById('addBoard').onclick = duplicate;

                // Script Handling the gridster
                var gridster = [];
                var startPosition = {};
                //Max board number
                var taille = 50;
                //the function of gridster
                $(function()
                {
                    var nombre = 0;
                    //creating the handler fo boards
                    for(nombre =0; nombre < taille; nombre++)
                    {
                        gridster[nombre] = $("#demo-" + nombre + " ul").gridster({
                            namespace: '#demo-' + nombre,
                            widget_base_dimensions: [100, 100],
                            widget_margins: [1, 1],
                           // autogrow_cols: true,
                           // widget_seletor: 'li'
                            
                            resize: {
                                enabled: true,
                                //Retrieve new dimensions of prjects
                                min_size: [2, 1],
                                stop: function (e, ui, $widget) {
                                    var newDimensions = this.serialize($widget)[0];
                                    var action = "ModifyProject";
                                    var action1 = "Resize";
                                    $.ajax({
                                        type : "POST",
                                        url : "trait.php",
                                        data : { action : action , action1 : action1 , idProj : $widget.attr('id') , NewWidth : newDimensions.size_x , NewHeight : newDimensions.size_y},
                                        success: function(data)
                                        {
                                            if(data!="")
                                            	alert(data);
                                        }
                                    });
                                }
                            },
                            draggable: {
                                handle: 'header p'                        
                            }
                        }).data('gridster');
                    }

                    //The handler of creating new project inside the gridster
                    var action = "CreateProject";

                    $(document).on( "click", "#addWidgetButton", function(e) {
                         e.preventDefault(); 
                         idcpt++;
                         var me = $(this);
                         var idParent = me.val();
                         gridster[me.val()].add_widget.apply(gridster[me.val()], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: LightSlateGray;"><div id = "my-widget'+idcpt+'"><header><p id="'+idcpt+'" style="cursor: move; background: #DAD5D5; height : 3px; float : left;" ondblclick="selectItem(this.id)" onclick="unselectItem(this.id)" class="glyphicon glyphicon-move" ></p><div class="dragDiv">New project<div id="'+me.val()+'" class="delete" onclick="deleteproj(this.id)"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></div><div id="'+me.val()+'" onclick="createproj(this.id)" class="load"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span></div><button id="create-user" value="'+me.val()+'" style="background : none; border : none;" class="loadtask"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></button><button id="changeColor" value="'+idcpt+'" style="background : none; border : none;" class="chcolor"><span class="glyphicon glyphicon-leaf" aria-hidden="true"></span></button></button></div></header></div></li>', 2, 1]);
                        //activate the color change on the new project
                        //$("#my-widget"+idcpt).colorize();
                        //activate the tast creator on the new project
                        popup();
                        //send the new project created to trait.php to save it in the input xml
                          $.ajax({
                            type: "POST",
                            url: 'trait.php',
                            data: { action : action , idParent : idParent },
                            success: function(data)
                            {
                                if(data!="")
                                	alert(data);
                            }
                        });
                    });
                    //set the handler of mouse event on the projects
                    $('.gridster li div header p').on('mousedown', mouseDownHandler).on('mouseup', mouseUpHandler );
                });
                //the mouse handler function "Down"
                function mouseDownHandler(event)
                {
                    event = event || window.event; // IE-ism

                    /** save start position to see if we dragged **/
                    startPosition = {
                        x: event.clientX,
                        y: event.clientY

                                  };
                }
                //the mouse handler function "Up"
                function mouseUpHandler(event)
                {
                    event = event || window.event; // IE-ism
                    /** get drop position to see if we dragged and where we dropped it **/
                    var dropPosition = {
                        x: event.clientX,
                        y: event.clientY
                    };
                    /** the element we clicked or dragged on **/
                    var liElement = $(this).parent().parent().parent();
                    /** the gridster of the element we clicked on **/
                    var currentGridster = liElement.closest('.gridster');
                    /** the gridster object of the element we clicked on **/
                    var gridsterObject = getGridsterObjectById(currentGridster.attr('id'));
                    /** check if we dragged **/
                    if( startPosition.x == dropPosition.x && startPosition.y == dropPosition.y ) {
                        return true;
                    }
                    //send the new position of the gridster
                    if( startPosition.x != dropPosition.x && startPosition.y != dropPosition.y ) {
                        var action = "ModifyProject";
                        var action1 = "Move";
                         $.ajax({
                             type : "POST",
                             url : "trait.php",
                             data : { action : action , action1 : action1 , idProj : liElement.attr('id'), NewCol : (parseInt(Math.round((dropPosition.x-startPosition.x)/100))+parseInt(liElement.attr('data-col'))) , NewRow : (parseInt(Math.round((dropPosition.y-startPosition.y)/100))+parseInt(liElement.attr('data-row'))) },
                             success: function(data)
                             {
                                 if(data!="")
                                 	alert(data);
                             }
                         });
                    }

                    /** loop through all gridsters to check if we dropped the element in here **/
                    /*
                    $('.gridster').each(function() {
                        var offset = $(this).offset();
                        /** check if element is dropped in the current gridster **/
                      /*  if( 
                            dropPosition.x > offset.left && 
                            dropPosition.x < ( offset.left+$(this).width() ) &&
                            dropPosition.y > offset.top && 
                            dropPosition.y < ( offset.top+$(this).height() ) &&
                            $(this).attr('id') != currentGridster.attr('id')
                        ) {
                            /** get the new gridster object to put the element in **/
                        //    newGridsterObject = getGridsterObjectById($(this).attr('id'));
                            /** get the HTML of the liElement **/
                         //   var newLiElement = liElement.clone().removeAttr('style').wrap('<p>').parent().html();
                            /** add the listeners on the new element **/
                        //    $(newLiElement).on('mousedown', mouseDownHandler).on('mouseup', mouseUpHandler );
                            /** add the liElement widget **/
                        //    newGridsterObject.add_widget(newLiElement);
                            /** remove the old widget **/
                        //    gridsterObject.remove_widget(liElement);
                      //  }
                   // });
                }
                /**
                 * Get the gridster object by id
                 */
                function getGridsterObjectById(id)
                {
                    return $('#'+id).find('ul').data('gridster');
                }
            </script>

	<script>
            //Show and Hide Boards the Toggle Button
                    var idPrj;
                    $(document).on( "click", "#btnshowhide", function() {
                            var m = $(this);
                            idPrj = m.val();
                            $("#showorHide"+idPrj).toggle();
                    });

            </script>

	<script>
                //function to send the new Created project to save it on XML
                function createproj(idParent){
                    var action = "CreateProject";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action , idParent: idParent },
                        success: function(data)
                        {
                            if(data!="")
                            	alert(data);
                            else 
                       		 window.location.reload();
                        }
                    });
                }
                //function to send the deleted project to MAJ it on XML
                function deleteproj(idProj){
                 if (confirm("Confirm delete ?")) { // Clic sur OK
                    var action = "DeleteProject";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action, idProj : idProj },
                        success: function(data)
                        {
                            if(data!="")
                            	alert(data);
                            else 
                       		 window.location.reload();
                        }
                    });
                 }
                }
            </script>
	<!-- src script jquery to change color-->
	<script src="assets/changeColorAssets/jquery-ui.js"></script>
	<!-- style for color changer -->
	<style>
.custom-colorize {
	position: relative;
	width: 100%;
	height: 100%;
}

.custom-colorize-changer {
	font-size: 10px;
	position: absolute;
	height: 0px;
	width: 0px;
	left: 1px;
	bottom: 1px;
	background-color: none;
}
</style>
	<!--Script of color changer-->
	<script>

              $(function() {
                  //variable colors
                  var i=0;
                  var j=0;
                  var k=0;
                // the widget definition, where "custom" is the namespace,
                // "colorize" the widget name
                $.widget( "custom.colorize", {
                  // default options
                  options: {
                    red: 25,
                    green: 34,
                    blue: 55,
                    // callbacks
                    change: null,
                    random: null
                  },

                  // the constructor
                  _create: function() {
                    this.element
                      // add a class for theming
                      .addClass( "custom-colorize" )
                      // prevent double click to select text
                      .disableSelection();

                    // bind click events on the changer button to the random method
                    this._on( this.changer, {
                      // _on won't call random when widget is disabled
                      click: "random"
                    });
                    //this._refresh();
                  },
                  // called when created, and later when changing options
                  
                  // a public method to change the color to a random value
                  // can be called directly via .colorize( "random" )
                  random: function( event ) {
                    var colors = {
                      red: i*250,
                      blue: j*250,
                      green: (1-k)*250
                      };
  
                      //switcher color algorithm
                      if (j==0 && i==0 && k==0) {i=0; j=1; k=1;}
                      else if (j==1&&i==0&&k==1) {j=0; i=1; k=0;}
                            else if(i==1 && k==0 && j==0){i=1; j=1; k=1;}
                            else if(i==1&& j==1&& k==1){i=1; j=0; k=1;}
                                 else {i=0; j=0; k=0;}

                    // trigger an event, check if it's canceled
                    if ( this._trigger( "random", event, colors ) !== false ) {
                      this.option( colors );
                    }

                  },

                  // events bound via _on are removed automatically
                  // revert other modifications here
                  _destroy: function() {
                    // remove generated elements
                    this.changer.remove();

                    this.element
                      .removeClass( "custom-colorize" )
                      .enableSelection()
                      .css( "background-color", "transparent" );
                  },

                  // _setOptions is called with a hash of all options that are changing
                  // always refresh when changing options
                  _setOptions: function() {
                    // _super and _superApply handle keeping the right this-context
                    this._superApply( arguments );
                    //this._refresh();
                  },

                  // _setOption is called for each individual option that is changing
                  _setOption: function( key, value ) {
                    // prevent invalid color values
                    if ( /red|green|blue/.test(key) && (value < 0 || value > 255) ) {
                      return;
                    }
                    this._super( key, value );
                  }
                });

                // initialize with default options all projects on load are red
                  for(var j=0;j<20;j++){
                      for(var i=0;i<50;i++){
                        $( "#my-widget"+j+"-"+i ).colorize();
                          for(var k=0;k<20;k++){
                               $( "#my-widget"+j+"-"+i+"-"+k ).colorize();
                              for(var l=0;l<10;l++){
                                  $( "#my-widget"+j+"-"+i+"-"+k+"-"+l ).colorize();
                              }
                          }
                      }
                  }

                // click to toggle enabled/disabled not used
                $( "#disable" ).click(function() {
                  // use the custom selector created for each widget to find all instances
                  // all instances are toggled together, so we can check the state from the first
                  if ( $( ":custom-colorize" ).colorize( "option", "disabled" ) ) {
                    $( ":custom-colorize" ).colorize( "enable" );
                  } else {
                    $( ":custom-colorize" ).colorize( "disable" );
                  }
                });

                // click to set options after initialization
                $( "#green" ).click( function() {
                  $( ":custom-colorize" ).colorize( "option", {
                    red: 64,
                    green: 250,
                    blue: 8
                  });
                });
              });
        </script>

	<script>

              $(document).on( "click", "#changeColor", function() {
                  var me = $(this);
                  idProj = me.val();
                   $.ajax({ 
                       type : "POST",
                       url  : "colorChanger.php",
                       data : { idProj : idProj },
                       success : function(output) { 
                           document.getElementById(idProj).style.backgroundColor=output;
                       }
                        });
              });
        </script>
	<!-- style script of popup task creator-->
	<style>
label, input {
	display: block;
}

input.text {
	margin-bottom: 12px;
	width: 95%;
	padding: .4em;
}

fieldset {
	padding: 0;
	border: 0;
	margin-top: 25px;
}

div#users-contain {
	width: 350px;
	margin: 20px 0;
}

div#users-contain table {
	margin: 1em 0;
	border-collapse: collapse;
	width: 100%;
}

div#users-contain table td, div#users-contain table th {
	border: 1px solid #eee;
	padding: .6em 10px;
	text-align: left;
}

.ui-dialog .ui-state-error {
	padding: .3em;
}

.validateTips {
	border: 1px solid transparent;
	padding: 0.3em;
}
</style>
	<!-- popup function task creator -->
	<script>
            //need to be called once to activate the popup
            popup();
            //function of the popup
            function popup() {
              $(function(){
                  //elements needed for the popup
                var idProject;
                var dialog, form,
                  action = "CreateTask",
                  title = $( "#title" ),
                  description = $( "#description" ),
                  deadLine = $( "#deadLine" ),
                  comment = $( "#comment" ),
                  allFields = $( [] ).add( title ).add( description ).add( deadLine ).add( comment ),
                  tips = $( ".validateTips" );

                function updateTips( t ) {
                  tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                  setTimeout(function() {
                    tips.removeClass( "ui-state-highlight", 1500 );
                  }, 500 );
                }
                  //not used
                /*function addUser() {
                  var valid = true;
                  allFields.removeClass( "ui-state-error" );
                  dialog.dialog( "close" );
                  return valid;
                }*/
                  //the dialog form
                dialog = $( "#dialog-form" ).dialog({
                  autoOpen: false,
                  height: 400,
                  width: 450,
                  modal: true,
                  buttons: {
                      //send data on click button
                    "Create task": function(){
                        $.ajax({
                        type :"POST",
                        url : "trait.php",
                        data: { action : action, idProject : idProject, title : title.val() , deadLine : deadLine.val() , comment : comment.val() , description : description.val() },
                            success: function(data)
                            {
                            	 if(data!="")
                                 	alert(data);
                            	 else 
                            		 window.location.reload();
                            } 
                        });
                        //dialog.dialog( "close" );
                        //window.location.reload(); 
                    },
                    Cancel: function() {
                      dialog.dialog( "close" );
                    }
                  },
                  close: function() {
                    form[ 0 ].reset();
                    allFields.removeClass( "ui-state-error" );
                  }
                });

                form = dialog.find( "form" ).on( "submit", function( event ) {
                  event.preventDefault();
                  //addUser();
                 dialog.dialog( "close" );
                 window.location.reload();
                });
                  //on click button activate the dialog form
                $(document).on( "click", "#create-user", function() {
                  var me = $(this);
                  idProject = me.val();
                  dialog.dialog( "open" );
                });
              });
              //datepicker to add calendar to deadline
                $(function() {
                    $( "#deadLine" ).datepicker();
              });
            }
        </script>
	<!-- script of task modifyer -->
	<script>
            //popupmodify() modify a task
            /*function popupmodify(){
                $(document).on( "click", "#modify-task", function() {
                    /**** To implement here ****/
                    /*alert("not implemented yet !!");

                });

            }*/
        
        </script>

	<!-- Script of Switcher Alt + t  : change order of boards-->

	<!-- Change Language Script -->
	<script>
                //sending the langage chose !!
                function change(clicked_lang){
                    //send the chosen language to php file
                    $.ajax({
                        type : "POST",
                        url  : "languageChanger.php",
                        data : { clicked_lang : clicked_lang },
                        success: function(data)
                        {
                            if(data!="")
                            	alert(data);
                            else 
                       		 window.location.reload();
                        }
                    });
                }
        </script>

	<script>

        $(document).on( "click", "#synchronize", function() {
            var action = "synchronize";
             $.ajax({ 
                 type : "POST",
                 url  : "rollBack.php",
                 data : { action : action },
                 success : function(output) { 
                	 if(output!="")
                     	alert(output);
                	 else 
                    	alert("The synchronisation succeded");
                 }
                  }); 
        });

        $(document).on( "click", "#rollBack", function() {
            var action = "rollBack";
             $.ajax({ 
                 type : "POST",
                 url  : "rollBack.php",
                 data : { action : action },
                 success : function(output) { 
                	 if(output!="")
                      	alert(output);
                 	 else 
                 		window.location.reload(); 
              	 }
                  });
        });

        $(document).on( "click", "#archive", function() {
            var action = "archive";
             $.ajax({ 
                 type : "POST",
                 url  : "archive.php",
                 data : { action : action },
                 success : function(output) { 
                	 if(output!="")
                     	alert(output);
                	 else 
                    	alert("Tasks archived !");
                	 	window.location.reload(); 
                 }
                  }); 
        });
        </script>

	<!--change board name-->
	<script>
            function changeBoardName(id,name){
                var action = "ModifyBoardName";
                var newName = prompt("Please enter board name", name);
				if (newName != null) {
                    $.ajax({
                        type : "POST",
                        url : "trait.php",
                        data : { action : action , newName : newName , id : id },
                        success : function(output) { 
                             if(output!="")
                                alert(output);
                             else 
                                window.location.reload(); 
                        }
                    }); 
                }
            }
			
			function changeProjectName(id,name){
				
                var action = "ModifyProjectName";
                var newName = prompt("Please enter Project name", name);
                if (newName != null) {
                    $.ajax({
                        type : "POST",
                        url : "trait.php",
                        data : { action : action , newName : newName , id : id },
                        success : function(output) { 
                             if(output!="")
                                alert(output);
                             else 
                                window.location.reload(); 
                        }
                    }); 
                }
            }
			
        </script>


	<script>
            //To Do SELECTED PROJECT
            var selectedProjects = [];
            var copiedProjects = [];
            var cut = false;
            var k = 0;
            
            function contains(a, obj) {
                var i = a.length;
                while (i--) {
                   if (a[i] == obj) {
                       return true;
                   }
                }
                return false;
            }
            
            function selectItem(id,i){
                if(i!=1 || k==0){
                    if(i!=1)
                		k = k+1;
                	document.getElementById(id).style.borderStyle = 'solid';                
                	document.getElementById(id).style.borderColor = '#000000';  
                	selectedProjects.push(id); 
                }
            }
            function unselectItem(id,i){
                if(i!=1){
                	if(contains(selectedProjects,id)){
                    	k = k-1;
                	}
                }
                document.getElementById(id).style.borderStyle = 'none';
                var index = selectedProjects.indexOf(id);
                if(index > -1){
                    selectedProjects.splice(index,1);
                }             
            }
            var map = {17: false, 18: false, 84: false, 86: false, 88: false, 67: false, 78 : false};
            function keydown(e) {
                if (e.keyCode in map) {
                    map[e.keyCode] = true;
                    if (map[18] && map[84]) {
                        action = "Toggle";
                        // send the new order to XML
                        $.ajax({
                            type: "POST",
                            url: 'trait.php',
                            data: { action : action },
                            success: function(data)
                            {
                                if(data!="")
                                	alert(data);
                                else 
                           		 window.location.reload();
                            }
                        });
                    }
                    else if(map[18] && map[78]) {
                        var action;
                        var idString;
                        if(selectedProjects.length==0){
                            action = "CreateBoard";
                        }
                        else {
                    		action = "CreateProjects";
                        	idString = JSON.stringify(selectedProjects);
                        }
                    	$.ajax({
	                        type : "POST",
	                        url  : "trait.php",
	                        data : { action : action , idString : idString },
	                        success: function(data)
	                        {
	                            if(data!="")
	                            	alert(data);
	                            else
	                       		 	window.location.reload();
	                        }
	                    });
                    }
                    else if(map[17] && map[88]) {
                        for(var i=0;i<selectedProjects.length;i++){
                            document.getElementById(selectedProjects[i]).style.borderStyle = 'none';
                        }
                    	for(var i=0;i<selectedProjects.length;i++){
                            if(selectedProjects[i].length == 1){
                                selectedProjects.splice(i,1);
                            }
                        }
                        copiedProjects = selectedProjects.slice();
						selectedProjects = [];
						cut = true;
						k=0;
                    }
                    else if(map[17] && map[67]) {
                        for(var i=0;i<selectedProjects.length;i++){
                            document.getElementById(selectedProjects[i]).style.borderStyle = 'none';
                        }
                    	for(var i=0;i<selectedProjects.length;i++){
                            if(selectedProjects[i].length == 1){
                                selectedProjects.splice(i,1);
                            }
                        }
                        copiedProjects = selectedProjects.slice();
						selectedProjects = [];
						cut = false;
						k=0;
                    }
                    else if(map[17] && map[86]) {
                        if(selectedProjects.length != 1){
                            alert(<?php echo json_encode((string)$language->PasteError);?>);
                        }
                        var action = "PasteProjects";
                        var id = selectedProjects[0];
                        var idString = JSON.stringify(copiedProjects);
                        $.ajax({
                            type: "POST",
                            url: 'trait.php',
                            data: { action : action, id : id, idString : idString, cut : cut },
                            success: function(data)
                            {
                                if(data!="")
                                	alert(data);
                                else 
                           		 window.location.reload();
                            }
                        });
                        
                        document.getElementById(selectedProjects[0]).style.borderStyle = 'none';
                        selectedProjects = [];
                        cutProjects = [];
                    }
                }
            }
            //function of key event
            function keyup(e) {
                if (e.keyCode in map) {
                    map[e.keyCode] = false;
                }
            }
            //key event
            window.addEventListener('keyup', keyup);
            window.addEventListener('keydown', keydown);
        </script>
	<!-- the Form of the popup -->
	<div id="dialog-form" title="Create task"
		style="height: 410px !important;">
		<form>
			<fieldset>
				<label for="title"> Title </label> <input type="text" name="title"
					id="title" class="text ui-widget-content ui-corner-all"> <label
					for="description"> Description </label> <input type="text"
					name="description" id="description"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<label for="deadLine"> Deadline </label> <input type="datepicker"
					name="deadLine" id="deadLine"
					class="text ui-widget-content ui-corner-all"> <label for="comment">
					comment </label> <input type="text" name="comment" id="comment"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<input type="submit" tabindex="-1"
					style="position: absolute; top: -1000px">
			</fieldset>
		</form>
	</div>
	<!-- end form -->
	<!-- script of task modifyer -->
	<script> 	 	
	            popupmodify();
	            //popupmodify() modify a task 	 	
	            function popupmodify(){ 	 	
	                $(function() { 	 	
							/**** To implement here ****/ 	 	
								 //elements needed for the popup 	 	
						var idTask; 	 	
						var dialog, form,
                          archivemodif ,
						  titlemodif = $( "#titlemodif" ), 	 	
						  descriptionmodif = $( "#descriptionmodif" ), 	 	
						  deadLinemodif = $( "#deadLinemodif" ), 	 	
						  commentmodif = $( "#commentmodif" ), 	 	
						  allFields = $( [] ).add( titlemodif ).add( descriptionmodif ).add( deadLinemodif ).add( commentmodif ).add(archivemodif), 	 	
						  tips = $( ".validateTips" ); 	 	
	 	 	
						function updateTips( t ) { 	 	
						  tips 	 	
							.text( t ) 	 	
							.addClass( "ui-state-highlight" ); 	 	
						  setTimeout(function() { 	 	
							tips.removeClass( "ui-state-highlight", 1500 ); 	 	
						  }, 500 ); 	 	
						} 	 	
						  //not used 	 	
					  /*  function addUser() { 	 	
						  var valid = true; 	 	
						  allFields.removeClass( "ui-state-error" ); 	 	
						  dialog.dialog( "close" ); 	 	
						  return valid; 	 	
						}*/ 	 	
						  //the dialog form 	 	
						dialog = $( "#dialog-form-modify" ).dialog({ 	 	
						  autoOpen: false, 	 	
						  height: 600, 	 	
						  width: 450, 	 	
						  modal: true, 	 	
						  buttons: { 	 	
							  //send data on click button 	 	
							"Modify": function(){ 	
								  var descr = <?php echo json_encode((string)$language->Description);?>+" :"+descriptionmodif.val()+"\n";
								  var com = <?php echo json_encode((string)$language->Comment);?>+" :"+commentmodif.val();
                                  var title = titlemodif.val();
                                  var deadLine = deadLinemodif.val(); 
		   						  action = "ModifyTask";	 	
		   						  $.ajax({
		   	                         type :"POST",
		   	                         url : "trait.php",
		   	                         data: { action : action, idTask : idTask, title : titlemodif.val() , deadLine : deadLinemodif.val() , comment : commentmodif.val() , description : descriptionmodif.val() , archivemodif : archivemodif},
		   	                             success: function(data)
		   	                             {
			   	                             var task = document.getElementById(idTask);
                                             if(archivemodif == "false"){
                                                 var underline = document.getElementById("underline"+idTask);
                                                 if(underline != null){
                                                     underline.parentNode.removeChild(underline);
                                                 }
                                                 //task.firstChild.nodeValue ="- "+title+" || "+deadLine;
                                             }
                                             else{
                                                 if(data == "true" && archivemodif == "true")
                                                     document.getElementById("underline"+idTask).firstChild.nodeValue = "- "+title+" || "+deadLine;
                                                 else
                                                 {
                                                	 task.firstChild.nodeValue = "";
                                                     var underline = document.createElement('s');
                                                     underline.setAttribute('id','underline'+idTask);
                                                     underline.innerHTML = "- "+title+" || "+deadLine;
                                                     //task.appendChild(underline,null);
                                                     
                                                 }
                                                
                                             }
                                             
                                                /*  if(data == "true"){
			   	                            	    document.getElementById("underline"+idTask).firstChild.nodeValue = "- "+title+" || "+deadLine;
                                                  }
                                                else
                                                  task.nodeValue ="- "+title+" || "+deadLine;*/
			   	                            	//task.title = descr+com;

			   	                            	window.location.reload();
		   	                            	  
		   	                             } 	 	
		   	                             	 	
		   	                      }); 	 
		   						  dialog.dialog( "close" );
							}, 	 	
   							   	 	
   							  "Delete": function(){ 
   								if (confirm("Confirm delete ?")) {	 	
   									action = "DeleteTask";	 	
		   						  	$.ajax({
		   	                         	type :"POST",
		   	                         	url : "trait.php",
		   	                         	data: { action : action, idTask : idTask},
		   	                            success: function(data)
		   	                             	{
		   	                            	  	if(data!="")
		   	                                  	 	alert(data);
		   	                            	  	else
		   	                            	  		window.location.reload();
		   	                             	} 	 	
		   	                      	}); 	 
		   						  dialog.dialog( "close" );	
   								} 	 	
   							  }, 	 	
   							  Cancel: function() { 	 	
   							  dialog.dialog( "close" ); 	 	
   							} 	 	
   						  }, 	 	
   						  close: function() { 	 	
   							form[ 0 ].reset(); 	 	
   							allFields.removeClass( "ui-state-error" ); 	 	
   						  } 	 	
   						}); 	 	
   	 	 	
   						form = dialog.find( "form" ).on( "submit", function( event ) { 	 	
   						  event.preventDefault(); 	 	
   						 // addUser();
   						 window.location.reload();
   						 dialog.dialog( "close" );  	 	
   						});
                        
                        //on click check box
                        $(document).on( "click", "#archivemodif", function() { 
                            
                            if(document.getElementById("archivemodif").checked  == true)
                                archivemodif = 'true';
                            else
                                archivemodif = 'false';
                        });
   						  //on click button activate the dialog form 	 	
   						$(document).on( "click", "#modify-task", function() { 	 	
   						  var me = $(this); 	 	
   						  idTask = me.val(); 
   						  action = "GetTaskAttributes";	 	
   	                      $.ajax({ 	 	
   	                            type : "POST", 	 	
   	                            url : "trait.php", 	 	
   	                            data : { action : action , idTask : idTask }, 	 	
   	                            success : function(data) 	 	
   	                            { 	 	
   	   	                            var attributes = data.split("|");
   	    	                        dialog.dialog( "open" ); 	 	
   	    	                        $("#titlemodif").val(attributes[0]); 	 	
   	    	                        $("#descriptionmodif").val(attributes[1]);
   	    	                        $("#commentmodif").val(attributes[2]); 	 
   	    	                     	$("#deadLinemodif").val(attributes[3]); 
   	    	                     	$("#archivemodif").val(attributes[4]);
                                    archivemodif = attributes[4];
                                    if(attributes[4]=="true")
                                        document.getElementById("archivemodif").checked = true;
                                    else
                                        document.getElementById("archivemodif").checked = false;

                                    var link = $("#openFreeTimeCalendar");
                                    link.attr('href', 'User_data/FreeTimeCalendars/task_' + idTask + '.ics');
   	                            } 	 	
   	                             	 	
   	                      }); 	
   	                        	
   						   	 	
   						}); 	 	
   							 	 	
   	 	 	
   	                }); 	 	
   	                 	 	
   	              $(function() { 	 	
   	                    $( "#deadLinemodif" ).datepicker(); 	 	
   	              }); 	 	
   	 	 	
   	            } 	 	
   	         	 	
   	        </script>
	<!-- the Form of the popup modify -->
	<div id="dialog-form-modify" title="Modify task"
		style="height: 4 !important;">
		<form>
			<fieldset>
				<label for="archivemodif"> State </label> <label
					class="checkbox-inline"> <input type="checkbox" id="archivemodif"
					name="archivemodif" value="archivemodif"> Archive
				</label> <label for="titlemodif"> Title </label> <input type="text"
					name="titlemodif" id="titlemodif"
					class="text ui-widget-content ui-corner-all"> <label
					for="descriptionmodif"> Description </label> <input type="text"
					name="descriptionmodif" id="descriptionmodif"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<label for="deadLinemodif"> Deadline </label> <input
					type="datepicker" name="deadLinemodif" id="deadLinemodif"
					class="text ui-widget-content ui-corner-all"> <label
					for="commentmodif"> comment </label> <input type="text"
					name="commentmodif" id="commentmodif"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<input type="submit" tabindex="-1"
					style="position: absolute; top: -1000px"> <label> <a
					id='openFreeTimeCalendar'>Open Free Time Calendar</a>
				</label>
			</fieldset>
		</form>
	</div>
	<!-- end form -->
</body>
</html>

<?php
	}
}
?>