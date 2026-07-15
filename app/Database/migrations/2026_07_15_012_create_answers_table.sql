CREATE TABLE answers (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    survey_id INT NOT NULL,

    employee_id INT NOT NULL,

    question_id INT NOT NULL,

    score DECIMAL(5,2) NULL,

    answer_text TEXT NULL,

    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_answer_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_answer_survey
        FOREIGN KEY (survey_id)
        REFERENCES surveys(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_answer_employee
        FOREIGN KEY (employee_id)
        REFERENCES employees(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_answer_question
        FOREIGN KEY (question_id)
        REFERENCES questions(id)
        ON DELETE CASCADE

);