CREATE TABLE ai_reports (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    survey_id INT NOT NULL,

    organizational_health_index_id INT NOT NULL,

    executive_summary LONGTEXT,

    strengths LONGTEXT,

    weaknesses LONGTEXT,

    opportunities LONGTEXT,

    threats LONGTEXT,

    priorities LONGTEXT,

    recommendations LONGTEXT,

    generated_by VARCHAR(100) DEFAULT 'Pulsar AI',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ai_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ai_survey
        FOREIGN KEY (survey_id)
        REFERENCES surveys(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ai_iso
        FOREIGN KEY (organizational_health_index_id)
        REFERENCES organizational_health_index(id)
        ON DELETE CASCADE

);