CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('PARLIAMENT', 'REVIEWER', 'ADMINISTRATOR') NOT NULL
);

CREATE TABLE bill_status (
    status_code CHAR(1) PRIMARY KEY,
    status_desc VARCHAR(10) NOT NULL
);

CREATE TABLE bill (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  author INT NOT NULL,
  content VARCHAR(255) NOT NULL,
  status CHAR(1) NOT NULL DEFAULT 'D',
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (status) REFERENCES bill_status(status_code)
);

INSERT INTO bill_status (status_code, status_desc) VALUES
('D', 'Draft'),
('R', 'Review'),
('A', 'Approved'),
('N', 'Rejected'),
('V', 'Voting'),
('P', 'Passed'),
('E', 'Denied');

CREATE TABLE amendment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bill_id INT NOT NULL,
  amendments VARCHAR(255),
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (bill_id) REFERENCES bill(id)
);

CREATE TABLE vote (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bill_id INT NOT NULL,
  agree TINYINT(1) CHECK (agree IN (0, 1)),
  mp_id INT,
  FOREIGN KEY (bill_id) REFERENCES bill(id),
  FOREIGN KEY (mp_id) REFERENCES user(id)
);

CREATE USER IF NOT EXISTS 'govuser'@'%' IDENTIFIED BY 'Gov@user1234';
GRANT ALL PRIVILEGES ON legislation_sys_db.* TO 'govuser'@'%';
FLUSH PRIVILEGES;
