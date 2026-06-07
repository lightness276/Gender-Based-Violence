CREATE DATABASE IF NOT EXISTS safevoice_gbv
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE safevoice_gbv;

DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    incident_type VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(180) NOT NULL,
    incident_date DATE NOT NULL,
    evidence_file VARCHAR(255) DEFAULT NULL,
    status ENUM('Pending', 'Under Review', 'Resolved') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reports_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

-- Default password for all sample accounts is: password
INSERT INTO users (full_name, email, phone, password, role) VALUES
('System Administrator', 'admin@safevoice.test', '0711111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC4/jPwq8PlV8aWb1yG', 'admin'),
('Asha Mdoe', 'asha@example.com', '0722222222', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC4/jPwq8PlV8aWb1yG', 'user'),
('Brian Joseph', 'brian@example.com', '0733333333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC4/jPwq8PlV8aWb1yG', 'user');

INSERT INTO reports (user_id, incident_type, description, location, incident_date, evidence_file, status) VALUES
(2, 'Physical Violence', 'The survivor reported physical violence near the residence after an argument.', 'Dar es Salaam', '2026-05-12', NULL, 'Pending'),
(2, 'Emotional Abuse', 'Repeated verbal threats and intimidation were reported over several weeks.', 'Arusha', '2026-05-18', NULL, 'Under Review'),
(3, 'Economic Abuse', 'The survivor reported being denied access to personal income and basic needs.', 'Mwanza', '2026-05-25', NULL, 'Resolved');
