<?php 
    $index = $_POST['index'];
    $flag = $_POST['flag'];
    $file = 'php_bin/received_changed.csv';
    if ( file_exists($file) ) {
        $current = file_get_contents($file);
        echo "can see file";
    } else {
        $current = "test";
    }
    $current .= $index . ",". $flag ."\n";
    echo $current;
    file_put_contents($file, $current) or die('write failed'); 
?>