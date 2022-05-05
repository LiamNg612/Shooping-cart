<?php 

    $handle=fopen(__DIR__.'/123.php',"w");
    echo(__DIR__.'123.php');
    //$temple=fopen(__DIR__.'/Food.php',"r");
    echo(__DIR__.'Food.php');
    fwrite($handle, file_get_contents(__DIR__.'/Food.php'));
    echo("success");
    fclose($handle);
    fclose($temple);
?>