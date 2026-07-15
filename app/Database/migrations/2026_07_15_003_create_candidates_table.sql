CREATE TABLE candidates (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    job_id INT NULL,

    name VARCHAR(150) NOT NULL,

    email VARCHAR(150),

    phone VARCHAR(30),

    cpf VARCHAR(20),

    birth_date DATE,

    city VARCHAR(120),

    state VARCHAR(50),

    linkedin VARCHAR(255),

    resume VARCHAR(255),

    notes TEXT,

    status ENUM(
        'Novo',
        'Triagem',
        'Entrevista',
        'Aprovado',
        'Banco de Talentos',
        'Reprovado'
    ) DEFAULT 'Novo',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_candidate_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_candidate_job
        FOREIGN KEY (job_id)
        REFERENCES jobs(id)
        ON DELETE SET NULL

);