<?php

class Db{

    private $pdo;

    //Construtor
    public function __construct($dnmae, $host, $usuario, $senha)
    {
        try {
            $this->pdo = new PDO("mysql:dbname=".$dnmae.";host=".$host, $usuario, $senha);
        } catch (PDOException $e) {
            echo "Erro no banco de dados: ".$e->getMessage();
        }
        catch(Exception $e){
            echo "Erro: ".$e->getMessage();
        }
    }

    public function inserirProduto($nome, $valor, $qnt)
    {
        $cmd = $this->pdo->prepare("INSERT INTO produtos (nome_produto, valor_produto, qnt_produto) 
            VALUES (:n, :v, :q)");
        $cmd->bindValue(":n",$nome);
        $cmd->bindValue(":v",$valor);
        $cmd->bindValue(":q",$qnt);
        $cmd->execute();
        return true;
    }

    public function buscarProdutos()
    {
        $cmd = $this->pdo->prepare("SELECT * FROM produtos");
        $cmd->execute();
        $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    }

    public function retirarEstoque($id)
    {
        $cmd = $this->pdo->prepare("SELECT qnt_produto FROM produtos WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        
        $qnt = $cmd->fetchAll(PDO::FETCH_ASSOC);
        
        $qnt = $qnt[0]['qnt_produto'];

        if($qnt > 0)
        {
            $cmd = $this->pdo->prepare("UPDATE produtos SET qnt_produto = qnt_produto - 1 WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
            return true;
        }
        else{
            return false;
        }
    }

    public function adicionarEstoque($id)
    {
        $cmd = $this->pdo->prepare("UPDATE produtos SET qnt_produto = qnt_produto + 1 WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        return true;
    }

    public function alterarEstoque($id)
    {
        $cmd = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    }

    public function atualizarProduto($id, $nome, $valor, $qnt)
    {
        $cmd = $this->pdo->prepare("UPDATE produtos 
            SET nome_produto = :np, valor_produto = :vp, qnt_produto = :qp WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->bindValue(":np",$nome);
        $cmd->bindValue(":vp",$valor);
        $cmd->bindValue(":qp",$qnt);
        $cmd->execute();
        return true;
    }

}


?>