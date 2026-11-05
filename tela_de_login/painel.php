<?php
session_start();

// Sair: apaga a sessão e volta para o login
if (isset($_GET["sair"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit;
}

// Página protegida: quem não fez login volta para o início
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$usuario = $_SESSION["usuario"];

// Lê os acessos salvos pelo login.php
$acessos = [];
$caminho = __DIR__ . "/dados/acessos.csv";
if (is_file($caminho)) {
    $arquivo = fopen($caminho, "r");
    while (($linha = fgetcsv($arquivo)) !== false) {
        $acessos[] = $linha;
    }
    fclose($arquivo);
}
// Mostra os mais recentes primeiro, no máximo 10
$acessos = array_slice(array_reverse($acessos), 0, 10);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="caixa larga">
    <h1>Bem-vinda, <?= htmlspecialchars($usuario) ?>!</h1>
    <p class="sucesso">Login realizado com sucesso.</p>

    <h2>Últimos acessos salvos</h2>
    <?php if (count($acessos) === 0): ?>
      <p class="subtitulo">Nenhum acesso registrado ainda.</p>
    <?php else: ?>
      <table class="tabela">
        <thead>
          <tr><th>Data e hora</th><th>Usuário</th><th>Resultado</th></tr>
        </thead>
        <tbody>
          <?php foreach ($acessos as $a): ?>
            <tr>
              <td><?= htmlspecialchars($a[0] ?? "") ?></td>
              <td><?= htmlspecialchars($a[1] ?? "") ?></td>
              <td class="<?= ($a[2] ?? "") === "sucesso" ? "ok" : "falha" ?>">
                <?= htmlspecialchars($a[2] ?? "") ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <a class="voltar" href="painel.php?sair=1">Sair</a>
  </div>

</body>
</html>
