# INTELIGÊNCIA ORGANIZACIONAL

O Pulsar RH não mede apenas respostas.

Ele identifica padrões.

Cada resposta gera informação.

O conjunto das informações gera indicadores.

O conjunto dos indicadores gera conhecimento.

A Inteligência Artificial transforma esse conhecimento em recomendações.

---

# FLUXO

Colaborador

↓

Pesquisa

↓

Perguntas

↓

Respostas

↓

Indicadores

↓

Índice de Saúde Organizacional (ISO)

↓

IA Consultora

↓

Plano de Ação

↓

Nova Pesquisa

↓

Comparação dos Resultados

↓

Melhoria Contínua

---

# TABELA • surveys

Pesquisas

- id
- company_id
- title
- description
- objective
- frequency
- anonymous
- start_date
- end_date
- status
- created_at
- updated_at

---

# TABELA • questions

Perguntas

- id
- category
- dimension
- text
- type
- scale
- weight
- active
- created_at
- updated_at

Categorias

- Liderança
- Comunicação
- Cultura
- Engajamento
- Desenvolvimento
- Bem-estar
- Reconhecimento
- Colaboração

---

# TABELA • survey_questions

Relacionamento

survey_id

question_id

order

required

---

# TABELA • answers

Respostas

- id
- survey_id
- employee_id
- question_id
- value
- observation
- answered_at

---

# TABELA • indicators

Indicadores

- id
- company_id
- survey_id
- dimension
- score
- previous_score
- variation
- risk_level
- created_at

---

# TABELA • organizational_health_index

ISO

- id
- company_id
- survey_id
- leadership
- communication
- engagement
- wellbeing
- development
- culture
- collaboration
- recognition
- final_score
- created_at

---

# TABELA • ai_reports

Relatórios da IA

- id
- company_id
- survey_id
- summary
- strengths
- weaknesses
- priorities
- recommendations
- created_at

---

# TABELA • action_plans

Plano de Ação

- id
- company_id
- ai_report_id
- title
- description
- responsible
- deadline
- status
- completed_at