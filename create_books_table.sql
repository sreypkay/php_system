CREATE TABLE IF NOT EXISTS `tblbooks` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `isbn` VARCHAR(13) UNIQUE NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255) NOT NULL,
    `publisher` VARCHAR(255) NOT NULL,
    `publication_year` YEAR NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT,
    `quantity` INT NOT NULL DEFAULT 1,
    `available_quantity` INT NOT NULL DEFAULT 1,
    `cover_image` VARCHAR(255) DEFAULT 'uploads/books/default_book.png',
    `shelf_location` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 