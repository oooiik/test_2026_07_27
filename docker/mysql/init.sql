CREATE TABLE images
(
    id         int PRIMARY KEY,
    slug       varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
;
CREATE TABLE categories
(
    id          int PRIMARY KEY,
    name        varchar(255) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE articles
(
    id          int PRIMARY KEY,
    name        varchar(255),
    text        TEXT NOT NULL,
    category_id int  NOT NULL,
    description TEXT,
    view_count  int  NOT NULL DEFAULT 0,
    image_id    int,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);