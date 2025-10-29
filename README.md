# Projeto: Sistema de Biblioteca (Programação Funcional)

Este é um trabalho acadêmico para a disciplina de **Linguagem de Programação e Paradigmas**, com o objetivo de aplicar conceitos de Programação Funcional (PF) em um projeto prático utilizando PHP.

O sistema simula a lógica de negócio de uma biblioteca, processando uma lista de registros de empréstimos e perfis de usuários para calcular multas, dias de atraso e gerar relatórios, seguindo os paradigmas de funções puras, imutabilidade e funções de ordem superior.

## Desenvolvedor
- **Jean Koerich** — @JeanKoerich
---

## Como Instalar e Executar

### Pré-requisitos
- Ambiente de servidor web com **PHP 7.4 ou superior** (necessário para arrow functions `fn()`).
- Recomendado: **XAMPP** ou o **servidor embutido do PHP**.

### Instruções (XAMPP)
1. Clone o repositório:
   ```bash
   git clone https://github.com/JeanKoerich/biblioteca-funcional.git
   ```
2. Copie a pasta `biblioteca-funcional` para `htdocs` do XAMPP.  
   **Exemplo (Windows):** `C:\xampp\htdocs\biblioteca-funcional`
3. Inicie o **Apache** no painel do XAMPP.
4. Acesse no navegador:  
   `http://localhost/biblioteca-funcional/`

### Instruções (Servidor embutido do PHP)
1. Clone e navegue até a pasta:
   ```bash
   git clone https://github.com/JeanKoerich/biblioteca-funcional.git
   cd biblioteca-funcional
   ```
2. Inicie o servidor:
   ```bash
   php -S localhost:8000
   ```
3. Acesse no navegador:  
   `http://localhost:8000`

---

## Funcionamento da Aplicação

O projeto é dividido em três telas principais, interligadas por um cabeçalho de navegação:

- **`index.php` (Processar Registros):** página principal. Permite colar os dados de entrada (JSON de *Perfis* e JSON de *Empréstimos*) e definir uma **Data Atual** para simulação. Ao enviar, os dados são processados, salvos em sessão PHP e o usuário é redirecionado aos relatórios.
- **`cadastro.php` (Gerar Registro):** tela auxiliar para criar novos registros de empréstimo via formulário, gerando o JSON pronto para colar na tela principal.
- **`relatorios.php` (Relatórios):** exibe os resultados do processamento (lidos da sessão), com:
  - Relatório de **Itens Atualmente em Atraso**;
  - **Total de Multas por Usuário**;
  - **Total de Multas Arrecadadas por Mês** (somente itens já devolvidos).

---

## Aplicação dos Conceitos de Programação Funcional

O núcleo do trabalho está em `src/biblioteca.php`, escrito segundo os paradigmas de PF.

### 1) Funções Puras e Imutabilidade (Critério 2.0 pts)
Todas as funções de negócio são **puras** (sem efeitos colaterais). Recebem dados e **retornam novos dados**, sem modificar variáveis globais ou estado externo.

- `calcularDataDevolucao(data, perfil)` → retorna nova data calculada.  
- `calcularDiasAtraso(esperada, real, atual)` → retorna número de dias.  
- `calcularMulta(dias, perfil)` → retorna `float`.  
- `validarRegistro(registro)` → retorna `bool`.

**Imutabilidade:** em `processarEmprestimos`, o array original `$emprestimosInput` **não** é alterado. `array_map` cria um novo array `$registrosProcessados` com os dados transformados.

### 2) Funções de Ordem Superior (HOF) (Critério 2.0 pts)
Uso das HOFs do PHP:

- **`array_filter` (Filtro):**  
  - Em `processarEmprestimos`, remove registros inválidos usando `validarRegistro`.  
  - Em `filtrarItensEmAtraso`, seleciona apenas itens com `isCurrentlyOverdue === true`.

- **`array_map` (Mapeamento):**  
  - HOF central em `processarEmprestimos`: transforma cada empréstimo aplicando `calcularDataDevolucao`, `calcularDiasAtraso`, `calcularMulta`.

- **`array_reduce` (Agregação):**  
  - Em `calcularTotalMultasPorUsuario`, reduz a lista para totais por usuário.  
  - Em `calcularTotalMultasPorMes`, agrega multas por mês.

### 3) Validação como Função Pura (Critério 1.5 pts)
A função `validarRegistro($registro)` recebe um registro e retorna **true/false** conforme regras (campos obrigatórios, datas válidas). Não gera efeitos colaterais; apenas o booleano esperado.  
*(No exemplo, o registro de “Mariana Torres” é filtrado por faltar `checkoutDate`.)*

---

## Exemplos de Entrada e Saída

**Contexto:** dados de `src/dados.php` e **data de processamento padrão: 2025-10-25**.

### Exemplo de Entrada — Perfis (JSON)
```json
{
  "Jean Koerich": "aluno",
  "Ricardo Alves": "professor",
  "Beatriz Lima": "aluno",
  "Mariana Torres": "professor"
}
```

### Exemplo de Entrada — Empréstimos (JSON) — considerando `dataAtual = 2025-10-25`
```json
[
  { "userId": "Jean Koerich",  "itemId": "livro_A", "checkoutDate": "2025-10-01", "returnDate": "2025-10-10" },
  { "userId": "Jean Koerich",  "itemId": "livro_B", "checkoutDate": "2025-10-01", "returnDate": "2025-10-13" },
  { "userId": "Ricardo Alves", "itemId": "livro_C", "checkoutDate": "2025-09-01", "returnDate": "2025-10-03" },
  { "userId": "Ricardo Alves", "itemId": "livro_D", "checkoutDate": "2025-09-01", "returnDate": "2025-10-08" },
  { "userId": "Beatriz Lima",  "itemId": "livro_E", "checkoutDate": "2025-10-20", "returnDate": null },
  { "userId": "Beatriz Lima",  "itemId": "livro_F", "checkoutDate": "2025-10-11", "returnDate": null },
  { "userId": "Mariana Torres","itemId": "livro_G" }
]
```
## Licença
MIT
