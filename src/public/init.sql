CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('PARLIAMENT', 'REVIEWER', 'ADMINISTRATOR')
);

CREATE TABLE bill (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  author INT NOT NULL,
  content VARCHAR(255) NOT NULL,
  status CHAR(1) NOT NULL DEFAULT 'D',
  create_time TIMESTAMP DEFAULT,
  FOREIGN KEY (author) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bill_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status_code CHAR(1) NOT NULL,
  status_desc VARCHAR(10) NOT NULL
);

INSERT INTO bill_status VALUES
(1, 'D', 'Draft')
(2, 'R', 'Review')
(3, 'A', 'Approved')
(4, 'N', 'Rejected')
(5, 'V', 'Voting')
(6, 'P', 'Passed');

CREATE TABLE amendments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bill_id INT NOT NULL,
  amendments VARCHAR(255)
  create_time TIMESTAMP DEFAULT,
  FOREIGN KEY (bill_id) REFERENCES bill(id)
);

CREATE TABLE vote (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bill_id INT NOT NULL,
  vote_agree INT,
  vote_disagree INT,
  FOREIGN KEY (bill_id) REFERENCES bill(id)
);

CREATE USER IF NOT EXISTS 'govuser'@'%' IDENTIFIED BY 'Gov@user1234';
GRANT ALL PRIVILEGES ON legislation_sys_db.* TO 'govuser'@'%';
FLUSH PRIVILEGES;