CREATE TABLE surveys (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,

    description TEXT,

    objective VARCHAR(255),

    target ENUM(
        'Empresa',
        'Filial',
        'Departamento',
        'Equipe'
    ) DEFAULT 'Empresa',

    frequency ENUM(
        'Única',
        'Semanal',
        'Quinzenal',
        'Mensal',
        'Trimestral',
        'Semestral',
        'Anual'
    ) DEFAULT 'Mensal',

    anonymous TINYINT(1) DEFAULT 1,

    start_date DATE,

    end_date DATE,

    status ENUM(
        'Rascunho',
        'Agendada',
        'Em andamento',
        'Encerrada',
        'Cancelada'
    ) DEFAULT 'Rascunho',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_survey_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE

);