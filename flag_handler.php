<?php 
    $index = $_POST['index'];
    $flag = $_POST['flag'];
    $file = '../received_changes.csv';
    if ( file_exists($file) ) {
        $current = file_get_contents($file);
    } else {
        $current = "";
    }
    $current .= $index . ",". $flag ."\n";
    echo $current;
    file_put_contents($file, $current) or die('write failed'); 
?>