const campoA = document.getElementById("a");
const campoB = document.getElementById("b");
const campoC = document.getElementById("c");
const saida = document.getElementById("saida");

// Arredonda para até 4 casas decimais e troca ponto por vírgula
function formatar(n) {
  return String(parseFloat(n.toFixed(4))).replace(".", ",");
}

function mostrar(html, tipo) {
  saida.className = "saida " + tipo;
  saida.innerHTML = html;
}

function calcular() {
  // Campo vazio vira NaN
  const a = parseFloat(campoA.value);
  const b = parseFloat(campoB.value);
  const c = parseFloat(campoC.value);

  if (isNaN(a) || isNaN(b) || isNaN(c)) {
    mostrar("Preencha os três coeficientes (a, b e c).", "erro");
    return;
  }

  if (a === 0) {
    mostrar("O coeficiente <strong>a</strong> não pode ser zero. Com a = 0 a equação deixa de ser do 2º grau.", "erro");
    return;
  }

  // Discriminante (delta)
  const delta = b * b - 4 * a * c;
  let html = "<p>Δ = b² − 4ac = " + formatar(delta) + "</p>";

  if (delta > 0) {
    const x1 = (-b + Math.sqrt(delta)) / (2 * a);
    const x2 = (-b - Math.sqrt(delta)) / (2 * a);
    html += "<p>Duas raízes reais e diferentes:</p>";
    html += "<p class='raiz'>x₁ = " + formatar(x1) + "</p>";
    html += "<p class='raiz'>x₂ = " + formatar(x2) + "</p>";
    mostrar(html, "ok");
  } else if (delta === 0) {
    const x = -b / (2 * a);
    html += "<p>Uma raiz real (duas raízes iguais):</p>";
    html += "<p class='raiz'>x = " + formatar(x) + "</p>";
    mostrar(html, "ok");
  } else {
    // Delta negativo: não há raízes reais (as raízes são números complexos)
    const parteReal = -b / (2 * a);
    const parteImag = Math.sqrt(-delta) / (2 * Math.abs(a));
    html += "<p>Não existem raízes reais, pois Δ &lt; 0.</p>";
    html += "<p class='raiz pequeno'>Raízes complexas: " +
            formatar(parteReal) + " ± " + formatar(parteImag) + "i</p>";
    mostrar(html, "aviso");
  }
}

document.getElementById("calcular").addEventListener("click", calcular);

// Enter em qualquer campo também calcula
[campoA, campoB, campoC].forEach(function (campo) {
  campo.addEventListener("keydown", function (e) {
    if (e.key === "Enter") calcular();
  });
});
