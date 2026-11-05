// Estado da calculadora
let atual = "0";          // número que está sendo digitado
let anterior = null;      // primeiro número da conta
let operador = null;      // + - * /
let reiniciar = false;    // true depois de mostrar um resultado

const visor = document.getElementById("resultado");
const historico = document.getElementById("historico");

const simbolos = { "+": "+", "-": "−", "*": "×", "/": "÷" };

function formatar(valor) {
  return String(valor).replace(".", ",");
}

function atualizarVisor() {
  visor.textContent = formatar(atual);
  if (operador !== null && anterior !== null) {
    historico.textContent = formatar(anterior) + " " + simbolos[operador];
  } else {
    historico.textContent = "";
  }
}

function digitar(tecla) {
  if (reiniciar) {
    atual = "0";
    reiniciar = false;
  }
  if (tecla === ".") {
    if (atual.includes(".")) return;       // só um ponto decimal
    atual += ".";
  } else {
    atual = atual === "0" ? tecla : atual + tecla;
  }
  atualizarVisor();
}

function escolherOperador(op) {
  // Se já havia uma conta pendente, calcula antes de continuar (ex.: 2 + 3 +)
  if (operador !== null && !reiniciar) {
    calcular();
  }
  anterior = parseFloat(atual);
  operador = op;
  reiniciar = true;
  atualizarVisor();
}

function calcular() {
  if (operador === null || anterior === null) return;

  const a = anterior;
  const b = parseFloat(atual);
  let resultado;

  switch (operador) {
    case "+": resultado = a + b; break;
    case "-": resultado = a - b; break;
    case "*": resultado = a * b; break;
    case "/":
      if (b === 0) {
        visor.textContent = "Erro: divisão por 0";
        historico.textContent = "";
        atual = "0";
        anterior = null;
        operador = null;
        reiniciar = true;
        return;
      }
      resultado = a / b;
      break;
  }

  // Evita erros de ponto flutuante (0.1 + 0.2 = 0.30000000000000004)
  resultado = parseFloat(resultado.toFixed(10));

  historico.textContent = formatar(a) + " " + simbolos[operador] + " " + formatar(b) + " =";
  visor.textContent = formatar(resultado);

  atual = String(resultado);
  anterior = null;
  operador = null;
  reiniciar = true;
}

function limpar() {
  atual = "0";
  anterior = null;
  operador = null;
  reiniciar = false;
  atualizarVisor();
}

function apagar() {
  if (reiniciar) return;
  atual = atual.length > 1 ? atual.slice(0, -1) : "0";
  atualizarVisor();
}

// Suporte ao teclado
document.addEventListener("keydown", function (e) {
  if (e.key >= "0" && e.key <= "9") digitar(e.key);
  else if (e.key === "." || e.key === ",") digitar(".");
  else if (["+", "-", "*", "/"].includes(e.key)) escolherOperador(e.key);
  else if (e.key === "Enter" || e.key === "=") { e.preventDefault(); calcular(); }
  else if (e.key === "Backspace") apagar();
  else if (e.key === "Escape") limpar();
});
