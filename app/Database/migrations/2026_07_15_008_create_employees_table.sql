CREATE TABLE employees (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    branch_id INT NOT NULL,

    department_id INT NOT NULL,

    team_id INT NULL,

    position_id INT NOT NULL,

    manager_id INT NULL,

    registration VARCHAR(30),

    name VARCHAR(150) NOT NULL,

    cpf VARCHAR(20) UNIQUE,

    birth_date DATE,

    gender ENUM(
        'Masculino',
        'Feminino',
        'Outro',
        'Prefiro não informar'
    ),

    email VARCHAR(150),

    phone VARCHAR(30),

    admission_date DATE,

    termination_date DATE NULL,

    employment_type ENUM(
        'CLT',
        'PJ',
        'Estágio',
        'Temporário',
        'Terceirizado'
    ) DEFAULT 'CLT',

    status ENUM(
        'Ativo',
        'Férias',
        'Afastado',
        'Desligado'
    ) DEFAULT 'Ativo',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_employee_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_employee_branch
        FOREIGN KEY (branch_id)
        REFERENCES branches(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_employee_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_employee_team
        FOREIGN KEY (team_id)
        REFERENCES teams(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_employee_position
        FOREIGN KEY (position_id)
        REFERENCES positions(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_employee_manager
        FOREIGN KEY (manager_id)
        REFERENCES employees(id)
        ON DELETE SET NULL

);