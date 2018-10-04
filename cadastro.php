<?
    include_once("connect.php");
    include_once("set_character.php");
    
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
        else
        {
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>CRUD com PHP</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=7"><!-- Bootstrap CSS 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
  </head>
  <body>
    <div class="container-fluid">
    <nav class="navbar navbar-inverse">
                    <div class="container-fluid">
                      <div class="navbar-header">
                        <a class="navbar-brand" href="/RHACTS/manter_funcionarios.jsp"><img src="/RHACTS/css/logo0.png"style="width: 76%; margin: -1vh"></a>
                      </div>
                      <ul class="nav navbar-nav">
                        <li><a href="/RHACTS/manter_funcionarios.jsp">Link1</a></li>
                        <li><a href="/RHACTS/manter_departamentos.jsp">Link2</a></li>
                        <li><a href="/RHACTS/manter_cargos.jsp">Link3</a></li>
                        <li><a href="/RHACTS/manter_folhas.jsp">Link4</a></li>
                        <li><a href="/RHACTS/RelatoriosGerente">Link4</a></li>
                      </ul>
                      <ul class="nav navbar-nav navbar-right">
                        <li>
                            <div style="margin-top: 2vh; color: #ccc; ">
                                Bem vindo
                            </div>
                        </li>
                        <li><a href="/RHACTS/ProcessaLogout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                      </ul>
                    </div>
    </nav>
    </div>
    <div class="container">
    <main role="main">
        <div class="row form">
            <div class="col-sm-5">
                <?
                    if(isset($erro))
                        echo '<div style="color:#F00">'.$erro.'</div><br /><br />';
                    else
                        if(isset($sucesso))
                            echo '<div style="color:#00f">'.$sucesso.'</div><br /><br />';
                ?>
                <form action="<?=$_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="form-group row">
                        <input class="form-control" type="text" name="nome" value="<?=$nome?>" placeholder="Enter name">
                    </div>
                    <div class="form-group row">
                        <input class="form-control" type="email" name="email" value="<?=$email?>" placeholder="Enter with valid Email (example@example.com)">
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <select class="form-control d-inline-block " id="estados" name="uf">
                                <option value="" placeholder="State"></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control d-inline-block " id="cidades" name="cidade" value=<?=$cidade?>></select>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <input class="form-control d-inline-block " type="hidden" name="id" value="-1" >
                        </div>
                    </div>
                    <!--Alteracao feita aqui para mostrar o texto Cadastrar ou salvar de acordo com o momento-->
                    <div class="form-group row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary"><?=($id==-1)?"Cadastrar":"Salvar" ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class= "table" width="400px" border="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">UF</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?
                    $result = $obj_mysqli->query("SELECT * FROM `cliente` ");
                    while ($aux_query = $result->fetch_assoc()) {
                        echo '<tr scope="row">';
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
            </tbody>
        </table>
    </main>
    </div>
    <!-- jQuery JS 3.1.0 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="combo_dinamico.js"></script>
    <!-- Bootstrap JS 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>