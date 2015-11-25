<?php 
    $index = $_POST['index'];
    $flag = $_POST['flag'];
    $file = 'bin/received_changes.csv';
    if ( !file_exists('bin') ) {
        $oldmask = umask(0);
        mkdir('bin', 0744);
    }
    if ( file_exists($file) ) {
        $current = file_get_contents($file);
    } else {
        $current = "";
    }
    $current .= $index . ",". $flag ."\n";
    echo $current;
    file_put_contents($file, $current) or die('write failed'); 
    //echo "Appended ". $index . ",". $flag . " to csv";
?>