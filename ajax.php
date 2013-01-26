<?php
error_reporting(E_ALL ^ E_NOTICE);
$user = $_GET[user_id];
$page = $_GET[page];

$dir = "/";
include "configuration.php";
$asd = new JConfig;
$c = mysql_connect($asd->host, $asd->user, $asd->password) or die("no db connect");
$prefix = $asd->dbprefix;
mysql_select_db($asd->db);


if ($page == "load" and $_GET[event_id]) {
    //check if event already have a constructor
    $qcnt = mysql_query("select count(*) as cnt from `".$prefix."event_autoconfigurator` where event_id =" . $_GET[event_id]);
    if (mysql_result($qcnt, 0) > 0) {
        $page = 3;
        $event_id = $_GET[event_id];
    } else {
      
      $page = 1;
    }
   
}
if($page == 1 or $page == 3) {
    ?>
<link rel="stylesheet" href="form_validator/css/validationEngine.jquery.css" type="text/css"/>
<script src="form_validator/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="form_validator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="jquery.maskedinput-1.2.2.js"></script>
  <script src="form_validator/jquery-confirm.js" type="text/javascript"></script>
  <script>
     var j5 = jQuery.noConflict();    
     j5(document).ready(function(){
         j5("#workarea *").prop("disabled", true);
	         //confirmation autopopulate
			j5("#pup").easyconfirm({locale: { title: 'Select Yes or No', button: ['No','Yes']}});
			j5("#pup").click(function() {
			
				            var v = j5("#event_id option:selected").val();                            
                            j5.ajax({
                                   type: "POST",
                                    url: "ajax.php?page=13&event_id=" +v,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                          	
                                        alert(html);          
                                            
                                    }
                                });

			});
			
			
			
			j5("#selfscore").change(function() { 
				var ss = j5("#selfscore");
				 j5.ajax({
                                   type: "POST",
                                    url: "ajax.php?page=16&event_id=<?=$_GET[event_id]?>&data=" + ss.is(':checked'),
                                    
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                    	//alert(html)
                                          
                                            
                                    }
                                }); 
			});
	AnyTime.picker( "field1",
  					  { format: "%M %D, %Y", firstDOW: 1 } );
					
  					  
  	    j5("#field1").blur(function() { 
  	    var c = j5("#field1").val();
                            j5.ajax({
                                   type: "POST",
                                    url: "ajax.php?page=14&event_id=<?=$_GET[event_id]?>&dates=" + c,
                                    
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                    //	alert(html)
                                    	j5("#saved").text(" saved!");         
                                    	j5("#saved").fadeIn(10);         
                                       	j5("#saved").fadeOut(1000);         
                                            
                                    }
                                });  	   
  	     }); 
  
        j5("#unlk").click(function() {
         
         j5("#workarea *").prop("disabled", false);
         j5("#information").text(" ");
         });
        
         j5("#sv").click(function() {
              if (j5("#frm").validationEngine('validate') == true)   {
                var datas = j5('#frm').serialize();
                  
                        var sum = 0;
                        j5('.zzz').each(function() {
                            sum += Number(j5(this).val());
                            
                        });

 if(sum != 100)
    {
        alert("The sum of percentage fields should be equal to 100. Currect value is " + sum);
 
        
    }
    else {
                      
                  
                    // Отсылаем паметры
                           j5.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=2",
                                    data: datas,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                      var v = j5("#event_id option:selected").val();
                                      AnyTime.noPicker("field1");
                                    
                                      j5("#wa").load("<?=$dir?>ajax.php?page=load&event_id=" + v);    
                                            
                                    }
                            });
                    }//sum
              }
         });
     });
     
      
   
     function delid(x)
     {
        
        var r   =         document.getElementById('pwod' + x);
        r.innerHTML = '<h3>WOD #'+x+' is deleted! </h3>';
       
     
 }

        
    </script>
    <?  
    	$aaa = mysql_query("select avl  from ".$prefix."event_avl where event_id = ".$_GET[event_id]."");
				$rn = mysql_num_rows($aaa);
				if($rn > 0)
				{
				while($aaa1 = mysql_fetch_array($aaa)) { $cn = $aaa1[avl]; }
				$date = DateTime::createFromFormat('Y-m-d', $cn);
				$dat = $date->format('F jS, Y');
				}
				if($rn < 1)
				{
					$dat = date('F jS, Y',time());
				}
	?>		
    
<div>    Leaderboard visible to public on: <input name="field1" type="text" id="field1" size="50"  value="<?=$dat?>" /> 
	<span id="saved" style="color: green; ">  </span></div>
	
	<? $ss = mysql_query("Select distinct selfscore as ss from ".$prefix."event_autoconfigurator where event_id = ".$_GET[event_id].""); 
		$ss_ch = " ";
		while($ss1 = mysql_fetch_array($ss))
		{
			if($ss1[ss] == 1) { $ss_ch = " checked "; }
		}
		
		?>
<div><input  type="checkbox" value="" name="selfscore" id="selfscore" <?php print $ss_ch; ?>  > Allow athlete to self-score </div>


<?
}


if ($page == 1) {
    ?>

    <div id="ag" name="ag"> Categories: <input type="text" value="All" id="ag_input" name="ag_input"><i><sub>(example: Men Rx, Men Scaled, Women Rx, Women Scaled)</sub></i></div>


    <div id="pwod1" name="pwod1"><h3>WOD #1</h3>
        <input class="validate[required] radio" type="radio" value="weight" name="WOD1" id="WOD1"> Weight 
        <input class="validate[required] radio" type="radio" value="time" name="WOD1" id="WOD1"> Time 
        <input class="validate[required] radio" type="radio" value="reps" name="WOD1" id="WOD1"> Reps 
        <input class="validate[required] radio" type="radio" value="dist" name="WOD1" id="WOD1"> Distance 
        | Percent(%): <input size="3" type="text" value="0" class="validate[required,custom[number],min[0],max[100]] zzz" name="WOD1_percent" id="WOD1_percent">  
         Description<input type="text" class="validate[required] input-text" size="18" maxlength="15" name="WOD1_desc" id="WOD1_desc"> <br>
    </div>
    <div id="pwod2" name="pwod2"></div>
    <div id="pwod3" name="pwod3"></div>
    <div id="pwod4" name="pwod4"></div>
    <div id="pwod5" name="pwod5"></div>
    <div id="pwod6" name="pwod6"></div>
    <div id="pwod7" name="pwod7"></div>
    <div id="pwod8" name="pwod8"></div>
    <div id="pwod9" name="pwod9"></div>
    <div id="pwod10" name="pwod10"></div>
    <div id="pwod11" name="pwod11"></div>
    <div id="pwod12" name="pwod12"></div>
    <div id="pwod13" name="pwod13"></div>
    <div id="pwod14" name="pwod214"></div>
    <div id="pwod15" name="pwod15"></div>
    <div id="pwod16" name="pwod16"></div>
    <div id="pwod17" name="pwod17"></div>
    <div id="pwod18" name="pwod18"></div>
    <div id="pwod19" name="pwod19"></div>
    <div id="pwod20" name="pwod20"></div>

    <input type="hidden" name="wod_cnt" id="wod_cnt" value="1">
    <input type="hidden" value="insert" name="action" id="action">

    <input type="button" value="Add  WOD" onclick="addmore()" style="height: 25px; width: 180px"> 

    <INPUT type="button" value="Save" id="sv" style="height: 25px; width: 60px">

    

    
    <?php
}


if ($page == 2) { // POST RESULTS
    $id = $_POST['event_id'];
    $ag = $_POST['ag_input'];
    $wodcnt = $_POST['wod_cnt'];
    $action = $_POST['action'];
//print "$id $ag $wodcnt $action";

    if ($action == "update") {
        //if action is update then we delete all related rows and insert then again
        mysql_query("delete from `".$prefix."event_ag` where event_id = " . $id);
        mysql_query("delete from `".$prefix."event_autoconfigurator` where event_id = " . $id);
        mysql_query("delete from ".$prefix."event_result
                    where 
                    athlete_id in (Select id from ".$prefix."event_athlete where event_id = ".$id.")");
        mysql_query("delete from ".$prefix."event_athlete where event_id = ".$id."");
        mysql_query("delete from ".$prefix."event_sev where master_event = ".$id."");
    }
$i2 = 1;
    for ($i = 1; $i <= $wodcnt; ++$i) {
        if($_POST['WOD' . $i])
        {
        $wod[$i2][type] = $_POST['WOD' . $i];
        $wod[$i2][percent] = $_POST['WOD' . $i . '_percent'];
        $wod[$i2][desc] = $_POST['WOD' . $i . '_desc'];
       ++$i2;
        }
       
    }

    if ($ag != "All" or $ag != "" or $ag != "no") {

        $ag1 = explode(",", $ag);
    }
//insert to database age groups    if avaliable 
    for ($i = 0; $i < count($ag1); ++$i) {
        $sql = "INSERT INTO  `".$prefix."event_ag` (
                                                                        `event_id` ,
                                                                        `ag` 
                                                                        )
                                                                        VALUES (
                                                                          '" . $id . "',  '" . $ag1[$i] . "'
                                                                        );";
        mysql_query($sql);
    }



    //insert WODs
    for ($i = 1; $i <= count($wod); ++$i) {
        $sql = "INSERT INTO `".$prefix."event_autoconfigurator` (

                                `event_id` ,
                                `type` ,
                                `weight`,
                                `desc`
                                )
                                VALUES (
                                 '" . $id . "',  '" . $wod[$i][type] . "',  '" . $wod[$i][percent] . "', '" . $wod[$i][desc] . "'
                                );  ";
        
        
        mysql_query($sql);
    }

    //insert sub-events
        foreach($_POST[sev] as $keys => $vals)
        {
            mysql_query("insert into ".$prefix."event_sev(master_event,sub_event) values ($id,$keys) ");
        }
    
   echo "Saved!";
}    //page==2     
if ($page == 3) {
    //page3 means there is a constructor for event
    //ag_groups
    $sql_ag = "Select ag from ".$prefix."event_ag where event_id = " . $_GET[event_id];
    $q_ag = mysql_query($sql_ag) or die("Incorrect query: " . $sql_ag);
    $ag_cnt = mysql_num_rows($q_ag);
    if ($ag_cnt > 0) {
        $ag_str = "";
        $i = 0;
        while ($row = mysql_fetch_array($q_ag)) {
            $ag_str .= $row[ag];
            if ($ag_cnt > $i + 1) {
                $ag_str .= ",";
                ++$i;
            }
        }
    } else {
        $ag_str = "All";
    }

    //wods
    $sql_wod = "Select type,weight, `desc` from `".$prefix."event_autoconfigurator` where event_id = " . $_GET[event_id];

    $q_wod = mysql_query($sql_wod);
    if (!$q_wod) {
        print "Incorrect query";
    }
    $wod[] = null;
    $i = 0;
    while ($row = mysql_fetch_array($q_wod)) {
        $wod[$i][type] = $row[type];
        $wod[$i][weight] = $row[weight];
        $wod[$i][desc] = $row[desc];
        ++$i;
    }


    //rendering
    ?>
    <div class="information" id="information" style="color: red"><b>WARNING: These two buttons will erase results!</b> 
        
        <input type="button" value="Unlock Form" id="unlk" style="height: 25px; width: 180px">  <b> OR </b> <input type="button" value="Populate event" id="pup" style="height: 25px; width: 180px"> <br></div>
   <div id="workarea">
   <div id="ag" name="ag"> Categories: <input type="text" value="<?= $ag_str ?>" id="ag_input" name="ag_input"><i><sub>(example: Men Rx, Men Scaled, Women Rx, Women Scaled ...)</sub></i></div>
  
   <div id="subev">
 Select sub-events: <br>
 <?
 $sql = "
Select s1.ohanah_event_id, s1.title, if(sev.sub_event is not null, 1, 0) as is_checked from ".$prefix."ohanah_events s1 left outer join ".$prefix."event_sev sev on (sev.sub_event = s1.ohanah_event_id and sev.master_event = ".$_GET[event_id].")
where
s1.created_by in (Select created_by from ".$prefix."ohanah_events where ohanah_event_id = ".$_GET[event_id].") and ohanah_event_id <> ".$_GET[event_id]."
and ohanah_event_id not in (select sub_event from ".$prefix."event_sev where master_event <> ".$_GET[event_id].")
and ohanah_event_id not in (select master_event from ".$prefix."event_sev)
";
 //print $sql;
 $qx = mysql_query($sql);
 while ($b = mysql_fetch_array($qx))
 {
     if($b[is_checked] == 1)
     {
         $ch = " checked ";
     }
     else 
     {
         $ch = " ";
     }
     print "<input $ch type=checkbox id=subevents name=\"sev[".$b[ohanah_event_id]."]\"> ".$b[title]." <br>";
 }
   
 
 ?>
  </div>

  <?php
    for ($i = 1; $i < 21; ++$i) {
        if ($i <= count($wod)) {
            $i2 = $i - 1;
            ?>

            <div id="pwod<?= $i ?>" name="pwod<?= $i ?>"><h3>WOD #<?= $i ?> <input type="button" id="delid<?=$i?>"  onclick="delid(<?=$i?>);" value="delete"></h3> 
                <input <? if ($wod[$i2][type] == "weight") {
                print "checked";
            } ?> class="validate[required] radio" type="radio" value="weight" name="WOD<?= $i ?>" id="WOD<?= $i ?>"> Weight 
                <input <? if ($wod[$i2][type] == "time") {
                print "checked";
            } ?> class="validate[required] radio" type="radio" value="time" name="WOD<?= $i ?>" id="WOD<?= $i ?>"> Time 
                <input <? if ($wod[$i2][type] == "reps") {
                print "checked";
            } ?> class="validate[required] radio" type="radio" value="reps" name="WOD<?= $i ?>" id="WOD<?= $i ?>"> Reps 
                <input <? if ($wod[$i2][type] == "dist") {
                print "checked";
            } ?> class="validate[required] radio" type="radio" value="dist" name="WOD<?= $i ?>" id="WOD<?= $i ?>"> Distance 
                | Percent(%): <input size="3" type="text" class="validate[required,custom[number],min[0],max[100]] zzz" value="<?= $wod[$i2][weight] ?>" name="WOD<?= $i ?>_percent" id="WOD<?= $i ?>_percent">  
                 Description <input type="text" class="validate[required] input-text" maxlength="15" size="18" name="WOD<?= $i ?>_desc" id="WOD<?= $i ?>_desc" value="<?= $wod[$i2][desc] ?>"> <br>
            </div>

        <?php } else { ?><div id="pwod<?= $i ?>" name="pwod<?= $i ?>"></div> <?
        }
    }
    ?><input type="hidden" name="wod_cnt" id="wod_cnt" value="<? print $i2 + 1; ?>">
    <input type="hidden" value="update" name="action" id="action">
    <input type="button" value="Add WOD" onclick="addmore()" style="height: 25px; width: 180px">
    <INPUT  type="button" value="Save" id="sv" style="height: 25px; width: 60px"> </div> <?
} // page==3
//RESULTS SECTION
//submit form




if ($page == 4) {

    if ($_GET[ag] == "All") {
        unset($_GET[ag]);
    }
    if ($_GET[ag] and $_GET[ag] != 'undefined') {
        $filter_ag = "  AND age_category = '" . $_GET[ag] . "'";
    } else {
        $filter_ag = "  ";
    }
    ?>
    
    
<link rel="stylesheet" href="form_validator/css/validationEngine.jquery.css" type="text/css"/>
<script src="form_validator/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="form_validator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="jquery.maskedinput-1.2.2.js"></script>
<script src="form_validator/jquery-confirm.js" type="text/javascript"></script>
   
   
    <script>
        var j2 = jQuery.noConflict();

		//search table function
		        function searchTable(inputVal) {
                var table = j2(".resulttable");
                table.find('tr').each(function(index, row) {
                	    var allCells = j2(row).find('td');
                        if (allCells.length > 0) {
                                var found = false;
                                allCells.each(function(index, td) {
                                        var regExp = new RegExp(inputVal, 'i');
                                        if (regExp.test(j2(td).text())) {
                                                found = true;
                                                return false;
                                        }
                                });
                                if (found == true)
                                        j2(row).show();
                                else
                                        j2(row).hide();
                        }
                });
        }



        j2(document).ready(function(){
         
         //confirmation
			j2(".delete_athlete").easyconfirm({locale: { title: 'Select Yes or No', button: ['No','Yes']}});
			j2(".delete_athlete").click(function() {
				var vs = j2(this).attr("aa");
				del(vs);
			});
			         
         //searchtables
         j2('#search').keyup(function() {
         			
                        searchTable(j2(this).val());
                	
                });
         
        j2("#sha").click(function() {
           var v = j2("#event_id option:selected").val();
            j2("#wa").load("ajax.php?page=10&event_id=" + v);
        }
        );
        
         j2("#rha").click(function() {
           var v = j2("#event_id option:selected").val();
            j2("#wa").load("ajax.php?page=12&event_id=" + v);
        }
        );
        
         j2("#insr").click(function() {
                var v = j2("#event_id option:selected").val();
               
                j2("#wa").load("ajax.php?page=5&event_id=" + v);
            });
            
                
            j2("#ag").change(function() {
                //go(); 
            });
            
            j2("#refresh_results").click(function() {
                
                go();
                
            } );
                
            function go()
            {
               
                var v =     j2("#event_id option:selected").val();
            
                var v4 =    j2("#ag option:selected").val();
               v4 = v4.replace("+","%2B");
                  
                j2("#wa").load("ajax.php?ppage=<?=$_GET[ppage]?>&page=4&event_id=" + v + "&ag=" + v4 );
     
            }
            
	        //inline editor
			j2(".wwwod").click(function(){
				//params
				var block = j2("#isBlock");
				if (block.val() == 1)
				{
					alert("Only one element can be edited at the same time!")
						return;
				};
				block.val("1");			
				var wod_id = j2(this).attr("wod_id");
				var wod_type = j2(this).attr("wod_type");
				var athlete_id = j2(this).attr("athlete_id");
				
				//show input menu
				var tds = "td_" + wod_type + "_" + athlete_id + "_" + wod_id
				var td = j2("#" + tds);
				var old_text = td.html();
				var res = j2("#" + tds + "_span").text()
					if(wod_type == 'time') {	  
					td.html(" <input class='validate[required]' style='width: 80px;'  type='text' value='' id='"+tds+"_input'> <br> <a id='eee' style='cursor: pointer'>save</a> | <a id='ddd' style='cursor: pointer'>cancel</a> ");
    				  j2.mask.definitions['#']='[012345]';
                	  j2("#"+tds+"_input").mask('#9:#9');
					} 
					else {
					td.html(" <input class='validate[required,custom[number],min[0]]' style='width: 80px;' type='text' value='' id='"+tds+"_input'> <br> <a id='eee' style='cursor: pointer'>save</a> | <a id='ddd' style='cursor: pointer'>cancel</a> ");
					};
				var new_res = j2("#"+tds+"_input");
				new_res.trigger('focus');	
				j2("#ddd").click(function() { td.html(old_text); j2("#isBlock").val("0"); });
				j2("#eee").click(function() { 
					
					 	//if new data is ok then send it to the server and refresh the page
					 	if (j2("#frm").validationEngine('validate') == true)
                    	{			
                    	           j2.ajax({
                                   type: "POST",
                                   data: "val="+new_res.val(),
                                   url: "ajax.php?page=15&wod_id="+wod_id+"&athlete_id="+athlete_id+"&wod_type="+wod_type,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                           go();          
                                         }
                                });
						};					 
					 });

			});
        });
    

       
         
            
    </script>
   
    
<? if ($_GET[ppage] == 0 or !$_GET[ppage] ) { ?>
    <a href="#" id="insr" name="insr" >Insert new data</a>
   <? } ?>
    <br>
  
    <style>/* Sortable tables */

/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   speak for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */


/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */


/* Sortable tables */
    table.sortable tr,table.sortable th
    {
		
        
    }
	
	
    
	
	
    table.sortable 
    {
        width: 100%;
		
    }
    
    
    .sorttable_event
    {
        text-align: center;
		
    }
   .sorttable_rank
   {
       width: 40px;
       text-align: center;
	   vertical-align:center;
   }
    .sortable_edit
    {
        width: 125px;
        text-align: right;
        padding-right: 10px;
    }
    .sorttable_name_name
    {
        padding-left: 10px;
		
       
    }
   
     .sorttable_name
    {
        padding-left: 10px;
		vertical-align:bottom;
    }
   
    table.sortable thead {
    background-color:#eee;
    color:#666666;
    font-weight: bold;
    cursor: default;
		
    }
	
	td{
	border:1px solid #e6e6e6;	
	}
	
	tr:nth-child(even) {
	background: #eee;
	}
    
	tr:nth-child(odd) {
	background: #FFF
	}
	
	.Pointscolumn
   {
       
       text-align: center;
	   vertical-align:center;
   }

    </style>
    
    <div style="float:left">
                        <label for="search"> <strong>Search: </strong>
                        </label><input type="text" id="s2ddfd" style="display: none;"> <input type="text" id="search" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </div>
    
    <div id="filter">
    <?php
    $sql = "Select id, ag from ".$prefix."event_ag where event_id = $_GET[event_id] and ag <> 'All' ";
    $q4 = mysql_query($sql);
    $ag_cnt = mysql_num_rows($q4);
    $numberofcategory = $ag_cnt;
    if ($ag_cnt > 0) {
        print "  <strong>Category:</strong> <select id=\"ag\">";
        if (!$_GET[ag]) {
            $sl = " selected ";
        }$categoryArray = array();
	   
     ?>
       <? if ($_GET[ppage] == 0 or !$_GET[ppage] ) { 
       print "<option $sl  value=\"All\"> All";
	   print "<option  value=\"Uncategorized\"> Uncategorized";
	   array_push($categoryArray, "  ");
	   array_push($categoryArray, "All");
	   
	   $numberofcategory = $numberofcategory +2;
	   
       } ?>

      <?
        //print "<option $sl  value=\"All\"> All";
        while ($d = mysql_fetch_array($q4)) {
            if ($_GET[ag] == $d[ag]) {
                $sl = " selected ";
            } else {
                $sl = " ";
            }
            print "<option $sl value=\"" . str_replace(" ", "%20", $d[ag]). "\">" . $d[ag] . "";
        }
        print "     </select>";
    }
    //check if is event creator
    
   
    ?>
   
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 


<label for="search" > <strong>Stage: </strong></label>
<select id="open_event">

</select>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="btn" value="play" onClick="btnfunc()" id="btn" style="width:70px;height:30px;text-align:center;
margin-bottom:9px" ></input>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" id="refresh_results" > Click to Refresh</a>

<br>

    </div>

        <?
		
//create a new table to put the percentage of each event in Stage:
$sqlpercent = "Select weight from ".$prefix."event_autoconfigurator where event_id = $_GET[event_id]  ";
    $qpercent = mysql_query($sqlpercent);
   
       
echo ' <table border="1" cellspacing="0" cellpadding="1" class="sortable"  id="sortable0">
<thead> <tr>
            <th></th>
			
			<th></th>';
				   
        while ($cbs = mysql_fetch_assoc($qpercent)) {
			echo "<th>".$cbs['weight']."</th>";
			echo '<th></th>';
			
        
		
		}

print '<th></th>';
			
					 
echo '</tr></thead>
</table>';

//end create a new table to put the percentage of each event in Stage:

        
    

$sqlbil = "Select id, ag from ".$prefix."event_ag where event_id = $_GET[event_id] and ag <> 'All' ";
    $q4bil = mysql_query($sqlbil);
   $numbil = 1;
    $nodata = 0;
       
        while ($cbs = mysql_fetch_assoc($q4bil)) {
        array_push($categoryArray, $cbs['ag']);
		
		}
		
		//to veridy that $categoryArray is not empty
		if( count($categoryArray)>0){
			
		foreach ($categoryArray as $valuearray)
		{
			
			
       $filter_ag = "  AND age_category = '".$valuearray."'";
	   if($valuearray == "  "){
		   $filter_ag = "  "; 
		   }
	   
?>
<?
        $sql = "SELECT 
                            wea.id AS athlete_id, 
                            wea.event_id AS event_id, 
                           if(wea.lname = 0, wea.fname,(select name from ".$prefix."users where id = wea.lname)) as lname,
                            if(wea.lname = 0, wea.gender,   (select cb_affiliatename from ".$prefix."comprofiler where user_id = wea.lname)) as affiliate,
                            wea.lname AS fname, 
                            wea.gender AS gender, 
                            wea.age_category AS age_category, 
                            wea.category AS category, 
                            wer.id AS result_id, 
                            wer.wod_id AS wod_id, 
                            wer.result AS result, 
                            weu.type AS wod_type, 
                            weu.weight AS weight, 
                            weu.desc AS description,
														wrr.rn as wod_rank,
														rn.overall as overall,
														rn.rank as rank
                       
                        FROM ".$prefix."event_result wer, 
														 ".$prefix."event_athlete wea, 
														 ".$prefix."event_autoconfigurator weu,
														/* Overall ranking*/
														(
	Select athlete_id, overall, rank from (
		Select athlete_id, overall, @r := if(overall <> @p, @r +1 +@s, @r) as rank, @p as prev,  @s := if(@p = overall, @s + 1, 0)  as step,    @p := overall   
					from  (Select @r := 0) a, (Select @p := 0) p , (Select @s := 0) r, ( 
							Select athlete_id, sum(rn*weight) as overall
							 from (


							SELECT  @row_num := IF(@prev_value=o.wod_id ,
							IF(@prev_res <> o.result,@row_num+@step,@row_num)

							,1) AS rn,
											@step := IF(@prev_value=o.wod_id and @prev_res = o.result,@step+1,1) AS step
																										 ,o.wod_id
																										 ,o.athlete_id
																										 ,o.result
																											,o.weight
																											
																										 ,@prev_value := o.wod_id
																										 ,@prev_res := o.result 
								
																										 
																								FROM (select wea.weight, wer.wod_id, wer.athlete_id, if(wea.type='time',if(wer.result=0,356400,wer.result)*-1,wer.result) as result from ".$prefix."event_result wer, ".$prefix."event_autoconfigurator wea where wea.id = wer.wod_id) o,
																										(SELECT @row_num := 1) x,
																										(SELECT @prev_value := '') y,
																										(Select @prev_res := 0) z,
																										(Select @step := 1) s
																							where 
																							 o.athlete_id in (Select id from ".$prefix."event_athlete where event_id = " . $_GET[event_id] . " " . $filter_cat . "  " . $filter_gender . "  ".$filter_ag." )
																							
																								ORDER BY o.wod_id, o.result DESC )s 
							group by athlete_id
							order by overall ASC
					) s
		) o) rn,




														/* /Overall ranking */
														/* WODs Ranking  */
															
(Select athlete_id, wod_id, rn, if(result<0,result * -1, result) as result 
from (
	SELECT  @row_num := IF(@prev_value=o.wod_id ,
			IF(@prev_res <> o.result,@row_num+@step,@row_num)

			,1) AS rn,
							@step := IF(@prev_value=o.wod_id and @prev_res = o.result,@step+1,1) AS step
                                       ,o.wod_id
                                       ,o.athlete_id
                                       ,o.result
																				,o.weight
																				
                                       ,@prev_value := o.wod_id
																			 ,@prev_res := o.result 
	
                                       
                                  FROM (select wea.weight, wer.wod_id, wer.athlete_id, if(wea.type='time',if(wer.result=0,356400,wer.result)*-1,wer.result) as result from ".$prefix."event_result wer, ".$prefix."event_autoconfigurator wea where wea.id = wer.wod_id) o,
                                      (SELECT @row_num := 1) x,
                                      (SELECT @prev_value := '') y,
																			(Select @prev_res := 0) z,
																			(Select @step := 1) s
                                where 
                                 o.athlete_id in (Select id from ".$prefix."event_athlete where event_id = " . $_GET[event_id] . " " . $filter_cat . " " . $filter_gender . " ". $filter_ag." )
																
                                  ORDER BY o.wod_id, o.result DESC 
	) wr) wrr

														/* / WODs ranking */

                        WHERE 	
				 rn.athlete_id  = wea.id
				 and wrr.athlete_id = wea.id
				 and wrr.wod_id = weu.id
				 and wer.athlete_id = wea.id
                                 AND wer.wod_id = weu.id
                                 AND wea.event_id = " . $_GET[event_id] . "
                            $filter_ag
                            $filter_cat
                            $filter_gender    
           Order by cast( rank as SIGNED) asc";

        
        
      //  print "<pre>";   print $sql;    print "</pre>";

        $res = mysql_query($sql);
        $cnt = mysql_num_rows($res);
		//echo $cnt;
        $arr = array();
       
        if ($cnt) {
            $i = 0;
            while ($row = mysql_fetch_array($res)) {

                $arr[$row[athlete_id]][wod][$row[wod_id]][result] = $row[result];
                $arr[$row[athlete_id]][wod][$row[wod_id]][weight] = $row[weight];
                $arr[$row[athlete_id]][wod][$row[wod_id]][wod_rank] = $row[wod_rank];
                $arr[$row[athlete_id]][wod][$row[wod_id]][wod_type] = $row[wod_type];
                    
                $arr[$row[athlete_id]][lname] = $row[lname];
                $arr[$row[athlete_id]][fname] = $row[fname];
                $arr[$row[athlete_id]][affiliate] = $row[affiliate];
                $arr[$row[athlete_id]][gender] = $row[gender];
                $arr[$row[athlete_id]][event_id] = $row[event_id];
                $arr[$row[athlete_id]][age_category] = $row[age_category];
                $arr[$row[athlete_id]][category] = $row[category];
                $arr[$row[athlete_id]][rank] = $row[rank];
                $wod_cnt = count($arr[$row[athlete_id]][wod]);
                $arr2[$row[wod_id]] = $row[description];
            }

          
            ?>

         <?  

echo '<script>sorttable.makeSortable(document.getElementById("sortable'.$numbil.'")); </script>';
//to give the table a className witch is the category name
$newtableid = str_replace("AND age_category = '","",$filter_ag);
$newtableid = str_replace("'","",$newtableid);
$newtableid = str_replace(" ","",$newtableid);

//for Uncategorized 
if($valuearray == "All"){
		   $newtableid = "Uncategorized"; 
		   }
// for All
if($valuearray == "  "){
		   $newtableid = "All"; 
		   }


echo ' <table border="0" cellspacing="0" cellpadding="1" class="sortable resulttable '.$newtableid.'" id="sortable'.$numbil.'"  >


            <thead> <tr>
                     <th class="sorttable_rank" style="vertical-align:bottom;"><a href="#">Rank</a></th>
                    <th class="sorttable_name" style="text-align:left"> <a id="trigger" ff="55" href="#">Competitor</a> </th>' ?>
                    
        <?
    // print ' <th style="text-align:left;padding-left: 10px;vertical-align:bottom;"><a href=\"#\" >Affiliate</a> </th>';
        $numberofevent = 0;//it was used for the number of the event
        foreach($arr2 as $keyv => $valv)
        for($col=1;$col<3;$col++){
		{
			
            ?><? echo '<th  style="vertical-align:bottom;padding-bottom:20px"   id="pop-up" 
	><a href="#"  ><p style="text-align:center;padding-left:20px;
			-webkit-transform:rotate(270deg);
    -moz-transform:rotate(270deg);
    -o-transform: rotate(270deg);
	
	
	
	">'; print $valv; ?><?  echo '</p></a>
                
          
            </th>' ?><?
         $numberofevent++;
        }
		}//end for of the $col
        
            if ($ag_cnt > 0) {
            //print " <th ><a href=\"#\" >Category</a> </th>";
			print ' <th style="vertical-align:bottom;"  ><a href="#">Points</a> </th>';
        }
        ?>
            
            
                   
                 <? if ($_GET[ppage] <> 1) { ?>  <? echo '<th width="30"  class="sorttable_nosort" >&nbsp;</th>' ?> <? } ?>
                <? echo '</tr>   </thead>' ?>
        <?php
        $i = 1;
        /* $arr[$row[athlete_id]][wod][$row[wod_id]] */
        foreach ($arr as $key => $val) {

            print "<tr>";
               print "<td  class=\"sorttable_rank\">" . $val[rank] . "</td>";
         
            print "<td class=\"sorttable_name_name\"><div>";
                        $query = "SELECT selfscore FROM .".$prefix."event_autoconfigurator where event_id =" . $_GET[event_id]  ;		
			$query_run = mysql_query($query);		
			$my_result=mysql_result($query_run,0);
	            if (($_GET[ppage] <> 1 or $val[fname]  == $_GET[user_id]) && $_GET[user_id]>0 && $my_result == 1 ) {
	            print "<a href=\"#\" onclick=\"edit(".$key.")\"  id=\"edit\" >[Edit]</a>  ";
	            }
            if ($val[fname] > 0) { 
            print "<a href=\"index.php?option=com_comprofiler&task=userprofile&user=".$val[fname]."&Itemid=111\"> ".$val[lname].
"</a>";
if ($_GET[ppage] <> 1 ) {
	               		//print "<span><a class=\"wwwod\" style=\"cursor: pointer;\" wod_id=\"".$key2."\" athlete_id=\"".$key."\" wod_type=\"".$val2[wod_type]."\">[Edit]</a></span>";
						print "<a href=\"#\" onclick=\"edit(".$key.")\"  id=\"edit\" > [Edit]</a>  ";
					}
              		
            }
            else
            {
            print "".$val[lname]."";
if ($_GET[ppage] <> 1 ) {
	               		//print "<span><a class=\"wwwod\" style=\"cursor: pointer;\" wod_id=\"".$key2."\" athlete_id=\"".$key."\" wod_type=\"".$val2[wod_type]."\">[Edit]</a></span>";
						print "<a href=\"#\" onclick=\"edit(".$key.")\"  id=\"edit\" > [Edit]</a>  ";
					}
            
            }
			
			 print '<br><span >'.$val[affiliate].'</span>';
            print "</div></td>";
           

            foreach ($val[wod] as $key2 => $val2) {
                
				for($col=1;$col<3;$col++){
               if($val2[result] <> 0)
               { print "<td class=\"sorttable_event\" sorttable_customkey=\"".$val2[wod_rank]."\" id=\"td_".$val2[wod_type]."_".$key."_".$key2."\"><span id=\"td_".$val2[wod_type]."_".$key."_".$key2."_span\">";
                print $val2[wod_rank];
                if($val2[wod_type]=='time'){$val2[result] = date("i:s", mktime(0,0,$val2[result])); }
                print "</span>";
                 print "<br><span>(" . $val2[result] . ") ";
                
             	 if ($_GET[ppage] <> 1 ) {
               	//print "<a class=\"wwwod\" style=\"cursor: pointer;\"  wod_id=\"".$key2."\" athlete_id=\"".$key."\" wod_type=\"".$val2[wod_type]."\">[Edit]</a>";
				//print "<a href=\"#\" onclick=\"edit(".$key.")\"  id=\"edit\" >[Edit]</a>  ";
              		}
              		
                print "</span></td>";
               }
               else { 
               print "<td class=\"sorttable_event\" sorttable_customkey=\"10000000\" id=\"td_".$val2[wod_type]."_".$key."_".$key2."\">";
               print "<span  id=\"td_".$val2[wod_type]."_".$key."_".$key2."_span\">"; 
               print "N/A"; 
               print "</span>";
	                if ($_GET[ppage] <> 1 ) {
	               		//print "<span><a class=\"wwwod\" style=\"cursor: pointer;\" wod_id=\"".$key2."\" athlete_id=\"".$key."\" wod_type=\"".$val2[wod_type]."\">[Edit]</a></span>";
						//print "<a href=\"#\" onclick=\"edit(".$key.")\"  id=\"edit\" >[Edit]</a>  ";
					}
                 }
              
               print "</td>";
			   }//end if for $col
            }


            
                     if ($ag_cnt > 0) {
                //print '<td class="categorycolumn">';
//                print $val[age_category];
//                print "</td>";

					print '<td class="Pointscolumn" >';
					//print'cnn';
					print "</td>";
            }
            
           if ($_GET[ppage] <> 1 ) {
            print " <td class=\"sortable_edit\"> ";
            if ($_GET[ppage] == 0)
                    {
                        print "         <a href=\"#\"  class=\"delete_athlete\" aa=\"".$key."\">Delete</a>";
                    }
                    print "</td>";
              }
           
   
            print "</tr>";
            ++$i;
        }
        ?> <? echo '</table>'; 

} else {
        if($nodata < 1){
        $nodata = $nodata+1;
        print '<p id="nodata">No available data</p>';
         }
    }
$numbil = $numbil + 1;	
	
}//end each categoryarray
}//end if count(arra
?>
<script>



//script to make the rotating tables
//alert(("#All th").length);
// to hide the table and no available and categorycolumn
	j2("table[class^='sortable']").hide();
	//j2("#sortable0").show();
	
    j2("#nodata").hide();
    //j2(".categorycolumn").hide();
	
	//to cerate the options of select of Stage:
	var colname ;
	var y=0;
	var biglength = 0;
	
	
	
	for(var z=4;z<<? echo $numberofevent+3 ?>;z=z+2){
	j2('td:nth-child('+z+'),th:nth-child('+z+')').hide();
		j2('td:nth-child('+z+')').html('-');
		colname = j2('th:nth-child('+z+')').text();
		
                
		colname = colname.substr(0, colname.indexOf('\n'));
                
        //to get the the biggest header length
		if(colname.length > biglength){
			biglength = colname.length;
		}        
        
		
		y++;
		j2("#open_event").append(new Option(colname, "open_event"+y));
	}
	y=0;
	//end of cerate the options of select of Stage:
	
	
	//to change the height of the header
	biglength = biglength/1.5+1;
	j2('th').css('height', biglength+'em');
	
	
	

	//set last option of Stage: as default
	j2('#open_event option:last-child').attr("selected","selected");
	
	
	//to reset the category doprdown you you click to refresh
	j2('#ag option:first-child').attr("selected","selected");
	
	

		

//to make the button pause by default and show the first table
var rotate = false;
j2("#sortable"+1).show();
if ((j2('tr:visible').length)==0){
	j2("#nodata").show();
	
		
}


function btnfunc(){
	if(j2("#btn").val()=="pause"){
		j2("#btn").val("play");
		rotate = false;
		
	}else{
		j2("#btn").val("pause");
		rotate = true;
	}
	
}

//to push the footer to the bottom

function footerpush(){	
    
	var v4 = j2("#ag option:selected").val();
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
	v4 = v4.replace("%20","");
	v4 = v4.replace("+","\\+");	
		if(1){
			//alert("sfs");
			
			var footermargin = new Number(0);
			if(Number(j2('.'+v4).height())+175 < 571){
				//alert("sfs");
				footermargin = 571 -( Number(j2('.'+v4).height())+175) ;
				
			j2('#rt-footer').css('margin-top', footermargin +'px');
			}else{
				j2('#rt-footer').css('margin-top', '0px');
				//alert("sfs");
			}
		}
}
//end ofo to push the footer to the bottom

       		
//onchange of category:

j2("#ag").change(function() {

footerpush();


	var v4 = j2("#ag option:selected").val();
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
    v4 = v4.replace("%20","");
	v4 = v4.replace("%20","");
	v4 = v4.replace("+","\\+");
			   
    j2("#btn").val("play");
    rotate = false;
    j2("table[class^='sortable']").hide();

    j2("."+v4).show();
	
	
		

    j2("#nodata").hide()	
    if ((j2('tr:visible').length)==0){
		j2("#nodata").show();
	}
});
//end of onchange of category:
			
//onchange of stage:
j2("#open_event").change(function() {
	stageonchange();
	
});

			
function stageonchange() {
		
	j2("#btn").val("play");
    rotate = false;


    //v7 is the number of the event in Stagte :start from 1 for the first event in the Stage: dropdown
	
	//to hide or show the column
	var v7 = j2("#open_event option:selected").val();
    v7 = v7.replace("open_event","");
	
	for(var d = 3;d<(v7*2)-1+2+1;d=d+1){
		
			j2('td:nth-child('+d+'),th:nth-child('+d+')').show();
			d=d+1;
			j2('td:nth-child('+d+'),th:nth-child('+d+')').hide();
	}
	
	
	for(var s=(v7*2)+2+1;s<<? echo $numberofevent+3 ?>;s=s+1){
		
	j2('td:nth-child('+s+'),th:nth-child('+s+')').hide();
	s=s+1;
	j2('td:nth-child('+s+'),th:nth-child('+s+')').show();
		
		
	}
	
	//to update the Rank

			   
    
	var suminrow = new Number(0);
	var rowCount;
	var rankinevent;
	var maxrankinevent;
	var rankcontent;
	var eventweight;
	var rankofrowbefore;
	var minweight = new Number(100);
	var thisth;

	//to get the min weight
	var thCount0 = j2('#sortable0 th').length;
	//alert("thCount0:"+thCount0);
	for( i= 3;i<thCount0 ;i=i+2){
		thisth = j2('#sortable0 th:nth-child('+i+')').text();		
		thisth= Number(thisth);
		//alert(i+" dcs "+ thisth);
		if(minweight>thisth){
			minweight=thisth;			
		}
	}
	// end of to get the min weight

	//to get the max rank in each column in each table:
	
	var rankarray = new Array(<? echo $numberofcategory +1 ?>);
	
	for(i = 1;i<<? echo $numberofcategory?>+1;i++){	
	     
		rowCount = j2('#sortable'+i+' tr').length;
		thCount = j2('#sortable'+i+' th').length;		
		rankarray[i] = new Array(thCount);
			
		for(var d = 3;d<thCount;d=d+2){		
			maxrankinevent = Number(0);
			for(var c=1;c<rowCount;c++){					
			
				rankinevent = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child('+d+')').text();
				//alert(rankinevent);
				if(rankinevent != "N/A" && rankinevent != "Delete" ){
					rankinevent = rankinevent.substr(0, rankinevent.indexOf('('));
					rankinevent = Number(rankinevent);
					if (maxrankinevent<rankinevent){
							maxrankinevent = rankinevent;
							
							
						}
					
					
				}
			}
			//alert(d+"  dcs "+maxrankinevent);
			rankarray[i][d]= maxrankinevent+1;
			//alert(i+" "+" "+d+"  "+rankarray[i][d]);
		}
	}
	
	//end of to get the max rank in each column in each table	
	
	function trim1 (str) {
    	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	}
	
	var lastcol;
	for(i = 1;i<<? echo $numberofcategory?>+1;i++){	
		rowCount = j2('#sortable'+i+' tr').length;
		thCount = j2('#sortable'+i+' th').length;
		//alert(j2('#sortable'+i+' tr:nth-child(1) td:last-child').text());
		lastcol = j2('#sortable'+i+' th:last-child').text();
		if( (trim1(lastcol) == "" )){
			
			thCount  = thCount - 1;
			
		}
		//alert("billel");
		var verify = false;
		
		
		for(var c=1;c<rowCount;c++){
			
			
			suminrow = 0;//d for column
			for(var d = 3;d<(v7*2)+2+1;d=d+2){
				rankinevent = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child('+d+')').text();
				//alert(d+" sxcxc "+rankinevent);
				if(rankinevent != "N/A" && rankinevent != "Delete"){
					
					rankinevent = rankinevent.substr(0, rankinevent.indexOf('('));
				}else{
					rankinevent = rankarray [i][d];
				}
				
				eventweight = j2('#sortable0 th:nth-child('+d+')').text();
				
				//alert("eventweight"+eventweight);
				eventweight = Number(eventweight);
				
				rankinevent = Number(rankinevent)*eventweight/100;
				//alert("rankinevent"+rankinevent);
				
				suminrow = suminrow + Number(rankinevent);
				//alert("suminrow" +suminrow)
				
			
			
					
			}
			suminrow = (suminrow*100)/minweight;
			//alert("minweight "+minweight);
			//alert("suminrow "+suminrow);
			
			rankcontent = suminrow.toFixed(1);
			//alert("rankcontent"+rankcontent);
			
			//rankofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child(1)').text();	
			j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text(rankcontent);
			verify=true;
//			nth-child('+thCount+')
				
				
		}
		
		j2(".sorttable_name").click();
	j2(".sorttable_rank").click();
	var rankcontent1;
	if(verify){
		
	for(var c=1;c<rowCount;c++){
		
			
			rankcontent1 = Number(j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text());
			
			//alert("rankcontent " +rankcontent);
//			alert(thCount);
			j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child('+thCount+')').text(rankcontent1 );
//			c=c-1
//			contentofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child('+thCount+')').text();
//			alert("rankcontent " +rankcontent)
//			rankofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text();
//			c=c+1;
			//alert(rankofrowbefore);
				//alert("rank ocntent is"+rankcontent);
			if(c==1){
				j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text(1);
			}else{
			
				c=c-1
			contentofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child('+thCount+')').text();
			
			rankofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text();
			c=c+1;
				if(contentofrowbefore==rankcontent1){
					
					j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text(Number(rankofrowbefore));
					
										
				}else{
					j2('#sortable'+i+' tr:nth-child('+c+') td:first-child').text(c);
				}
			}
	}
	
			//{
//				
//				j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child(1)').text(c+'  ('+rankcontent+')');				
//			
//				//to verify if the row before has the same average weight:
//				c=c-1;
//				rankofrowbefore = j2('#sortable'+i+' tr:nth-child('+c+') td:nth-child(1)').text();
//				c=c+1;
//				rankofrowbefore = rankofrowbefore.substr(rankofrowbefore.indexOf('(')+1);
//				rankofrowbefore= rankofrowbefore.substr(0, rankofrowbefore.indexOf(')'));
//				
//						
//				
//			}
		}
		
		
	}
	
	
	
	
	
	
	thCount=<? echo $numberofevent+3 ?>;
	var contentofrowbefore;
	for(i = 1;i<<? echo $numberofcategory?>+1;i++){
		rowCount = j2('#sortable'+i+' tr').length;
		
	}

}

//end of onchange of stage:			
			
var t;          
var i = 0;

//the function that the button play/pause calls when it is clicked
function func() {
        
	if(rotate){
    
	   
	if(i>0){ 
	j2("#sortable"+i).hide();

	}
        //to rotate the dropdwon category with its table
        j2("#ag").val(j2("#ag option:eq("+i+")").val());
	i = i+1;
   
	if (i><? echo $numberofcategory?>){
	i=1;
		
	}
       j2("#nodata").hide();
        j2("#sortable"+i).show();
		footerpush();
       
       

       	//to show no available data
        if ((j2('tr:visible').length)==0){
	j2("#nodata").show();
		
	}
  	
                			
	}
   
t=setTimeout(function(){func()},14000);
   
   
}//end func()

function resetwidth() {
	//to reset width when refresh
	var windwidth = j2(window).width();
	
	
	
	j2('.rt-pull-9').css('position','relative');
	
	if(windwidth>959 && windwidth <1200){
		j2('.rt-grid-9').width(720);
		j2('.rt-grid-12').width(960);
		
		j2('.rt-pull-9').css('left','-720px');
		j2('.rt-grid-3').width(240);
		
	}else if(windwidth>767 && windwidth <960){
		j2('.rt-grid-9').width(576);
		j2('.rt-grid-12').width(768);
		
		j2('.rt-pull-9').css('left','-576px');
		j2('.rt-grid-3').width(192);
		
	}else if(windwidth>480 && windwidth <768){
		j2('.rt-grid-9').width(480);
		j2('.rt-grid-12').width(480);
		
		j2('.rt-pull-9').css('left','auto');
		j2('.rt-grid-3').css('width','100%');
		
	}else if( windwidth <481){
		j2('.rt-grid-9').css('width','95%');
		j2('.rt-grid-12').css('width','95%');
		
		j2('.rt-pull-9').css('left','auto');
		j2('.rt-grid-3').css('width','100%');
		
	}if(windwidth>1199 ){
		j2('.rt-grid-9').width(900);
		j2('.rt-grid-12').width(1200);
		
		j2('.rt-pull-9').css('left','-900px');
		j2('.rt-grid-3').width(300);
		
		
	}
	
	
	//end of to reset width when refresh 
	//alert(j2('table').width());
	//j2('.rt-grid-12').width(1200);

 	//to change the width when the table is bigger (contains many events)
	var sumall = 0 ;  	
	for(i=1;i<40;i++){	
		sumall = sumall + j2('tr:nth-child(1) td:nth-child('+i+')').width();	
		
	}
	sumall = sumall+116;
	//alert(sumall);
	
	
	
	
	if(sumall>j2('table').width() && sumall > windwidth){
	
		
		//sumall = sumall+116;
		
		j2('.rt-grid-9').width(sumall);
		
		j2('.rt-grid-12').width(sumall);
		
		//j2('.rt-grid-12').width(1200);
		//alert("last"+j2('table').width());
	    
	
		if(windwidth>767){
			j2('.rt-pull-9').css('left','0px');
			j2('.rt-pull-9').css('position','absolute');
			
		
		}else{
			j2('.rt-grid-3').width(sumall);
			
			
		
		}
	
	}
	
}
//end of to change the width when the table is bigger (contains many events)






j2(document).ready(function() {
   	//j2("#sortable0").show();
	
	if(j2("tr:visible").length<3){
	j2(".modal").css("display","none");
	return false;
}
	
	stageonchange();
	resetwidth();
	

	
   clearTimeout(t);
    
   func(); 
         
    j2("#open_event").change();
	//alert("algeria");
	j2(".modal").css("display","none");
	
	
	
   
});


</script>

        	<input type="hidden" id="isBlock" value="0">
    <script>
    function edit(x)
        {
            var j10 = jQuery.noConflict();
            var v = j10("#event_id option:selected").val();
            j10("#wa").load("ajax.php?page=9&event_id=" + v+"&athlete_id=" + x + "&ppage="+<?=$_GET[ppage]?>);
        }
        function del(x)
        {
            var j8 = jQuery.noConflict();
            j8.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=8",
                                    data: "athlete_id=" + x,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                          
                                               
                                                var v =     j8("#event_id option:selected").val();
                                            
                                                var v4 =    j8("#ag option:selected").val();
                                                j8("#wa").load("ajax.php?page=4&event_id=" + v +  "&ag=" + v4 );
                                               
                                               
                                    }
                            });
           
        }
    </script><?
    
} //page == 4









if ($page == 5) {
    $sql_wod = "Select id,type,weight, `desc` from `".$prefix."event_autoconfigurator` where event_id = " . $_GET[event_id];

    $q_wod = mysql_query($sql_wod);
    if (!$q_wod) {
        print "Incorrect query";
    }
    $wod[] = null;
    $i = 0;
    while ($row = mysql_fetch_array($q_wod)) {
        $wod[$i][id] = $row[id];
        $wod[$i][type] = $row[type];
        $wod[$i][weight] = $row[weight];
        $wod[$i][desc] = $row[desc];
        ++$i;
    }
    
    ?>
<link rel="stylesheet" href="form_validator/css/validationEngine.jquery.css" type="text/css"/>
<script src="form_validator/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="form_validator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="jquery.maskedinput-1.2.2.js"></script>
<script>
        var j7 = jQuery.noConflict();
        
        j7(document).ready(function(){
              j7("#gender").prop('disabled', 'disabled');
             j7("#atlname").change(function() { 
             var isa = j7("#atlname").val();
             
             if (isa != '')
                 {
                     
                      j7("#lname").prop('disabled', 'disabled');
                       j7("#gender").prop('disabled', false);
                 };
             if (isa == '')
                 {
                     
                      j7("#lname").prop('disabled', false);
                       j7("#gender").prop('disabled', 'disabled');
                 };
                 
                    });
             
             
             j7.mask.definitions['#']='[012345]';
                <? foreach($wod as $k => $v) { if($v[type] == 'time') { ?>
                j7("#wod\\[<? print $v[id]; ?>\\]").mask('99:#9').val('mm:ss');
           <?
                } else 
                {?>
                   
               <? }
                
           
           } ?>
                   
                   
                   
        });
</script>

<table border="0"  class="tablesorter">

        <tr class="rsform-block rsform-block-description">
            <td width="150"></td>
            <td><div class="formClr"></div></td>
            <td></td>
        </tr>
     <? 
      
        
    $sql =  " select memberid as user_id, s.name  as fname from ".$prefix."users s, sfvi3_comprofiler_members cm 
where referenceid in (select  oe.created_by from ".$prefix."ohanah_events oe where oe.ohanah_event_id = ".$_GET[event_id].") 
and type like '%Athlete%'
and
s.id = memberid
and  memberid not in (select lname from ".$prefix."event_athlete where event_id = ".$_GET[event_id]."  ) order by 2 asc";
    $s = mysql_query($sql) or die(mysql_errno());
$num_rows = mysql_num_rows($s);
?>
         <tr class="rsform-block rsform-block-last-name">
            <td>Athlete *</td>
            <td>
              <?if ($num_rows > 0) { ?>  
                <select name="lname" id="lname">
                 <?   
                 while ($az = mysql_fetch_array($s))
                 {
                     print "<option value='".$az[user_id]."'> ".$az[fname]."";
                 }
                 ?>
                </select>

                <br>
                
           OR type<br><? } ?>  name: <input type="text" id="atlname" name="atlname" size="15"> 
            affiliate: <input type="text" id="gender" name="gender" size="15">  </td>
            <td></td>
        </tr>
       
        
        <?php
        $sql_ag = "Select ag from `".$prefix."event_ag` where event_id = " . $_GET[event_id];
        $q_ag = mysql_query($sql_ag) or die("Incorrect query: " . $sql_ag);
        $ag_cnt = mysql_num_rows($q_ag);


        if ($ag_cnt > 0) {
            ?>
            <tr class="rsform-block rsform-block-category">
                <td> Category *</td>
                <td><select  name="ag"  id="Category" class="validate[required]" >
        <?php
        while ($row = mysql_fetch_array($q_ag)) {


            print "<option value=\"" . $row[ag] . "\">" . $row[ag] . "";
        }
        ?>
                    </select></td>
                <td></td>
            </tr>
    <? } ?>



    <?php
    for ($i = 0; $i < count($wod); ++$i) {
        ?>

            <tr class="rsform-block rsform-block-event-1">
                <td>       <? print "WOD #" . ($i + 1);
        print " (" . $wod[$i][type] . ")"; ?>*</td>
                <td><sub><? print $wod[$i][desc]; ?></sub><br><input <? if ($wod[$i][type] == 'time'){ print 'class="validate[required]"'; } else { print 'class="validate[required,custom[number],min[0]]"';} ?>  type="text" value="0" name="wod[<? print $wod[$i][id]; ?>]" id="wod[<? print $wod[$i][id]; ?>]"></td>
                <td></td>
            </tr>

        <?php }
        ?>

        <tr class="rsform-block rsform-block-submit-your-results">
            <td></td>
            <td><input type="hidden" id="event_id" name="event_id" value="<? print $_GET[event_id]; ?>"><input type="Button" value="Submit" name="form[Submit Your Results]" id="sbm"  class="rsform-submit-button" style="height: 25px; width: 60px" /><div class="formClr"></div></td>
            <td></td>
        </tr>

    </table>
    
    <div id="info"></div>
<script>
    
     var j4 = jQuery.noConflict();
      j4(document).ready(function(){
              j4("#sbm").click(function(){
                if (j4("#frm").validationEngine('validate') == true)
                    {
                    var datas = j4('#frm').serialize();
                   j4("#sbm").prop('disabled','disabled');
                 // alert("6")
                   
                   
                    // Отсылаем паметры
                           j4.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=6",
                                    data: datas,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                        
                                        //alert(html)
                                               
                                                var v =    j4("#event_id option:selected").val();
                                            j4("#wa").load("ajax.php?page=4&event_id=" + v  );
                                               
                                               
                                            
                                    }
                            });
                    }
                 });
      });
            
  </script>

                    <?
                } //page==5

                if ($page == 6) {
//                     print "<pre>"; print_r($_POST); print "</pre>";

                    if($_POST[athlete_to_delete])
                    {
                        
                        mysql_query("delete from ".$prefix."event_result where athlete_id = ".$_POST[athlete_to_delete]);
                        mysql_query("delete from ".$prefix."event_athlete where id = ".$_POST[athlete_to_delete]);
                    
                    }
                    
                    $event_id = $_POST[event_id];
                    $lname = $_POST[lname];
                    $fname = $_POST[fname];
                    $gender = $_POST[gender];
                    $cat = $_POST[category];
                    $ag = $_POST[ag];
                    $wod = $_POST[wod];
                    $atlname = $_POST[atlname];
                if($atlname)
                {
                    $lname = 0;
                    $fname = $atlname;
                }
                   
               
                    $sql_a = " INSERT INTO ".$prefix."event_athlete (event_id,fname,lname,gender,age_category,category) 
                     VALUE ($event_id,'" . $fname . "','" . $lname . "','" . $gender . "','" . $ag . "','" . $cat . "')";
                    mysql_query($sql_a);
                    $res = mysql_insert_id();


                    foreach ($wod as $key => $val) {
                        if(1 == preg_match('/[0-9][0-9]:[0-5][0-9]/', $val, $matches))
                        {
                            $a = explode(":", $val);
                            $val =  $a[0] * 60 + $a[1];
                        }
                        $sql_r = "INSERT INTO ".$prefix."event_result (athlete_id,wod_id,result) VALUES ($res,$key,'" . $val . "')";
                        mysql_query($sql_r);
                    }
                   // print "<pre>"; print $sql_a; print "<br><br>"; print $sql_r; print "<br><br>"; print_r($_POST);  print "</pre>";
                    echo 1;
                }
                
                //delete athlete by id
               if($page ==8)
               {
                  mysql_query("Delete from ".$prefix."event_athlete where id = ".$_POST[athlete_id]);
                  mysql_query("Delete from ".$prefix."event_result where athlete_id = ".$_POST[athlete_id]);
               }
                
               
               
               
               //edit athlete's results
               if($page == 9)
               {
                   
                   $sql_res = "select wer.athlete_id, wea.event_id, wea.fname, wea.lname, wea.gender, wea.age_category, wea.category, wer.wod_id, wer.result, weu.type
                       from ".$prefix."event_athlete wea, ".$prefix."event_result wer, ".$prefix."event_autoconfigurator weu
                        where wea.id = wer.athlete_id and weu.event_id = wea.event_id and weu.id = wer.wod_id
                        and wea.event_id = ".$_GET[event_id] ." AND wer.athlete_id = ". $_GET[athlete_id];
                   $qres = mysql_query($sql_res);
                   while($res = mysql_fetch_array($qres))
                   {    
                       $arr[id]    = $res[athlete_id];
                       $arr[fname] = $res[fname];
                       $arr[lname] = $res[lname];
                       $arr[gender]= $res[gender];
                       $arr[ag]    = $res[age_category];
                       $arr[cat]   = $res[category];
                       if($res[type] != 'time')
                       {
                       $arr[wod][$res[wod_id]] = $res[result];
                       }
                       else
                       {
                          $arr[wod][$res[wod_id]] = date("i:s", mktime(0,0,$res[result]));
                       }
                    }
               
                   
                    $sql_wod = "Select id,type,weight, `desc` from `".$prefix."event_autoconfigurator` where event_id = " . $_GET[event_id];

    $q_wod = mysql_query($sql_wod);
    if (!$q_wod) {
        print "Incorrect query";
    }
    $wod[] = null;
    $i = 0;
    while ($row = mysql_fetch_array($q_wod)) {
        $wod[$i][id] = $row[id];
        $wod[$i][type] = $row[type];
        $wod[$i][weight] = $row[weight];
        $wod[$i][desc] = $row[desc];
        ++$i;
    }
    
    ?>
<link rel="stylesheet" href="form_validator/css/validationEngine.jquery.css" type="text/css"/>
<script src="form_validator/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="form_validator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="jquery.maskedinput-1.2.2.js"></script>
<script>
        var j7 = jQuery.noConflict();
        
        j7(document).ready(function(){
                j7.mask.definitions['#']='[012345]';
                <? foreach($wod as $k => $v) { if($v[type] == 'time') { ?>
                j7("#wod\\[<? print $v[id]; ?>\\]").mask('#9:#9').val('<? print $arr[wod][$v[id]]; ?>');
           <?
                } else 
                {?>
                   
               <? }
                
           
           } ?>
                   
                   
                   
        });
</script>

<table border="0"  class="tablesorter">

        <tr class="rsform-block rsform-block-description">
            <td width="150"></td>
            <td><div class="formClr"></div></td>
            <td></td>
        </tr>
        
      <?  
        

                    
?>
         <tr class="rsform-block rsform-block-last-name">
            <td>Athlete *</td>
            <td>
<? if ($arr[lname] > 0) { ?>
                
                <select  name="lname2" id="lname2"  disabled>
                 <?   
                
                 $ssd3 = "Select name from ".$prefix."users where id = ".$arr[lname]." ";

                        $ssd = mysql_query($ssd3);
                 
                 while($ssd2 = mysql_fetch_array($ssd)) 
                     { 
                     $username = $ssd2[name]; 
                     
                     }
                
                 
                     print "<option selected value='".$arr[lname]."'> ".$username."";
                 
                 ?>
                </select>
                <input type="hidden" value="<?=$arr[lname]?>" name="lname" id="lname">

                <? } else { ?>
                
                
                name: <input type="text" id="atlname" name="atlname" size="15" value="<?=$arr[fname]?>"> 
            affiliate: <input type="text" id="gender" name="gender" size="15" value="<?=$arr[gender]?>"> 
            <input type="hidden" value="0" name="lname" id="lname">
            <input type="hidden" value="<?=$arr[id]?>" name="idathl" id="idathl">

                
                <?  } ?>
                
            </td>
            <td></td>
        </tr>
        
          <? /*
        <tr class="rsform-block rsform-block-last-name">
            <td>Last Name *</td>
            <td><input type="text" value="<?=$arr[lname]?>" size="20"  name="lname" id="lname"  class="validate[required] text-input"/></td>
            <td></td>
        </tr>
        
        <tr class="rsform-block rsform-block-first-name">
            <td>First Name *</td>
            <td><input type="text" value="<?=$arr[fname]?>" size="20"  name="fname" id="First Name"  class="validate[required] text-input"/></td>
            <td></td>
        </tr>
      
        <tr class="rsform-block rsform-block-gender">
            <td>Gender *</td>
            <td><select  name="gender"  id="Gender"  class="validate[required]" ><option  value=""></option><option  <? if($arr[gender]=='M')  { print "selected"; } ?> value="M">Men</option><option <? if($arr[gender]=='W')  { print "selected"; } ?>  value="W">Women</option></select></td>
            <td></td>
        </tr>
         
        
        <tr class="rsform-block rsform-block-category">
            <td>Category *</td>
            <td><select  name="category"  id="Category"  class="validate[required]" ><option  value=""></option><option <? if($arr[cat]=='Rx')  { print "selected"; } ?>  value="Rx">Rx</option><option <? if($arr[cat]=='Scaled')  { print "selected"; } ?>  value="Scaled">Scaled</option></select></td>
            <td></td>
        </tr>
 */?>
        <?php
        $sql_ag = "Select ag from `".$prefix."event_ag` where event_id = " . $_GET[event_id];
        $q_ag = mysql_query($sql_ag) or die("Incorrect query: " . $sql_ag);
        $ag_cnt = mysql_num_rows($q_ag);


        if ($ag_cnt > 0) {
            ?>
            <tr class="rsform-block rsform-block-category">
                <td>Category *</td>
                <td><select  name="ag" <? if($arr[ag]=='All')  { print "selected"; } ?>  id="Category" class="validate[required]" >
        <?php
        while ($row = mysql_fetch_array($q_ag)) {


             ?> <option <? if($arr[ag]==$row[ag])  { print "selected"; } ?> value="<?=$row[ag]?>"><?=$row[ag]?> <?
        }
        ?>
                    </select></td>
                <td></td>
            </tr>
    <? } ?>



    <?php
    for ($i = 0; $i < count($wod); ++$i) {
        ?>

            <tr class="rsform-block rsform-block-event-1">
                <td>       <? print "WOD #" . ($i + 1);
        print " (" . $wod[$i][type] . ")"; ?>*</td>
                <td><sub><? print $wod[$i][desc]; ?></sub><br><input <? if ($wod[$i][type] == 'time'){ print 'class="validate[required]"'; } else { print 'class="validate[required,custom[number],min[0]]"';} ?>  type="text" value="<? print $arr[wod][$wod[$i][id]]; ?>" name="wod[<? print $wod[$i][id]; ?>]" id="wod[<? print $wod[$i][id]; ?>]"></td>
                <td></td>
            </tr>

        <?php }
        ?>

        <tr class="rsform-block rsform-block-submit-your-results">
            <td></td>
            <td><input type="hidden" id="athlete_to_delete" name="athlete_to_delete" value="<?=$arr[id]?>"><input type="hidden" id="event_id" name="event_id" value="<? print $_GET[event_id]; ?>"><input type="Button" value="Submit" name="form[Submit Your Results]" id="sbm"  class="rsform-submit-button" style="height: 25px; width: 60px" /><div class="formClr" ></div></td>
            <td></td>
        </tr>

    </table>
    
    <div id="info"></div>
<script>
    
     var j4 = jQuery.noConflict();
      j4(document).ready(function(){
              j4("#sbm").click(function(){
                if (j4("#frm").validationEngine('validate') == true)
                    {
                   j4("#sbm").prop('disabled','disabled');
                   
                   var datas = j4('#frm').serialize();
         // alert("34")
                    // Отсылаем паметры
                           j4.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=6",
                                    data: datas,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                       //  alert(html);
                                            
                                                var v =     j4("#event_id option:selected").val();
                                               j4("#wa").load("ajax.php?page=4&event_id=" + v + "&ppage=" + <?=$_GET[ppage]?>  );
                                               
                                               
                                            
                                    }
                            });
                    }
                 });
      });
            
  </script>

                    <?
                
                   
                   
                   
               }
               if ($ppage == "live") // LIVE RESULTS
               {
    print "                   Please select event<br>";
                   
               }
                   
                   if($page == 10)//share access
                   {
                       ?>
                       <script>
    
     var j11 = jQuery.noConflict();
      j11(document).ready(function(){
       // Отсылаем паметры
      j11("#shab").click( function() {
        var datas = j11('#frm').serialize();
        var v =     j11("#event_id option:selected").val();
                           j11.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=11&event_id=" + v,
                                    data: datas,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                        
                                            
                                                
                                               j11("#wa").load("ajax.php?page=4&event_id=" + v  );
                                               
                                               
                                            

                                    }
                            });
                });
      });
          </script>
                       
                       <?
                      $sql = "select id, name from sfvi3_users u, sfvi3_user_usergroup_map g 
                          where g.group_id = 10 and u.id = g.user_id and u.id not in (select user_id from sfvi3_event_access where event_id = ".$_GET[event_id].")";
                       $z = mysql_query($sql);
                       
                       print "Select name:  <select id='sha' name='sha'> ";
                       
                       while ($r = mysql_fetch_array($z))
                       {
                           print "<option value='".$r[id]."'>".$r[name]."";
                       }
                       print "<input type=hidden value=grant name=act id=act><input type=button value=OK id='shab'>";
                       print "</select>";
                   }//10
               if ($page == 11 ) //set access
               {
                    if($_POST[act] == "grant")
                    {
                        mysql_query("insert into ".$prefix."event_access (event_id,user_id) values (".$_POST[event_id].",".$_POST[sha].") ");

                    }
                    if($_POST[act] == "revoke")
                    {
                        mysql_query("delete from ".$prefix."event_access where event_id = ".$_POST[event_id]." and user_id =".$_POST[sha]." ");

                    }
               }
               
               
                   if($page == 12)//revoke access
                   {
                       ?>
                       <script>
    
     var j11 = jQuery.noConflict();
      j11(document).ready(function(){
       // Отсылаем паметры
      j11("#shab").click( function() {
        var datas = j11('#frm').serialize();
        var v =     j11("#event_id option:selected").val();
                           j11.ajax({
                                    type: "POST",
                                    url: "ajax.php?page=11&event_id=" + v,
                                    data: datas,
                                    // Выводим то что вернул PHP
                                    success: function(html) {
                                        
                                            
                                                
                                               j11("#wa").load("ajax.php?page=4&event_id=" + v  );
                                               
                                               
                                            
                                    }
                            });
                });
      });
          </script>
                       
                       <?
                      $sql = "select id, name from sfvi3_users u, sfvi3_user_usergroup_map g 
                          where g.group_id = 10 and u.id = g.user_id and u.id  in (select user_id from sfvi3_event_access where event_id = ".$_GET[event_id].")";
                       $z = mysql_query($sql);
                       
                       print "Select name:  <select id='sha' name='sha'> ";
                       
                       while ($r = mysql_fetch_array($z))
                       {
                           print "<option value='".$r[id]."'>".$r[name]."";
                       }
                       print "<input type=hidden value=revoke name=act id=act><input type=button value=OK id='shab'>";
                       print "</select>";
                   }//12
               
               if($page == 13) //populate tables
               {
                   


                   $eid = $_GET[event_id];
                    //delete any data related to this event
         //          print "delete from ".$prefix."event_result where athlete_id in (Select id from ".$prefix."event_athlete where event_id = ".$eid.")";
                   mysql_query("delete from ".$prefix."event_result where athlete_id in (Select id from ".$prefix."event_athlete where event_id = ".$eid.")") or die(" sql1 = ".mysql_error());
                   mysql_query("delete from ".$prefix."event_athlete where event_id = ".$eid."") or die(" sql2 = ".mysql_error());
                   
                   //insert new data
                   $sql = "select o.created_by FROM ".$prefix."ohanah_registrations o 
                     where o.ohanah_event_id = ".$eid." or o.ohanah_event_id in (select sub_event from ".$prefix."event_sev where master_event = ".$eid.")";
                   $f = mysql_query($sql) or die( " sql3 = ". mysql_error());
                   while($a = mysql_fetch_array($f))
                   {
                       mysql_query("Insert into ".$prefix."event_athlete (event_id,lname,age_category) Values (".$eid.",".$a[created_by].",'All') ;")  or die(" sql4 = ". mysql_error());
                       $id =  mysql_insert_id();
                       $z = mysql_query("Select id from ".$prefix."event_autoconfigurator where event_id = ".$eid."") or die(" sql5 = ".mysql_error());
                       while($z2 = mysql_fetch_array($z))
                       {
                           mysql_query("insert into ".$prefix."event_result (athlete_id,wod_id,result) VALUES(".$id.",".$z2[id].",0) ;") or die(" sql6 = ".mysql_error());;
                       }
                   }
                echo "Done!";  
               }
               
               
               if ($page == 14)
               {
               	   	//echo 1;
				$date = DateTime::createFromFormat('F jS, Y', $_GET[dates]);
				$aaa = mysql_query("select count(*) as cnt from ".$prefix."event_avl where event_id = ".$_GET[event_id]."");
				while($aaa1 = mysql_fetch_array($aaa)) { $cn = $aaa1[cnt]; }
				if($cn < 1)
				{
				$sql = "insert into ".$prefix."event_avl (event_id,avl) values (".$_GET[event_id].", '".$date->format('Y-m-d')."')";
				}
				if($cn > 0)
				{
				$sql = "update ".$prefix."event_avl set avl = '".$date->format('Y-m-d')."' where event_id = ".$_GET[event_id]."";	
				}
				
				mysql_query($sql) or die($sql);      
			//	echo 2;
				         }
               
               
//THIS FILE IS TOOOOOOO BIG. CONTINUED IN AJAX2.PHP               
 require_once("ajax2.php");
               
                mysql_close($c);
                ?>
 

<? 
/*
print "<pre>";
print_r($_POST);

print_r($_GET);
print_r($wod);
print_r($arr);
print_r($arr3);
print_r($arr4); 
print "</pre>";
*/
  ?> 


