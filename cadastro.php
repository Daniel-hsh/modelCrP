<?
    $obj_mysqli = new mysqli("127.0.0.1","root","","CRUDprocess");

    if ($obj_mysqli->connect_errno) {
        echo "Ocorreu um erro na conexão com o banco  de dados.";
        exit;
    }

    mysqli_set_charset($obj_mysqli, 'utf-8'); //OS DADOS SERAO TRABALHADOS NA FORMA UTF-8

    //Inserção variaveis registro (Alteração update)
    $id = -1;
    $nome = "";
    $email = "";
    $cidade = "";
    $uf = "";


    //validação de dados existentes
    if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["cidade"]) && isset($_POST["uf"]))
    {
        if (empty($_POST["nome"]))
            $erro = "Campo nome obrigatorio!";
        else{
            if (empty($_POST["email"])) {
                $erro = "Campo email obrigatorio!";
            } 
            else 
            {
                //CADASTRO DE ITEM / PUXAR OS DADOS DO FORMULARIO

                $id = $_POST["id"];
                $nome = $_POST["nome"];
                $email = $_POST["email"];
                $cidade = $_POST["cidade"];
                $uf = $_POST["uf"];

                //PreparedStatement do PHP
                if($id == -1)
                {
                    $stmt = $obj_mysqli->prepare("INSERT INTO `cliente`(`nome`, `email`, `cidade`, `uf`) VALUES (?,?,?,?)");
                    $stmt->bind_param('ssss', $nome, $email, $cidade, $uf);
    
                    if(!$stmt->execute()){
                        $erro = $stmt->error;
                    }
                    else
                    {
                        header("Location: cadastro.php");
                        exit;
                    }
                }
                else
                {
                    if(is_numeric($id) && $id >= 1)
                    {
                        $stmt = $obj_mysqli->prepare("UPDATE `cliente` SET `nome`=?, `email`=?, `cidade`=?, `uf`=? WHERE id=?");
                        $stmt->bind_param('ssssi',$nome,$email,$cidade,$uf,$id);

                        if(!$stmt->execute())
                        {
                            $erro = $stmt->error;
                        }
                        else
                        {
                            header("Location: cadastro.php");
                            exit;
                        }
                    }
                    //retorna um erro
                    else
                    {
                        $erro = "Numero Inválido";
                    }
                }
            }
        }
             
    }
    else
    {
        if (isset($_GET["id"]) && is_numeric($_GET["id"])) 
            $id = (int)$_GET["id"];

        if (isset($_GET["del"]))
        {
            $stmt = $obj_mysqli->prepare("DELETE FROM `cliente` WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            header("Location: cadastro.php");
            exit;
        }
        else 
        {
            $stmt = $obj_mysqli->prepare("SELECT * FROM `cliente` WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $result = $stmt->get_result();
                $aux_query = $result->fetch_assoc();

            $nome = $aux_query["Nome"];
            $email = $aux_query["Email"];
            $cidade = $aux_query["Cidade"];
            $uf = $aux_query["UF"];

            $stmt->close();            
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>CRUD com PHP, de forma simples e fácil</title>
  </head>
  <body>
    <?
        if(isset($erro))
            echo '<div style="color:#F00">'.$erro.'</div><br /><br />';
        else
            if(isset($sucesso))
                echo '<div style="color:#00f">'.$sucesso.'</div><br /><br />';
    ?>
	<form action="<?=$_SERVER['PHP_SELF'] ?>" method="POST">
	  Nome:<br/> 
	  <input type="text" name="nome" placeholder="Qual seu nome?" value="<?=$nome?>"><br/><br/>
	  E-mail:<br/> 
	  <input type="email" name="email" placeholder="Qual seu e-mail?" value="<?=$email?>"><br/><br/>
	  Cidade:<br/> 
	  <input type="text" name="cidade" placeholder="Qual sua cidade?" value="<?=$cidade?>"><br/><br/>
	  UF:<br/> 
	  <input type="text" name="uf" size="2" placeholder="UF" value="<?=$uf?>">
	  <br/><br/>
      <input type="hidden" value="-1" name="id">
        <!--Alteracao feita aqui para mostrar o texto Cadastrar ou salvar de acordo com o momento-->
	  <button type="submit"><?=($id==-1)?"Cadastrar":"Salvar" ?></button>
    </form>
    <br />
    <br />

    <table width="400px" border="0" cellspacing="0">
        <tr>
            <td><strong>#</strong></td>
            <td><strong>Nome</strong></td>
            <td><strong>Email</strong></td>
            <td><strong>Cidade</strong></td>
            <td><strong>UF</strong></td>
        </tr>

        <?
            $result = $obj_mysqli->query("SELECT * FROM `cliente` ");
            while ($aux_query = $result->fetch_assoc()) {
                echo '<tr>';
                echo ' <td>'.$aux_query["Id"].'</td> ';
                echo ' <td>'.$aux_query["Nome"].'</td> ';
                echo ' <td>'.$aux_query["Email"].'</td> ';
                echo ' <td>'.$aux_query["Cidade"].'</td> ';
                echo ' <td>'.$aux_query["UF"].'</td> ';
                echo ' <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["Id"].'">Editar</a></td>';//Por aqui passa o ID - EDITAR REGISTRO
                echo ' <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["Id"].'&del=true">Excluir</a></td>';//Por aqui passa o ID - DELETAR REGISTRO
                echo '</tr>';
            }
        ?>
    </table>


  </body>
</html>