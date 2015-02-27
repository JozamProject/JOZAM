<?php
session_start();
if($_GET["state"] == "ok"){
    $_SESSION['connect'] =true;
}
if(isset($_SESSION['connect'])){
    if($_SESSION['connect'] == true){
?>
<?php
function afficherTache($tache){
    ?>
<div id="create-user" value="<?php echo $tache['id'];?>" onclick="popup()">
	<abbr
		title="Description :<?php echo $tache->description . "\r\n";?>Comment :<?php echo $tache->commentaire;?>"> <?php echo "- ".$tache['titre'] . " || " .$tache['echeance'] ; ?> </abbr>
</div>
</br>
<?php
}
?>
<?php 
function afficherProjet($projet){
        	?>
<li id="<?php echo $projet['id']?>"
	data-row="<?php echo $projet['data-row']?>"
	data-col="<?php echo $projet['data-col']?>"
	data-sizex="<?php echo $projet['data-sizex']?>"
	data-sizey="<?php echo $projet['data-sizey']?>"
	class="gs-w scrollable-menu">
	<div id="<?php echo "my-widget".$projet['id'];?>" value="<?php echo $projet['id'];?>" onload="ch(this.id)">
		<header>
			<p style="cursor: move; background: grey;">|||</p>
			<div class="dragDiv" contenteditable="true">
        					<?php echo $projet['nom']?>
        					<div id="<?php echo $projet['id'];?>" onclick="createproj(this.id)" class="load">+</div>
				<div id="<?php echo $projet['id'];?>" class="delete" onclick="deleteproj(this.id)">x</div>

				<button id="create-user" value="<?php echo $projet['id'];?>"
					style="height: 7px; width: 7px;" onclick="popup()"></button>
			</div>
		</header>
		<div style="text-align: left; margin-left: 10%; overflow: auto;"> 
                        <?php 
                        foreach($projet->tache as $stache){
                            afficherTache($stache);
                        }?>
                    </div>
	</div>
	<ul style="background: #DDDDDD;">
        			<?php 
        			foreach($projet->projet as $sprojet){
        				afficherProjet($sprojet);
        				//popup();
        			}?>	
        		</ul>
</li><?php 
        }
       ?>
<?php 
    $languages = new SimpleXMLElement('Languages.xml',0,true);
    $boards = new SimpleXMLElement('input.xml',0,true);
    foreach($languages->Language as $l){
        if($l['chosen']=="true"){
            $lstring = str_replace(' ', '', $l);
            $language =  new SimpleXMLElement($lstring,0,true);

        }
    }?>
<!DOCTYPE html>
<html charset="UTF-8">
<head>
<title><?php echo $language->Title ?></title>
<meta name="author" content="gyurisc">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css"
	href="assets/css/jquery.gridster.css">
<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
<link rel="stylesheet" type="text/css" href="assets/css/boardsStyle.css">
<link rel="stylesheet"
	href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
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
					<button type="button" data-target="#navbarCollapse"
						data-toggle="collapse" class="navbar-toggle">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a href="#" class="navbar-brand"><?php echo $language->Project ?></a>
				</div>
				<!-- Collection of nav links and other content for toggling -->
				<div id="navbarCollapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a id="addBoard" href="#"><?php echo $language->AddBoard ?></a></li>
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $language->Toggle ?> <span
								class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
                                    <?php 
                                        foreach($boards->board as $board){?>                                        
                                            <li><button id="btnshowhide"
										value="<?php echo $board['id'];?>" href="#"
										style="width: 100%; background: none;"><?php echo $board['nom'];?></button></li>
                                        <?php } ?>
                                    <!--<li><a id="btnshowhide" href="#">All</a></li>
                                    <li><a id="btnshowhideTravail" href="#">Travail</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#" id="btnshowhideSurveille">Surveille</a></li>-->
							</ul></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo $language->Abb;?>
                                    <span class="caret"></span>
						    </a>
							<ul class="dropdown-menu" role="menu">
                                    <?php 
                                        //$languages = new SimpleXMLElement('Languages.xml',0,true);
                                        
			                            foreach($languages->Language as $l){ 
                                        if($l['chosen']=="true")
                                            $chosen = 'v ';
                                        else $chosen = " ";
                                    ?>
                                    <li href="#"><button
										id="<?php echo $l['name'];?>"
										style="width: 100%; background: none;"
										onclick="change(this.id)"><?php echo $chosen.$l['name'];?></button></li>  
                                    <?php } ?>
                                </ul>
                        </li>
                        <li>
                            <a href="logout.php" id="disconnect"><?php echo $language->Disconnect; ?></a>
                        </li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<div class="navbar" style="margin-top: 50px;">
		<div class="container" style="margin : 5px !important;">
		<?php 
			
			foreach($boards->board as $board){
				?> 
							
					<div id="<?php echo "showorHide".$board['id'];?>">
				<div id="<?php echo "duplicater".$board['id'];?>">
					<div id="<?php echo "demo-".$board['id'];?>" class="gridster">
						<div contenteditable="true">
							<h2><?php echo $board['nom'];?></h2>
						</div>
						<button type="button" id="addWidgetButton"
							value="<?php echo $board['id'];?>" class="btn btn-default btn-sm"><?php echo $language->AddProject ?></button>
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
                /*"use strict";
				var original = document.getElementById('duplicater' + i);
				var clone = original.cloneNode(true); // "deep" clone
                //i += i;
				clone.id = "duplicetor" + ++i; // there can only be one element with an ID
				//clone.onclick = duplicate; // event handlers are not cloned
				original.parentNode.appendChild(clone);
                //show the hiden board or the duplicated one
                document.getElementById('duplicater2').style.display = 'block';
				--i;// -= i;*/
				var action = "CreationBoard";
				$.ajax({
					type : "POST",
					url  : "trait.php",
					data : { action : action }
				});
                alert("board created!!");
                window.location.reload();
			}
			document.getElementById('addBoard').onclick = duplicate;
			
		
			/*	
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
		*/
			var gridster = [];
			var startPosition = {};
            //max board number
			var taille = 20;
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
							enabled: true,
                            //Retrieve new dimensions of prjects----------------
                            stop: function (e, ui, $widget) {
                                var newDimensions = this.serialize($widget)[0];
                                var action = "ResizeProject";
                                //alert("widget is:" + $widget.attr('id'));
                                //alert("New width: " + newDimensions.size_x);
                                //alert("New height: " + newDimensions.size_y);
                                $.ajax({
                                    type : "POST",
                                    url : "trait.php",
                                    data : { action : action , idProj : $widget.attr('id') , NewWidth : newDimensions.size_x , NewHeight : newDimensions.size_y}
                                });
                            }
						},
                        draggable: {
                            handle: 'header p'                        
                        }
					}).data('gridster');
				}
                				
				var action = "CreationProjet";
                
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
                     var idBoard = me.val();
                     gridster[me.val()].add_widget.apply(gridster[me.val()], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: #E8AC71;"><div id = "my-widget'+idcpt+'"><header><p style="cursor: move; background: grey;" >|||</p><div class="dragDiv" contenteditable="true">New project<div id="loadbutton" class="load">+</div><div id="deletebutton" class="delete">*</div><button id="create-user" value="'+me.val()+'" style = "height : 7px; width: 7px;"></button></div></header></div></li>', 1, 1]);
                    $("#my-widget"+idcpt).colorize();
                    /**************************/
                  
                    popup();
                    
                    
                    
                    
                    /******************************/
					  $.ajax({
						type: "POST",
						url: 'trait.php',
						data: { action : action , idBoard : idBoard },
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
                //send the new position of the gridster
				if( startPosition.x != dropPosition.x && startPosition.y != dropPosition.y ) {
                    var action = "MoveProject";
                    alert(action+" "+liElement.attr('id')+" "+Math.floor((dropPosition.x)/100)+" "+ Math.floor((dropPosition.y)/100));
                     $.ajax({
                         type : "POST",
                         url : "trait.php",
                         data : { action : action , id_prj : liElement.attr('id'), data_row_x : Math.floor((dropPosition.x)/100) , data_row_y : Math.floor((dropPosition.y)/100) }
                     });
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
           
                var idPrj;
                $(document).on( "click", "#btnshowhide", function() {
                        var m = $(this);
                        idPrj = m.val();
                        $("#showorHide"+idPrj).toggle();
                });
            
		</script>

	<script>
            //load data from files
            function createproj(createdproj){
                var action = "CreationProjet";
                alert("create project"+createdproj);
                $.ajax({
					type : "POST",
					url  : "trait.php",
					data : { action : action , createdproj : createdproj }
				});
                //alert("Langage chosen!!");
                window.location.reload();               
            }
            //delete data from files
        function deleteproj(deletedproj){
                alert("delete project"+deletedproj);
                $.ajax({
					type : "POST",
					url  : "trait.php",
					data : { deletedproj : deletedproj }
				});
                //alert("Langage chosen!!");
                window.location.reload();               
            }
        </script>

	<!-- sources pour le changement des couleurs-->
	<script src="assets/changeColorAssets/jquery-ui.js"></script>
	<!-- script changement de couleurs -->
	<style>
.custom-colorize {
	color : white;
	position: relative;
	width: 100%;
	height: 100%;
}

.custom-colorize-changer {
	font-size: 10px;
	position: absolute;
	height: 6px;
	width: 6px;
	left: 1px;
	bottom: 1px;
	background-color: black;
}
</style>
	<script>
        
        //var map = {82: false, 84: false};
        //window.onkeydown = keydown;
        //window.onkeyup = keyup;
        var map = {18: false, 84: false};
    	function keydown(e) {
        	if (e.keyCode in map) {
            	map[e.keyCode] = true;
            	if (map[18] && map[84]) {
            		action = "Toggle";
        			$.ajax({
    					type: "POST",
    					url: 'trait.php',
    					data: { action : action },
    					success: function(data)
    					{
    		    			window.location.reload();
    					}
    				});
            	}
        	}
    	}

    	function keyup(e) {
        	if (e.keyCode in map) {
            	map[e.keyCode] = false;
        	}
    	}
    	window.addEventListener('keyup', keyup);
		window.addEventListener('keydown', keydown);

       	/* var map = []; 
		onkeydown = onkeyup = function(e){
    		e = e || event; // to deal with IE
    		map[e.keyCode] = e.type == 'keydown';
    		if(map[18] && map[84]){ 
    			action = "Toggle";
    			$.ajax({
					type: "POST",
					url: 'trait.php',
					data: { action : action },
					success: function(data)
					{
		    			window.location.reload();
					}
				});
    			//map[18] = false;
        		//map[84] = false;
    		}
		} */
        </script>
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
              
            //$( "#my-widget" ).colorize();
              for(var j=0;j<100;j++){
                  for(var i=0;i<100;i++){
                    $( "#my-widget"+j+"-"+i ).colorize();
                  }
              }

 
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
	<script>
        popup();
        function popup() {
            
          $(function(){
        	var idProjet;
            var dialog, form,
              action = "CreationTache",
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
                    data: { action : action, idProjet : idProjet, titre : titre.val() , echeance : echeance.val() , commentaire : commentaire.val() , description : description.val() },
						success: function(data)
						{
							//alert("data sent to trait.php!");
						} 
                    });
                    dialog.dialog( "close" );
                    window.location.reload(); 
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
            $(document).on( "click", "#create-user", function() {
              var me = $(this);
              idProjet = me.val();
              //alert(me.val());
              dialog.dialog( "open" );
            });
          });
          //datepicker
            $(function() {
            $( "#echeance" ).datepicker();
          });
        }
    </script>
	<script>
            //sending the langage chose !!
            function change(clicked_lang){
                //alert(clicked_lang);
                $.ajax({
					type : "POST",
					url  : "languageChanger.php",
					data : { clicked_lang : clicked_lang }
				});
                //alert("Langage chosen!!");
                window.location.reload();               
            }
        </script>

	<div id="dialog-form" title="Create task">

		<form>
			<fieldset>
				<label for="titre">Titre</label> <input type="text" name="titre"
					id="titre" class="text ui-widget-content ui-corner-all"> <label
					for="description">Description</label> <input type="text"
					name="description" id="description"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<label for="echeance">Echeance</label> <input type="datepicker"
					name="echeance" id="echeance"
					class="text ui-widget-content ui-corner-all"> <label
					for="commentaire">commentaire</label> <input type="text"
					name="commentaire" id="commentaire"
					class="text ui-widget-content ui-corner-all" style="height: 70px;">
				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1"
					style="position: absolute; top: -1000px">
			</fieldset>
		</form>
	</div>

</body>
</html>
<?php
        
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

<?php } } ?>