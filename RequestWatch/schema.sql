CREATE TABLE user_request (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    previous INTEGER NULL REFERENCES user_request(id),
    ip VARCHAR(15) NOT NULL,
    method VARCHAR(20) NOT NULL,
    path VARCHAR(255) NOT NULL,
    route VARCHAR(255) NULL
);