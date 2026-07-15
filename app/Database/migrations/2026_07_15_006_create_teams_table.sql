CREATE TABLE teams (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    branch_id INT NOT NULL,

    department_id INT NOT NULL,

    leader_id INT NULL,

    name VARCHAR(150) NOT NULL,

    code VARCHAR(30),

    description TEXT,

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_team_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_team_branch
        FOREIGN KEY (branch_id)
        REFERENCES branches(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_team_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id)
        ON DELETE CASCADE

);