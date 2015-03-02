<?php
session_start();
//Test the variable session for connection
if(isset($_GET["state"])){
    if($_GET["state"] == "ok"){
        $_SESSION['connect'] =true;
    }
}

if(isset($_SESSION['connect'])){
    if($_SESSION['connect'] == true){
?>

<?php
        //Function to display tasks in each project
function showTask($tache){
    ?>
    <div id="modify-task" value="<?php echo $tache['id'];?>" onclick="popupmodify()">
        <abbr title="Description :<?php echo $tache->description . "\r\n";?>Comment :<?php echo $tache->commentaire . "\r\n";?>"> 
            <?php echo "- ".$tache['titre'] . " || " .$tache['echeance'] ; ?> 
        </abbr>
    </div>
</br>
<?php
    }
?>

<?php 
        //function to load projects from XML dynamically and display them on boards
function showProject($project){
        	?>
    <li id="<?php echo $project['id']?>" data-row="<?php echo $project['data-row']?>" data-col="<?php echo $project['data-col']?>" data-sizex="<?php echo $project['data-sizex']?>" data-sizey="<?php echo $project['data-sizey']?>" class="gs-w scrollable-menu">
        <div id="<?php echo "my-widget".$project['id'];?>" value="<?php echo $project['id'];?>" onload="ch(this.id)" style="overflow: auto;">
            <header>
                <p style="cursor: move; background: grey;">|||</p>
                <div class="dragDiv" contenteditable="true">
                    <?php echo $project['nom']?>
                    <div id="<?php echo $project['id'];?>" class="delete" onclick="deleteproj(this.id)">
                        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
                    </div>
                    <div id="<?php echo $project['id'];?>" onclick="createproj(this.id)" class="load">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                    </div>
                    <button id="create-user" value="<?php echo $project['id'];?>" style="background : none; border : none;" class="loadtask" onclick="popup()">
                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                    </button>
                </div>
            </header>
            <div style="text-align: left; margin-left: 10%;"> 
                <?php 
                    //Iteretor to fetch tasks from XML and display them for each project
                    foreach($project->tache as $stache){
                        showTask($stache);
                        }?>
                <ul style="background: #DDDDDD;">
                     <?php 
                        //Iteretor to fetch sub projects and display the recurssevilly
                        foreach($project->projet as $sproject){
                            showProject($sproject);
                            }?>	
                </ul>
            </div>
        </div>
    </li>
<?php 
        }
?>
<?php 
    //Load the language of JOZAM
    $languages = new SimpleXMLElement('Languages.xml',0,true);
    $boards = new SimpleXMLElement('input.xml',0,true);
    foreach($languages->Language as $l){
        if($l['chosen']=="true"){
            $lstring = str_replace(' ', '', $l);
            $language =  new SimpleXMLElement($lstring,0,true);

        }
    }
?>
<!-- Startin the HTML document -->
<!DOCTYPE html>
<html charset="UTF-8">
    <head>
        <!-- SRC needd Bootstrap , Ajax, Jquery et les CSS -->
        <title><?php echo $language->Title ?></title>
		<link rel="shortcut icon" href="assets/css/JOZAM_Logo.png" type="image/png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="assets/css/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
        <link rel="stylesheet" type="text/css" href="assets/css/boardsStyle.css">
        <link rel="stylesheet" href="assets/css/bootstrap.netdna.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <script src="assets/jquery.min.js"></script>
        <script src="assets/css/bootstrap.min.js"></script>
        <link rel="stylesheet" href="assets/changeColorAssets/jquery-ui.css">
        <link rel="stylesheet" href="popup/jquery-ui.css">
        <script src="popup/jquery-ui.js"></script>
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
						<a href="#" class="navbar-brand"><img src="assets/css/JOZAM_Logo.png" style="width:40px; height: 40px; margin-top:-10px;" /></a>
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
                                    <!-- Adding elements dynamically to the navigaftion bar "Toggle"-->
                                        <?php 
                                            foreach($boards->board as $board){?>                                        
                                                <li><button id="btnshowhide"
                                            value="<?php echo $board['id'];?>" href="#"
                                            style="width: 100%; background: none;"><?php echo $board['nom'];?></button></li>
                                            <?php } ?>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <?php echo $language->Abb;?>
                                        <span class="caret"></span>
                                </a>
                                <!-- choosing the language -->
                                <ul class="dropdown-menu" role="menu">
                                        <?php 
                                            foreach($languages->Language as $l){ 
                                            if($l['chosen']=="true")
                                                $chosen = 'v ';
                                            else $chosen = " ";
                                        ?>
                                    <li href="#">
                                        <button id="<?php echo $l['name'];?>" style="width: 100%; background: none;" onclick="change(this.id)">
                                            <?php echo $chosen.$l['name'];?>
                                        </button>
                                    </li>  
                                        <?php } ?>
                                </ul>
                            </li>
                            <!-- disconnect -->
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
            <!--Charge and display boards on the main menu-->
            <?php 
                foreach($boards->board as $board)
                {
            ?> 
                <div id="<?php echo "showorHide".$board['id'];?>">
                    <div id="<?php echo "duplicater".$board['id'];?>">
                        <div id="<?php echo "demo-".$board['id'];?>" class="gridster">
                            <div contenteditable="true">
                                <h2>
                                    <?php echo $board['nom'];?>
                                </h2>
                            </div>
                            <button type="button" id="addWidgetButton" value="<?php echo $board['id'];?>" class="btn btn-default btn-sm">
                                <?php echo $language->AddProject ?>
                            </button>
                            <button type="button" id="deleteWidgetButton" value="<?php echo $board['id'];?>" class="btn btn-default btn-sm">
                                <?php echo $language->DeleteProject ?>
                            </button>
                            <ul id="myList">
                                <!--Display projects in this board-->
                                    <?php 
                                        foreach($board->projet as $project){
                                            showProject($project);
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
        <script src="assets/jquery-1.11.2.js"></script>
        <script src="assets/jquery.gridster.min.js" charster="utf-8"></script>
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
                data : { action : action , idBoard : idBoard }
            });
            window.location.reload();
		});
        </script>
        <script>
                //Creating new board ans send action to trait.php to save it in input XML             
                var i = 2;
                var idcpt = 0;
                function duplicate() {
                    var action = "CreationBoard";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action }
                    });
                    window.location.reload();
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
                            widget_margins: [5, 5],
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
                                        data : { action : action , action1 : action1 , idProj : $widget.attr('id') , NewWidth : newDimensions.size_x , NewHeight : newDimensions.size_y}
                                    });
                                }
                            },
                            draggable: {
                                handle: 'header p'                        
                            }
                        }).data('gridster');
                    }

                    //The handler of creating new project inside the gridster
                    var action = "CreationProjet";

                    $(document).on( "click", "#addWidgetButton", function(e) {
                         e.preventDefault(); 
                         idcpt++;
                         var me = $(this);
                         var idParent = me.val();
                         gridster[me.val()].add_widget.apply(gridster[me.val()], ['<li data-row="1" data-col="1" data-sizex="2" data-sizey="1" style="background: #E8AC71;"><div id = "my-widget'+idcpt+'"><header><p style="cursor: move; background: grey;" >|||</p><div class="dragDiv" contenteditable="true">New project<div id="loadbutton" class="load">+</div><div id="deletebutton" class="delete">x</div><button id="create-user" value="'+me.val()+'" style = "height : 7px; width: 7px;"></button></div></header></div></li>', 2, 1]);
                        //activate the color change on the new project
                        $("#my-widget"+idcpt).colorize();
                        //activate the tast creator on the new project
                        popup();
                        //send the new project created to trait.php to save it in the input xml
                          $.ajax({
                            type: "POST",
                            url: 'trait.php',
                            data: { action : action , idParent : idParent },
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
                             data : { action : action , action1 : action1 , idProj : liElement.attr('id'), NewCol : (parseInt(Math.round((dropPosition.x-startPosition.x)/100))+parseInt(liElement.attr('data-col'))) , NewRow : (parseInt(Math.round((dropPosition.y-startPosition.y)/100))+parseInt(liElement.attr('data-row'))) }
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
                function createproj(createdproj){
                    var action = "CreationProjet";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action , idParent: createdproj }
                    });
                    window.location.reload();               
                }
                //function to send the deleted project to MAJ it on XML
                function deleteproj(idProj){
                 if (confirm("Confirm delete ?")) { // Clic sur OK
                    var action = "DeleteProject";
                    $.ajax({
                        type : "POST",
                        url  : "trait.php",
                        data : { action : action, idProj : idProj }
                    });
                    window.location.reload(); 
                    window.location.reload(); 
                 }
                }
            </script>
        <!-- src script jquery to change color-->
        <script src="assets/changeColorAssets/jquery-ui.js"></script>
        <!-- style for color changer -->
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
                    var action = "ChangeColor";
                      //send the color of the project
                      //alert(action);
                       $.ajax({
                        type :"POST",
                        url : "trait.php",
                        data: { action : action, i : i , j : j , k : k },
                            success: function(data)
                            {
                                //alert("Color change the new one is : "+i+""+j+""+k);
                            } 
                       });
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
                  //not used
                function addUser() {
                  var valid = true;
                  allFields.removeClass( "ui-state-error" );
                  dialog.dialog( "close" );
                  return valid;
                }
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
                  //on click button activate the dialog form
                $(document).on( "click", "#create-user", function() {
                  var me = $(this);
                  idProjet = me.val();
                  dialog.dialog( "open" );
                });
              });
              //datepicker to add calendar to deadline
                $(function() {
                    $( "#echeance" ).datepicker();
              });
            }
        </script>
        <!-- script of task modifyer -->
        <script>
            //popupmodify() modify a task
            function popupmodify(){
                $(document).on( "click", "#modify-task", function() {
                    /**** To implement here ****/
                    alert("not implemented yet !!");

                });

            }
        
        </script>
        
        <!-- Script of Switcher Alt + t  : change order of boards-->
        <script>
            var map = {18: false, 84: false};
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
                                window.location.reload();
                            }
                        });
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
        <!-- Change Language Script -->
        <script>
                //sending the langage chose !!
                function change(clicked_lang){
                    //send the chosen language to php file
                    $.ajax({
                        type : "POST",
                        url  : "languageChanger.php",
                        data : { clicked_lang : clicked_lang }
                    });
                    window.location.reload();               
                }
        </script>
        <!-- the Form of the popup -->
        <div id="dialog-form" title="Create task">
            <form>
                <fieldset>
                    <label for="titre">
                        Title
                    </label> 
                    <input type="text" name="titre" id="titre" class="text ui-widget-content ui-corner-all"> 
                    <label for="description">
                        Description
                    </label> 
                    <input type="text" name="description" id="description" class="text ui-widget-content ui-corner-all" style="height: 70px;">
                    <label for="echeance">
                        Deadline
                    </label> 
                    <input type="datepicker" name="echeance" id="echeance" class="text ui-widget-content ui-corner-all"> 
                    <label for="commentaire">
                        comment
                    </label> 
                    <input type="text" name="commentaire" id="commentaire" class="text ui-widget-content ui-corner-all" style="height: 70px;">
                    <input type="submit" tabindex="-1" style="position: absolute; top: -1000px">
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