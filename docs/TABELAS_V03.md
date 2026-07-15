# TABELAS DO BANCO DE DADOS
## Pulsar RH 3.0

---

# 01 • companies

Empresas

- id
- corporate_name
- trade_name
- document
- email
- phone
- website
- segment
- size
- employees
- created_at
- updated_at

---

# 02 • branches

Unidades / Filiais

- id
- company_id
- name
- code
- city
- state
- address
- manager_id
- active
- created_at
- updated_at

---

# 03 • departments

Setores

- id
- branch_id
- name
- description
- manager_id
- active
- created_at
- updated_at

---

# 04 • teams

Equipes

- id
- department_id
- name
- description
- leader_id
- active
- created_at
- updated_at

---

# 05 • positions

Cargos

- id
- department_id
- name
- description
- cbo
- salary_range
- active
- created_at
- updated_at

---

# 06 • employees

Colaboradores

- id
- company_id
- branch_id
- department_id
- team_id
- position_id
- manager_id
- name
- cpf
- birth_date
- gender
- email
- phone
- admission_date
- status
- created_at
- updated_at