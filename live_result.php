<?
$user =& JFactory::getUser();
$uid = $user->get('id');


$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = "";
for ($i = 0; $i < count($parts) - 1; $i++) {
    
 $dir .= $parts[$i] . "/";
} 



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
                 j("#wa").load("<?=$dir?>ajax.php?ppage=1&page=1&event_id=" + <?= $_GET[evn] ?> +"&user_id=<?=$uid?>");
             });
<? } ?>
     
     
             j(document).ready(function(){
        
                 j("#event_id").change(function(event){
                     var v = j("#event_id option:selected").val();
                     if(v > 0)
                     {
                         j("#wa").load("<?=$dir?>ajax.php?ppage=1&page=4&event_id=" + v +"&user_id=<?=$uid?>");
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


?>
<form action="ajax.php?page=6" method="post" id="frm">    
    <?php
    $sql = "select ohanah_event_id as id, title from ".$prefix."ohanah_events where title is not null
        and ohanah_event_id not in (select sub_event from ".$prefix."event_sev) and ohanah_event_id in (select event_id from ".$prefix."event_avl where avl  < now() )" ;
    $res = mysql_query($sql) or die("Invalid query: " . mysql_error() ." <br><br> ". $sql);
    ;
    $str = "<select name=\"event_id\" id=\"event_id\" >";
    $str .= "<option value=\"0\">---Please select event---";

    while ($row = mysql_fetch_array($res)) {
        $str .= "<option value=" . $row[id] . "> " . $row[title];
    }
    $str .= "</select>";
    echo $str;
    echo "<div id=\"wa\"></div>";
    ?></form><?
    mysql_close($c);
    ?>
