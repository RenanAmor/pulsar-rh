CREATE TABLE positions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    branch_id INT NOT NULL,

    department_id INT NOT NULL,

    name VARCHAR(150) NOT NULL,

    code VARCHAR(30),

    cbo VARCHAR(20),

    description TEXT,

    salary_min DECIMAL(12,2) NULL,

    salary_max DECIMAL(12,2) NULL,

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_position_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_position_branch
        FOREIGN KEY (branch_id)
        REFERENCES branches(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_position_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id)
        ON DELETE CASCADE

);