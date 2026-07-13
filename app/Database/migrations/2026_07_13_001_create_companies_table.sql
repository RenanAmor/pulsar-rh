CREATE TABLE companies (

    id INT AUTO_INCREMENT PRIMARY KEY,

    corporate_name VARCHAR(255) NOT NULL,

    trade_name VARCHAR(255) NOT NULL,

    document VARCHAR(20) NOT NULL UNIQUE,

    email VARCHAR(255),

    phone VARCHAR(30),

    website VARCHAR(255),

    sector VARCHAR(150),

    size ENUM(
        'Micro',
        'Pequena',
        'Média',
        'Grande'
    ) DEFAULT 'Micro',

    employees INT DEFAULT 0,

    city VARCHAR(120),

    state CHAR(2),

    active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);