CREATE TABLE jobs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,

    department VARCHAR(150),

    workplace ENUM(
        'Presencial',
        'Híbrido',
        'Remoto'
    ) DEFAULT 'Presencial',

    contract_type ENUM(
        'CLT',
        'PJ',
        'Estágio',
        'Temporário',
        'Autônomo'
    ) DEFAULT 'CLT',

    vacancies INT DEFAULT 1,

    salary DECIMAL(10,2) DEFAULT NULL,

    city VARCHAR(120),

    state CHAR(2),

    description TEXT,

    requirements TEXT,

    benefits TEXT,

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_jobs_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE

);