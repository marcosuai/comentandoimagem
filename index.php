<?php
session_start(); // Inicia a sessão

$mensagem = ""; // Variável para armazenar a mensagem de retorno

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'comentarios');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $conn->real_escape_string($_POST['nome_usuario']);
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $imagem = 'img01.jpg'; // Nome da imagem associada

    // Verificar se o usuário já fez um comentário
    $sql_check = "SELECT * FROM comentarios WHERE nome='$nome_usuario'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $mensagem = "Você já fez um comentário. Apenas um comentário por usuário é permitido.";
    } else {
        // Inserir comentário no banco de dados
        $sql_insert = "INSERT INTO comentarios (nome, comentario, imagem) VALUES ('$nome_usuario', '$comentario', '$imagem')";

        if ($conn->query($sql_insert) === TRUE) {
            $mensagem = "Comentário enviado com sucesso!";
            // Define a variável de sessão para marcar que o usuário já fez um comentário
            $_SESSION['nome_usuario'] = $nome_usuario;
        } else {
            $mensagem = "Erro: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comente sobre a Imagem</title>
    <link rel="stylesheet" href="estilo.css">
    <script>
        // Função para exibir a mensagem em um pop-up
        function mostrarMensagem(mensagem) {
            if (mensagem) {
                alert(mensagem);
            }
        }
    </script>
</head>
<body onload="mostrarMensagem('<?php echo $mensagem; ?>')">
    <div class="container">
        <h1>Comente sobre a Imagem</h1>
        <img src="imagens/img01.jpg" alt="Imagem" style="width: 6cm; height: 6cm;">
        <form action="index.php" method="post">
            <input type="text" name="nome_usuario" placeholder="Nome de Usuário" required>
            <textarea name="comentario" placeholder="Seu comentário" rows="4" required></textarea>
            <input type="submit" name="submit" value="Enviar">
        </form>

        <a href="comentarios.php"><button>Consultar Comentários</button></a>
        <a href="cadastro.php"><button>Cadastrar Aluno</button></a>
    </div>
</body>
</html>

