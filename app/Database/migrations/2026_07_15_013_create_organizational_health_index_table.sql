CREATE TABLE organizational_health_index (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    survey_id INT NOT NULL,

    leadership DECIMAL(5,2) DEFAULT 0,

    communication DECIMAL(5,2) DEFAULT 0,

    engagement DECIMAL(5,2) DEFAULT 0,

    wellbeing DECIMAL(5,2) DEFAULT 0,

    development DECIMAL(5,2) DEFAULT 0,

    culture DECIMAL(5,2) DEFAULT 0,

    collaboration DECIMAL(5,2) DEFAULT 0,

    recognition DECIMAL(5,2) DEFAULT 0,

    turnover_risk DECIMAL(5,2) DEFAULT 0,

    burnout_risk DECIMAL(5,2) DEFAULT 0,

    final_score DECIMAL(5,2) NOT NULL,

    classification ENUM(
        'Excelente',
        'Saudável',
        'Atenção',
        'Crítico'
    ) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ohi_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ohi_survey
        FOREIGN KEY (survey_id)
        REFERENCES surveys(id)
        ON DELETE CASCADE

);