CREATE TABLE survey_questions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    survey_id INT NOT NULL,

    question_id INT NOT NULL,

    display_order INT DEFAULT 1,

    required TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sq_survey
        FOREIGN KEY (survey_id)
        REFERENCES surveys(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_sq_question
        FOREIGN KEY (question_id)
        REFERENCES questions(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_survey_question
        UNIQUE (survey_id, question_id)

);