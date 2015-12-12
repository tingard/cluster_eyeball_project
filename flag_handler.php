<?php 
    $index = $_POST['index'];
    $flag = $_POST['flag'];
    $name = $_POST['name'];
    $file = './php_bin/received_changed.csv';
    if ( file_exists($file) ) {
        $current = file_get_contents($file);
    } else {
        $current = "";
    }
    $current .= $index . ",". $flag . "," . $name . "\n";
    //echo $index . "  " . $flag;
    file_put_contents($file, $current) or die('write failed'); 
    echo "Changes Added to approval list";
?>