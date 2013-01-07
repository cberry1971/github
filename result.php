<?


$url = $_SERVER['REQUEST_URI']; //returns the current URL


$parts = explode('/',$url);
$dir = "";
for ($i = 0; $i < count($parts) - 1; $i++) {
    
 $dir .= $parts[$i] . "/";
} 

$dir = "";
?>

<script src="jquery.js"></script>
    <script type="text/javascript" src="sorttable.js"></script> 

<script>
    var j = jQuery.noConflict();
     
<?php

if ($_GET[evn]) {
    ?>
             j(window).load( function() { 
                
                 j('#event_id').val('<?= $_GET[evn] ?>');
                 j("#wa").load("<?=$dir?>ajax.php?ppage=2&page=4&event_id=" + <?= $_GET[evn] ?>);
             });
<? } ?>
     
     
             j(document).ready(function(){
        
                 j("#event_id").change(function(event){
                     var v2 = j("#event_id option:selected");
                     cr = v2.attr("cr");
                     v = v2.val();
                     if(v > 0)
                     {
                         j("#wa").load("<?=$dir?>ajax.php?ppage=" + cr + "&page=4&event_id=" + v);
                     }
                 });
               
             });
     
     
</script>




<?php

$asd = new JConfig;
$c = mysql_connect($asd->host, $asd->user, $asd->password) or die("no db connect");
mysql_select_db($asd->db);


//select event
$prefix = $asd->dbprefix;
$user =& JFactory::getUser();
//usergroups with unlimited access 

 // 8 = superadmin.
if(in_array(8, $user->groups)){
// 'You are an Administrator';
$user_filter = "";
    $isadmin = 1;
}
else{
// 'You are just an ordinary user';

     $user_filter = " AND (created_by = ".$user->get('id')." OR ohanah_event_id in (

select e.ohanah_event_id 

from ".$prefix."ohanah_events e, ".$prefix."comprofiler_members m

where e.created_by = m.referenceid

and m.type = 'Judge'

and m.memberid = ".$user->get('id')."        

))" ;
}

?>
<form action="ajax.php?page=6" method="post" id="frm">    
    <?php
    $sql = "select ohanah_event_id as id, title, created_by from ".$prefix."ohanah_events where title is not null $user_filter
            and ohanah_event_id not in (select sub_event from ".$prefix."event_sev)";
  
    $res = mysql_query($sql) or die("Invalid query: " . mysql_error());
    
    $str = "<select name=\"event_id\" id=\"event_id\" onChange=\"event_id_cn\">";
    $str .= "<option value=\"0\">---Please select event---";

    while ($row = mysql_fetch_array($res)) {
        if($row[created_by] == $user->get('id') or $isadmin == 1)
        {
            $zz = " cr='0' ";
        }
        else
        {
            $zz = " cr='4' ";
        }
        
        $str .= "<option $zz value=" . $row[id] . "> " . $row[title];
    }
    $str .= "</select>";
    echo $str;
    echo "<div id=\"wa\"></div>";
    ?></form><?
    mysql_close($c);
    ?>
