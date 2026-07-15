CREATE TABLE questions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NULL,

    category ENUM(
        'Liderança',
        'Comunicação',
        'Engajamento',
        'Cultura',
        'Bem-estar',
        'Desenvolvimento',
        'Colaboração',
        'Reconhecimento'
    ) NOT NULL,

    dimension VARCHAR(100) NOT NULL,

    question TEXT NOT NULL,

    answer_type ENUM(
        'Escala',
        'SimNão',
        'Texto',
        'Múltipla Escolha'
    ) DEFAULT 'Escala',

    scale_min INT DEFAULT 1,

    scale_max INT DEFAULT 5,

    weight DECIMAL(5,2) DEFAULT 1.00,

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_question_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE

);