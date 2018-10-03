<?php
    include_once("credentials.php");

    $obj_mysqli = new mysqli(hostName,hostLogin,hostPass,bdName);

    if ($obj_mysqli->connect_errno) {
        echo "Ocorreu um erro na conexão com o banco  de dados.";
        exit;
    }
?>

