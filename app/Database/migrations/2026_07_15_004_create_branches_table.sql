CREATE TABLE branches (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    name VARCHAR(150) NOT NULL,

    code VARCHAR(30),

    manager_id INT NULL,

    email VARCHAR(150),

    phone VARCHAR(30),

    document VARCHAR(30),

    zipcode VARCHAR(15),

    address VARCHAR(255),

    number VARCHAR(20),

    district VARCHAR(120),

    city VARCHAR(120),

    state CHAR(2),

    country VARCHAR(80) DEFAULT 'Brasil',

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_branch_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE

);