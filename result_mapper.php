<?php

$asd = new JConfig;
$prefix = $asd->dbprefix;
$c = mysql_connect($asd->host, $asd->user, $asd->password) or die("no db connect");
mysql_select_db($asd->db);


$dir = "/";
?>

<script src="jquery.js"></script>

<link rel="stylesheet" type="text/css" href="form_validator/anytime.css" />
<script src="form_validator/anytime.js"></script>


<script>
    var j = jQuery.noConflict();
    j(document).ready(function(){
        j("#event_id").change(function(event){
            AnyTime.noPicker("field1");
            var v = j("#event_id option:selected").val();
            j("#wa").load("<?=$dir?>ajax.php?page=load&event_id=" + v);
         	  
        });
    });
<?php if ($_GET[event_id]) {
    ?>
           j(window).load( function() {
           	   
               j('#event_id').val('<?= $_GET[event_id] ?>');
               j("#wa").load("<?=$dir?>ajax.php?page=load&event_id=<?= $_GET[event_id] ?>");

           });
         
<?php } ?>
        function addmore()
        {
    
            var wod_cnt = document.getElementById("wod_cnt");
            if(wod_cnt > 20)
            {
                alert("Limit 20 wods");
                exit;
            }
            var wod     = ++wod_cnt.value ;
            var elem    = document.getElementById('pwod'+wod);
            elem.innerHTML += '<h3>WOD #'+ wod +' <input type="button"  id="delid'+ wod + '"  onclick="delid('+ wod +');" value="delete"> </h3> ';
            elem.innerHTML += ' <input class="validate[required] radio" type="radio" value="weight" name="WOD'+ wod +'" id="WOD'+wod+'"> Weight';
            elem.innerHTML += ' <input class="validate[required] radio" type="radio" value="time" name="WOD'+ wod +'" id="WOD'+ wod +'"> Time ';
            elem.innerHTML += '<input  class="validate[required] radio" type="radio" value="reps" name="WOD'+ wod +'" id="WOD'+ wod +'"> Reps ';
            elem.innerHTML += '<input   class="validate[required] radio" type="radio" value="dist" name="WOD'+ wod +'" id="WOD'+ wod +'"> Distance';
            elem.innerHTML += ' |  Percent(%): <input size="3" class="validate[required,custom[number],min[0],max[100]] zzz" type="text" value="" name="WOD'+ wod +'_percent" id="WOD'+ wod +'_percent"> '
            elem.innerHTML += '  Description<input type="text" class="validate[required] input-text" maxlength="15" size="18"  name="WOD'+wod+'_desc" id="WOD'+wod+'_desc"> <br>';
            wod_cnt.value = wod;
    
        }
 
   
</script>
<form action="<?=$dir?>ajax.php?page=2" method="post" id="frm">    
    <?php
    
    
$user =& JFactory::getUser();
//usergroups with unlimited access 

 // 8 = superadmin.
if(in_array(8, $user->groups)){
// 'You are an Administrator';
$user_filter = "";
    
}
else{
// 'You are just an ordinary user';

    $user_filter = " AND created_by = ".$user->get('id');
}

    $sql = "select ohanah_event_id as id, title from ".$prefix."ohanah_events where title is not null $user_filter
            and ohanah_event_id not in (select sub_event from ".$prefix."event_sev)";
    $res = mysql_query($sql) or die("Invalid query: " . mysql_error());
    ;
    $str = "<select name=\"event_id\" id=\"event_id\" >";
    $str .= "<option value=\"\">---Please select event---";

    while ($row = mysql_fetch_array($res)) {
        $str .= "<option value=" . $row[id] . "> " . $row[title];
    }
    $str .= "</select>";
    echo $str;
    echo "<div id=\"wa\"></div>";
    ?></form><?
    mysql_close($c);
    ?>
