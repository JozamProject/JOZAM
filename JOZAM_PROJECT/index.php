<!DOCTYPE html>
<html lang="en" charset="UTF-8">
	<head>
		<title>JOZAM PROJECT</title>
		<meta name="author" content="gyurisc">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="assets/css/jquery.gridster.css">
		<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
		<link rel="stylesheet" type="text/css" href="assets/css/boardsStyle.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        	<!-- sources pour le changement des couleurs-->
		<link rel="stylesheet" href="assets/changeColorAssets/jquery-ui.css">
		      <!-- popup -->
		<link rel="stylesheet" href="popup/jquery-ui.css">
		<script src="popup/jquery-ui.js"></script>
	</head>
	<body>
        <div>
            <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="#" class="navbar-brand">JOZAM Task Manager</a>
                    </div>
                    <!-- Collection of nav links and other content for toggling -->
                    <div id="navbarCollapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a id="addBoard" href="#">Add board</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Toggle <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a id="btnshowhide" href="#">All</a></li>
                                    <li><a id="btnshowhideTravail" href="#">Travail</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" id="btnshowhideSurveille">Surveille</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#">Jozam User</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="navbar" style="margin-top: 50px;">
            <div id="showorHideBoards" class="container">
		<?php 
			$boards = new SimpleXMLElement('input.xml',0,true);
			foreach($boards->board as $board){
				?> 
							
					<div id=<?php echo "showorHide".$board['nom'];?>>
                    <div id=<?php echo "duplicater".$board['id'];?>>
                        <div id=<?php echo "demo-".$board['id'];?> class="gridster">
                            <div contenteditable="true">
                                <h2><?php echo $board['nom'];?></h2>
                            </div>
                            <button type="button" id="addWidgetButton" value="<?php echo $board['id'];?>" class="btn btn-default btn-sm" >Add Project</button>
                            <ul id="myList">
                            	<?php 
                            		foreach($board->projet as $projet){
                            			afficherProjet($projet);
                            		}
                            	?>
			
							</ul>
						</div>
           			</div> 
        			</div>
        	<?php }?>
        	</div>
        </div>
        <script src="assets/jquery-1.11.2.js"></script>
		<script src="assets/jquery.gridster.min.js" charster="utf-8"></script>
		
		<script>
		//make all editable
            //$('#showorHideBoards div').attr('contenteditable','true');

            //duplicate             
			var i = 2;
            var idcpt = 0;
			
			function duplicate() {
                "use strict";
				var original = document.getElementById('duplicater' + i);
				var clone = original.cloneNode(true); // "deep" clone
                //i += i;
				clone.id = "duplicetor" + ++i; // there can only be one element with an ID
				//clone.onclick = duplicate; // event handlers are not cloned
				original.parentNode.appendChild(clone);
                //show the hiden board or the duplicated one
                document.getElementById('duplicater2').style.display = 'block';
				--i;// -= i;
			}
			document.getElementById('addBoard').onclick = duplicate;
			
		
				
			function getAllElementsWithAttribute(attribute) {
			    var matchingElements = [];
			    var allElements = document.getElementsByTagName('*');
                var i = 0;
                var n = allElements.length;
			    for (i = 0; i < n; i++)
			    {
				  if (allElements[i].getAttribute(attribute) !== null)
				  {
				    matchingElements.push(allElements[i]);
				  }
			    }
			    return matchingElements;
			}
		
			var gridster = [];
			var startPosition = {};
			var taille = 10;
			$(function()
			{
                var nombre = 0;
				for(nombre =0; nombre < taille; nombre++)
				{
					gridster[nombre] = $("#demo-" + nombre + " ul").gridster({
						namespace: '#demo-' + nombre,
						widget_base_dimensions: [100, 100],
						widget_margins: [5, 5],
                       // autogrow_cols: true,
                       // widget_seletor: 'li'
						resize: {
							enabled: true
						},
                        draggable: {
                            handle: 'header p'                        
                        }
					}).data('gridster');
				}
                				
				var action = "CreationProjet" + "\r\n";
                var board = "0";
               /* var  n = getAllElementsWithAttribute("data-row").length;
				for (j =0; j < n; j++)
				{
					testID += getAllElementsWithAttribute("data-row")[j].getAttribute('data-row');
				}*/
				
				$(document).on( "click", "#addWidgetButton", function(e) {
					 e.preventDefault(); 
                     idcpt++;
                     var me = $(this);
                     //alert(me.val());
                     gridster[me.val()].add_widget.apply(gridster[me.val()], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: #E8AC71;"><div id = "my-widget'+idcpt+'"><header><p style="cursor: move; background: #DDDDDD;" >|||</p><div class="dragDiv" contenteditable="true">SousProject<div id="loadbuttonsous" class="load">+</div><div id="deletebuttonsous" class="delete">*</div><button id="create-user'+idcpt+'" style = "height : 7px; width: 7px;"></button></div></header><div id="divtestsous" style="overflow:auto;"></div></div></li>', 1, 1]);
                    $("#my-widget"+idcpt).colorize();
                    /**************************/
                  
                    popup(idcpt);
                    
                    
                    
                    
                    /******************************/
					  $.ajax({
						type: "POST",
						url: 'trait.php',
						data: { action : action , board : board },
						success: function(data)
						{
							//alert("Project created!");
						}
					});
				});
                var board = "1";
                $(document).on( "click", "#addWidgetButtonSurveille", function(e) {
					 e.preventDefault(); 
					   idcpt++;
					 gridster[1].add_widget.apply(gridster[1], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: #E8AC71;"><div id = "my-widget'+idcpt+'"><header><p style="cursor: move; background: #DDDDDD;" >|||</p><div class="dragDiv" contenteditable="true">SousProject<div id="loadbuttonsous" class="load">+</div><div id="deletebuttonsous" class="delete">*</div><button id="create-user'+idcpt+'" style = "height : 7px; width: 7px;"></button></div></header><div id="divtestsous" style="overflow:auto;"></div></div></li>', 1, 1]);
                    $("#my-widget"+idcpt).colorize();
                    /**************************/
                    
                   
                    popup(idcpt);
                    
                    
					  $.ajax({
						type: "POST",
						url: 'trait.php',
						data: { action : action , board : board },
						success: function(data)
						{
							//alert("Project created!");
						}
					});
				});
                var board = "2";
                $(document).on( "click", "#addWidgetButtonNew", function(e) {
					 e.preventDefault(); 
					  idcpt++;
					 gridster[2].add_widget.apply(gridster[2], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: #E8AC71;"><div id = "my-widget'+idcpt+'"><header><p style="cursor: move; background: #DDDDDD;" >|||</p><div class="dragDiv" contenteditable="true">SousProject<div id="loadbuttonsous" class="load">+</div><div id="deletebuttonsous" class="delete">*</div><button id="create-user'+idcpt+'" style = "height : 7px; width: 7px;"></button></div></header><div id="divtestsous" style="overflow:auto;"></div></div></li>', 1, 1]);
                    $("#my-widget"+idcpt).colorize();
                    /**************************/
                    
                    
                    popup(idcpt);
                    
					  $.ajax({
						type: "POST",
						url: 'trait.php',
						data: { action : action , board : board },
						success: function(data)
						{
							//alert("Project created!");
						}
					});
				});
				
				$('.gridster li').on('mousedown', mouseDownHandler).on('mouseup', mouseUpHandler );
			});
            
                      

			function mouseDownHandler(event)
			{
				event = event || window.event; // IE-ism

				/** save start position to see if we dragged **/
				startPosition = {
					x: event.clientX,
					y: event.clientY
				};
			}
			function mouseUpHandler(event)
			{
				event = event || window.event; // IE-ism
				
				/** get drop position to see if we dragged and where we dropped it **/
				var dropPosition = {
					x: event.clientX,
					y: event.clientY
				};
				
				/** the element we clicked or dragged on **/
				var liElement = $(this);
				/** the gridster of the element we clicked on **/
				var currentGridster = liElement.closest('.gridster');
				/** the gridster object of the element we clicked on **/
				var gridsterObject = getGridsterObjectById(currentGridster.attr('id'));
				
				/** check if we dragged **/
				if( startPosition.x == dropPosition.x && startPosition.y == dropPosition.y ) {
					return true;
				}
				
				/** loop through all gridsters to check if we dropped the element in here **/
				$('.gridster').each(function() {
					var offset = $(this).offset();
					
					/** check if element is dropped in the current gridster **/
					if( 
						dropPosition.x > offset.left && 
						dropPosition.x < ( offset.left+$(this).width() ) &&
						dropPosition.y > offset.top && 
						dropPosition.y < ( offset.top+$(this).height() ) &&
						$(this).attr('id') != currentGridster.attr('id')
					) {
						/** get the new gridster object to put the element in **/
						newGridsterObject = getGridsterObjectById($(this).attr('id'));
						
						/** get the HTML of the liElement **/
						var newLiElement = liElement.clone().removeAttr('style').wrap('<p>').parent().html();
						
						/** add the listeners on the new element **/
						$(newLiElement).on('mousedown', mouseDownHandler).on('mouseup', mouseUpHandler );
						
						/** add the liElement widget **/
						newGridsterObject.add_widget(newLiElement);
						
						/** remove the old widget **/
						gridsterObject.remove_widget(liElement);
					}
				});
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
		//Show and Hide Boards
			$(document).ready(function(){
                $("#duplicater2").hide();
                
				$("#btnshowhide").click(function(){
					$("#showorHideBoards").toggle();
				});
                $("#btnshowhideTravail").click(function(){
					$("#showorHideTravail").toggle();
				});
                $("#btnshowhideSurveille").click(function(){
					$("#showorHideSurveille").toggle();
				});
			});
		</script>
        
        <script>
            //load data from files
            $(document).ready(function(){
                $("#loadbutton").click(function(){
                    $("#divtest").load("gtxml.xml");
                });
            });
            //delete data from files
            $(document).ready(function(){
                $("#deletebutton").click(function(){
                    document.getElementById("divtest").innerHTML = "";
                });
            });
        </script>
         <script>
            //load data from files
            $(document).ready(function(){
                $("#loadbuttonsous").click(function(){
                    $("#divtestsous").load("gtxml2.xml");
                });
            });
            //delete data from files
            $(document).ready(function(){
                $("#deletebuttonsous").click(function(){
                    document.getElementById("divtestsous").innerHTML = "";
                });
            });
        </script>
        
		<!-- sources pour le changement des couleurs-->
		<script src="assets/changeColorAssets/jquery-ui.js"></script>
		<!-- script changement de couleurs -->
		<style>
          .custom-colorize {
            font-size: 7px;
            position: relative;
            width: 100%;
            height: 100%;
          }
          .custom-colorize-changer {
            font-size: 10px;
            position: absolute;
            height : 6px;
            width : 6px;
            left: 1px;
            bottom: 1px;
            background-color : black;

          }
            
        </style>
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
                red: 255,
                green: 0,
                blue: 0,

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

                this.changer = $( "<button>", {
                  text: "",
                  "class": "custom-colorize-changer"
                })
                .appendTo( this.element )
                .button();
 
                // bind click events on the changer button to the random method
                this._on( this.changer, {
                  // _on won't call random when widget is disabled
                  click: "random"
                });
                this._refresh();
              },

              // called when created, and later when changing options
              _refresh: function() {
                this.element.css( "background-color", "rgb(" +
                  this.options.red +"," +
                  this.options.green + "," +
                  this.options.blue + ")"
                );

                // trigger a callback/event
                this._trigger( "change" );
              },

              // a public method to change the color to a random value
              // can be called directly via .colorize( "random" )
              random: function( event ) {
                var colors = {
                  red: i*250,
                  blue: j*250,
                  green: (1-k)*250
                  };
                  
                   $.ajax({
                    type :"POST",
                    url : "trait.php",
                    data: { i : i , j : j , k : k },
						success: function(data)
						{
							//alert("Color change the new one is : "+i+""+j+""+k);
						} 
                   });

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
                this._refresh();
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
 
            // initialize with default options
            $( "#my-widget1" ).colorize();

            // initialize with two customized options
            $( "#my-widget2" ).colorize();

            // initialize with custom green value
            // and a random callback to allow only colors with enough green
            $( "#my-widget3" ).colorize();
 
            // click to toggle enabled/disabled
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
    <style>
        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
    </style>
    <script>
        popup(-1);
        function popup(idcpt) {
          $(function(){
            var dialog, form,
              titre = $( "#titre" ),
              description = $( "#description" ),
              echeance = $( "#echeance" ),
              commentaire = $( "#commentaire" ),
              allFields = $( [] ).add( titre ).add( description ).add( echeance ).add( commentaire ),
              tips = $( ".validateTips" );

            function updateTips( t ) {
              tips
                .text( t )
                .addClass( "ui-state-highlight" );
              setTimeout(function() {
                tips.removeClass( "ui-state-highlight", 1500 );
              }, 500 );
            }
              
            function addUser() {
              var valid = true;
              allFields.removeClass( "ui-state-error" );
              dialog.dialog( "close" );
              return valid;
            }
 
            dialog = $( "#dialog-form" ).dialog({
              autoOpen: false,
              height: 400,
              width: 450,
              modal: true,
              buttons: {
                "Create task": function(){
                    $.ajax({
                    type :"POST",
                    url : "trait.php",
                    data: { titre : titre.val() , echeance : echeance.val() , commentaire : commentaire.val() , description : description.val() },
						success: function(data)
						{
							//alert("data sent to trait.php!");
						} 
                    });
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
              addUser();
             dialog.dialog( "close" ); 
            });

            $( "#create-user"+idcpt ).button().on( "click", function() {
              dialog.dialog( "open" );
            });
          });
          //datepicker
            $(function() {
            $( "#echeance" ).datepicker();
          });
        }
    </script>
    	
	</body>
	</html>
        <?php
        function afficherProjet($projet){
        	?>
        	<li data-row=<?php echo $projet['data-row']?> data-col=<?php echo $projet['data-col']?> data-sizex=<?php echo $projet['data-sizex']?> data-sizey=<?php echo $projet['data-sizey']?> class="gs-w scrollable-menu">
        	 	<div id = "my-widget'+idcpt+'">
        			<header>
        				<p style="cursor: move; background: #DDDDDD;" >|||</p>
        				<div class="dragDiv" contenteditable="true">
        					<?php echo $projet['nom']?>
        					<div id="loadbuttonsous" class="load">+</div>
        					<div id="deletebuttonsous" class="delete">*</div>
        					<button id="create-user'+idcpt+'" style = "height : 7px; width: 7px;"></button>
        				</div>
        			</header>
        			<div id="divtestsous" style="overflow:auto;"></div>
        		</div>
        		<ul style="background: #DDDDDD;">
        			<?php 
        			foreach($projet->projet as $sprojet){
        				afficherProjet($sprojet);
        			}?>	
        		</ul>
        	</li><?php 
        }
$boards = new SimpleXMLElement('input.xml',0,true);
//file_put_contents('result_file_create.txt', $action);
//$projet = $boards->board[0]->addChild('projet','New');
//file_put_contents('gtxml.xml', $boards->asXML());
//$dom = new DOMDocument("1.0");
//$dom->preserveWhiteSpace = false;
//$dom->formatOutput = true;
//$dom->loadXML($boards->asXML());
//file_put_contents('result_file_toto.txt', $action);
//file_put_contents('bbb.xml', $boards->asXML());
//file_put_contents('gtxml.xml', $dom->saveXML());
?>