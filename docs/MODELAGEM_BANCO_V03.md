# MODELAGEM DO BANCO DE DADOS
## Pulsar RH 3.0

---

# EMPRESA

Empresa
│
├── Unidade (Filial)
│
├── Setor
│
├── Equipe
│
├── Cargo
│
├── Colaborador
│
├── Gestor
│
├── Pesquisa
│
├── Pergunta
│
├── Resposta
│
├── Indicadores
│
├── Plano de Ação
│
└── Relatórios

---

# RELACIONAMENTOS

Empresa

1:N Unidades

Unidade

1:N Setores

Setor

1:N Equipes

Equipe

1:N Cargos

Cargo

1:N Colaboradores

Colaborador

N:N Pesquisas

Pesquisas

1:N Perguntas

Perguntas

1:N Respostas

Respostas

→ Indicadores

Indicadores

→ IA

IA

→ Plano de Ação