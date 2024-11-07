CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, created_at) VALUES
    ('John Doe', 'john.doe@example.com', '2024-01-15 10:00:00'),
    ('Jane Smith', 'jane.smith@example.com', '2024-01-16 11:30:00'),
    ('Mike Johnson', 'mike.johnson@example.com', '2024-01-17 09:15:00'),
    ('Sarah Wilson', 'sarah.wilson@example.com', '2024-01-18 14:20:00'),
    ('David Brown', 'david.brown@example.com', '2024-01-19 16:45:00'),
    ('Emily Davis', 'emily.davis@example.com', '2024-01-20 13:10:00'),
    ('James Miller', 'james.miller@example.com', '2024-01-21 08:30:00'),
    ('Emma Taylor', 'emma.taylor@example.com', '2024-01-22 15:55:00');