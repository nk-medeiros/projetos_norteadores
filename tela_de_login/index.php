<?php
session_start();

// Se já está logada, vai direto para o painel
if (isset($_SESSION["usuario"])) {
    header("Location: painel.php");
    exit;
}

// Mensagem de erro vinda do login.php (?erro=1 ou ?erro=2)
$erro = $_GET["erro"] ?? "";
$mensagem = "";
if ($erro === "1") {
    $mensagem = "Preencha o usuário e a senha.";
} elseif ($erro === "2") {
    $mensagem = "Usuário ou senha incorretos.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="caixa">
    <h1>Entrar</h1>
    <p class="subtitulo">Acesse sua conta</p>

    <?php if ($mensagem !== ""): ?>
      <p class="erro"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <!-- Os dados são enviados por POST para o login.php -->
    <form action="login.php" method="post">
      <div class="campo">
        <label for="usuario">Usuário</label>
        <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
      </div>

      <div class="campo">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
      </div>

      <button type="submit">Entrar</button>
    </form>

    <p class="dica">Teste: usuário <strong>nayara</strong> · senha <strong>1234</strong></p>
  </div>

</body>
</html>
