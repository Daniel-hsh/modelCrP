<?php
    include_once("connect.php");
    
    $stmt = $obj_mysqli->prepare("ALTER TABLE tablename MODIFY columnname INTEGER");
        $stmt->bind_param('ssss', $nome, $email, $cidade, $uf);
    
    if(!$stmt->execute()){
        $erro = $stmt->error;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
    <?
        if(isset($erro))
            echo '<div style="color:#F00">'.$erro.'</div><br /><br />';
        else
            if(isset($sucesso))
            {
                echo '<div style="color:#00f">'.$sucesso.'</div><br /><br />';
                header("Location: cadastro.php");
                exit;
            }       
    ?>
</body>
</html>