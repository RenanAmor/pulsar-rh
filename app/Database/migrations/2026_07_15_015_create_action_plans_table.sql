CREATE TABLE action_plans (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    ai_report_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,

    description LONGTEXT,

    priority ENUM(
        'Baixa',
        'Média',
        'Alta',
        'Crítica'
    ) DEFAULT 'Média',

    responsible_employee_id INT NULL,

    start_date DATE,

    due_date DATE,

    completed_at DATE NULL,

    status ENUM(
        'Planejado',
        'Em andamento',
        'Concluído',
        'Cancelado'
    ) DEFAULT 'Planejado',

    effectiveness_score DECIMAL(5,2) NULL,

    observations LONGTEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_action_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_action_report
        FOREIGN KEY (ai_report_id)
        REFERENCES ai_reports(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_action_responsible
        FOREIGN KEY (responsible_employee_id)
        REFERENCES employees(id)
        ON DELETE SET NULL

);