<?php 
    $index = $_POST['index']."  ";
    $flag = $_POST['flag'];
    $file = 'recieved_changes.csv';
    $current = file_get_contents($file);
    $current .= $index . ",". $flag ."\n";
    file_put_contents($file, $current);
    echo "Appended ". $index . ",". $flag . " to csv";
?>