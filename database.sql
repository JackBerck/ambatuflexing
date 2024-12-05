CREATE DATABASE devflex;

CREATE DATABASE devflex_test;

CREATE TABLE users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    email      VARCHAR(255) NOT NULL UNIQUE,
    username   VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    position   VARCHAR(100) NULL,
    bio        TEXT         NULL,
    photo      VARCHAR(255) NULL,
    is_admin   ENUM ('admin','user') DEFAULT 'user',
    created_at TIMESTAMP             DEFAULT CURRENT_TIMESTAMP
) ENGINE InnoDB;

CREATE TABLE sessions
(
    id      VARCHAR(255) PRIMARY KEY,
    user_id INT NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE posts
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      varchar(255) NOT NULL,
    content    TEXT         NULL,
    category   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    user_id    INT,
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE InnoDB;

CREATE TABLE post_images
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    image   VARCHAR(255),

    FOREIGN KEY (post_id) REFERENCES posts (id)
) ENGINE InnoDB;

CREATE TABLE likes
(
    user_id    INT NOT NULL,
    post_id    INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE comments
(
    user_id    INT  NOT NULL,
    post_id    INT  NOT NULL,
    comment    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
) ENGINE InnoDB;
