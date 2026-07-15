CREATE TABLE ai_insights (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    survey_id INT NOT NULL,

    organizational_health_index_id INT NOT NULL,

    dimension VARCHAR(100) NOT NULL,

    insight_type ENUM(
        'Tendência',
        'Alerta',
        'Oportunidade',
        'Risco',
        'Evolução'
    ) NOT NULL,

    severity ENUM(
        'Baixa',
        'Média',
        'Alta',
        'Crítica'
    ) DEFAULT 'Baixa',

    title VARCHAR(255) NOT NULL,

    description LONGTEXT NOT NULL,

    recommendation LONGTEXT,

    confidence DECIMAL(5,2) DEFAULT 0,

    status ENUM(
        'Novo',
        'Em análise',
        'Aceito',
        'Descartado'
    ) DEFAULT 'Novo',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_aii_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_aii_survey
        FOREIGN KEY (survey_id)
        REFERENCES surveys(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_aii_iso
        FOREIGN KEY (organizational_health_index_id)
        REFERENCES organizational_health_index(id)
        ON DELETE CASCADE

);