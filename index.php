<?php
    if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
			header("Location: cadastro.php");
		}else{
			die();
		}
		
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>CRUD com PHP, de forma simples e fácil</title>
  </head>
  <body>
		<div>C-mom cadastrar! men</div>
  </body>
</html>