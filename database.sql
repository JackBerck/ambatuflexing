CREATE DATABASE devflex;

CREATE DATABASE devflex_test;

CREATE TABLE users
(
    id        INT PRIMARY KEY AUTO_INCREMENT,
    email     VARCHAR(255) NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    password  VARCHAR(255) NOT NULL,
    role      VARCHAR(100) NULL,
    photo     VARCHAR(255) NULL,
    is_admin  ENUM ('admin','user') DEFAULT 'user'
) ENGINE InnoDB;

CREATE TABLE sessions
(
    id      VARCHAR(255) PRIMARY KEY,
    user_id INT NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE contents
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      varchar(255) NOT NULL,
    content    TEXT         NULL,
    created_at TIMESTAMP                 DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP                 DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    visibility ENUM ('public','private') DEFAULT 'public',

    user_id    INT,
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE InnoDB;

CREATE TABLE content_images
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,
    path       VARCHAR(255),

    FOREIGN KEY (content_id) REFERENCES contents (id)
) ENGINE InnoDB;

CREATE TABLE likes
(
    user_id    INT NOT NULL,
    content_id INT NOT NULL,
    like_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, content_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES contents (id) ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE comments
(
    user_id    INT  NOT NULL,
    content_id INT  NOT NULL,
    comment    TEXT NOT NULL,
    comment_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, content_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES contents (id) ON DELETE CASCADE
) ENGINE InnoDB;
