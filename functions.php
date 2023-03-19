<?php
function redirect($url){
echo'<script>document.location="'.$url.'"</script>';
} 


function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}
?>
