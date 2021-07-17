<?php

require 'php/config.php';
require 'php/Class/Db.php';

$db = new Db(DB_NAME, DB_HOST, DB_USER, DB_PASS);


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-sacalable=no">
    <title>Gestão de Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body><br>
    <section class="container">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <?php
                // se houver um clique de venda

                if(isset($_GET['id_venda']) && !empty($_GET['id_venda']))
                {
                    $id = addslashes($_GET['id_venda']);
                    
                    if($db->retirarEstoque($id))
                    {
                        echo "<div class='alert alert-success'>Venda computada.</div>";
                    }else{
                        echo "<div class='alert alert-danger'>Não há mais itens.</div>";
                    }
                }

                // se houver um post de um novo produto

                if(isset($_POST['produto']))
                {


                    if(isset($_GET['id_alter']) && !empty($_GET['id_alter'])){
                    
                        $id = $_GET['id_alter'];
                        $nome = addslashes($_POST['produto']);
                        $valor = addslashes($_POST['valor']);
                        $qnt = addslashes($_POST['qnt']);    

                        if(!empty($nome) && !empty($valor) && !empty($qnt)){
                            $db->atualizarProduto($id, $nome ,$valor ,$qnt);
                        }
                        else{
                            echo "<div class='alert alert-danger'>Preencha todos os campos.</div>";
                        }
                        
                    }
                    else{
                        $nome = addslashes($_POST['produto']);
                        $valor = addslashes($_POST['valor']);
                        $qnt = addslashes($_POST['qnt']);
                        if(!empty($nome) && !empty($valor) && !empty($qnt)){

                            if($db->inserirProduto($nome, $valor, $qnt))
                            {
                                echo "<div class='alert alert-success'>Inserido com sucesso.</div>";
                            }
                        }
                        else{
                            echo "<div class='alert alert-danger'>Preencha todos os campos.</div>";
                        }
                    }
                }


                if(isset($_GET['id_add']) && !empty($_GET['id_add']))
                {
                    $id = addslashes($_GET['id_add']);
                
                    if($db->adicionarEstoque($id)){
                        echo "<div class='alert alert-success'>Item adicionado com sucesso.</div>";
                    }
                }

                // se a pessoa clicou pra editar

                if(isset($_GET['id_alter']) && !empty($_GET['id_alter']))
                {
                    $id = addslashes($_GET['id_alter']);

                    $dados = $db->alterarEstoque($id);
                }
                ?>
                <form method="post">
                    <label for="nome">Nome do produto</label>
                    <input class="form-control" type="text" name="produto" id="" value="<?php if(isset($dados)){echo $dados[0]['nome_produto'];} ?>">
                    <label for="nome">Valor do produto</label>
                    <input class="form-control" type="number" step="0.01" name="valor" id="" value="<?php if(isset($dados)){echo $dados[0]['valor_produto'];} ?>">
                    <label for="nome">Qnt do produto</label>
                    <input class="form-control" type="number" name="qnt" id="" value="<?php if(isset($dados)){echo $dados[0]['qnt_produto'];} ?>"><br>
                    <input class="btn btn-primary" type="submit" value="<?php if(isset($dados)){ echo "Alterar"; }else{echo "Cadastrar";} ?>">
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <br>
        <div class="row">
                <?php
                $dados = $db->buscarProdutos();
                if(count($dados) > 0)
                {
                    ?>
                    
                    <div class="col-sm-3"></div>

                    <div class="col-sm-6">
                        <table class="table">
                            <tr>
                                <th>Produtos</th>
                                <th>Valor</th>
                                <th>Qnt</th>
                                <th colspan="3">Ação</th>
                            </tr>
                    <?php
                    
                    foreach($dados as $info)
                    {
                    ?>

                        <tr>
                            <td><?php echo $info['nome_produto']; ?></td>
                            <td><?php echo $info['valor_produto']; ?></td>
                            <td><?php echo $info['qnt_produto']; ?></td>
                            <td><a class="btn btn-success" href="index.php?id_add=<?php echo $info['id']; ?>">Adicionar</a></td>
                            <td><a class="btn btn-warning" href="index.php?id_alter=<?php echo $info['id']; ?>">Alterar</a></td>
                            <td><a class="btn btn-danger" href="index.php?id_venda=<?php echo $info['id']; ?>">Vender</a></td>
                            
                        </tr>

                    <?php
                        }
                        ?>
                            </table>
                            <div class="col-sm-3"></div>     
                        <?php
                    }
                    else{
                        echo "<div class='col-sm-3'></div><div class='col-sm-6'><div class='alert alert-default'>Não há dados a serem exibidos.</div></div><div class='col-sm-3'></div>";
                    }
                    ?>
            </div>
        </div>
    </section>    



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
</body>
</html>