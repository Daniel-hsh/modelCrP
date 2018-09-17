<?php
    $link_conexao = mysqli_connect("127.0.0.1","root","","CRUDprocess");

    if (!$link_conexao) {
        die("Ocorreu um erro na conexão com o banco de dados!");
    }
?>