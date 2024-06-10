CREATE DATABASE feedback_module;

USE feedback_module;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50)
);

CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_name VARCHAR(255) NOT NULL
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    topic_id INT NOT NULL,
    message TEXT NOT NULL,
    attachment VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Получено',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (topic_id) REFERENCES topics(id)
);

INSERT INTO topics (topic_name) VALUES 
('Поступление'),
('Учебный процесс'),
('Общежитие'),
('Стипендии'),
('Другое');
