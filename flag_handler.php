<?php 
    $index = $_POST['index'];
    $flag = $_POST['flag'];
    $file = 'received_changes.csv';
    $current = file_get_contents($file);
    $current .= $index . ",". $flag ."\n";
    echo $current;
    file_put_contents($file, $current) or die('write failed'); 
    //echo "Appended ". $index . ",". $flag . " to csv";
?>