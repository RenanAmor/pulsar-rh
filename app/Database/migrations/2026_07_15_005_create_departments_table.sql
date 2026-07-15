CREATE TABLE departments (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    branch_id INT NOT NULL,

    manager_id INT NULL,

    name VARCHAR(150) NOT NULL,

    code VARCHAR(30),

    description TEXT,

    email VARCHAR(150),

    phone VARCHAR(30),

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_department_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_department_branch
        FOREIGN KEY (branch_id)
        REFERENCES branches(id)
        ON DELETE CASCADE

);