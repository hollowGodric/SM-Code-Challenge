CREATE TABLE article
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(160) NOT NULL,
    content TEXT NOT NULL,
    topic INT NOT NULL
);