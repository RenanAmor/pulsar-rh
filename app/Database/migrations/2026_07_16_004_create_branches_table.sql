CREATE TABLE branches (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    name VARCHAR(255) NOT NULL,

    document VARCHAR(20) NOT NULL UNIQUE,

    email VARCHAR(255),

    phone VARCHAR(30),

    city VARCHAR(120),

    state CHAR(2),

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_branches_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE

);
