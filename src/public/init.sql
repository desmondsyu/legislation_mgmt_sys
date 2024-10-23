CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('PARLIAMENT', 'REVIEWER', 'ADMINISTRATOR') NOT NULL
);

CREATE TABLE bill (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  author INT NOT NULL,
  content VARCHAR(255) NOT NULL,
  status CHAR(1) NOT NULL DEFAULT 'D',
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE bill_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status_code CHAR(1) NOT NULL,
  status_desc VARCHAR(10) NOT NULL
);

INSERT INTO bill_status (id, status_code, status_desc) VALUES
(1, 'D', 'Draft'),
(2, 'R', 'Review'),
(3, 'A', 'Approved'),
(4, 'N', 'Rejected'),
(5, 'V', 'Voting'),
(6, 'P', 'Passed');

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
  vote_agree INT DEFAULT 0,
  vote_disagree INT DEFAULT 0,
  result INT GENERATED ALWAYS AS (vote_agree - vote_disagree) VIRTUAL,
  FOREIGN KEY (bill_id) REFERENCES bill(id)
);

CREATE USER IF NOT EXISTS 'govuser'@'%' IDENTIFIED BY 'Gov@user1234';
GRANT ALL PRIVILEGES ON legislation_sys_db.* TO 'govuser'@'%';
FLUSH PRIVILEGES;
