-- Create the database
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Admin/Staff table
CREATE TABLE tbladmin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'staff',
    email VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Course table
CREATE TABLE tblcourse (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student table
CREATE TABLE tblstudent (
    student_id VARCHAR(20) PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    course_id INT,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES tblcourse(course_id)
);

-- Books table
CREATE TABLE tblbooks (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    publication_year INT,
    publisher VARCHAR(100),
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Borrower table
CREATE TABLE tblborrower (
    borrow_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20),
    book_id INT,
    borrow_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NOT NULL,
    return_date DATE,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    admin_id INT,
    FOREIGN KEY (student_id) REFERENCES tblstudent(student_id),
    FOREIGN KEY (book_id) REFERENCES tblbooks(book_id),
    FOREIGN KEY (admin_id) REFERENCES tbladmin(admin_id)
);

-- Insert default admin account
INSERT INTO tbladmin (username, password, fullname, role) 
VALUES ('admin', '$2y$10$8K1p/a0dxLB5o.VdpwFwKO5OeRk2kp6eaYHxuZHj8HJQSlYYfSqS.', 'System Administrator', 'admin');
-- Default password is 'admin123' - please change this immediately after first login

-- Insert some sample courses
INSERT INTO tblcourse (course_name, course_code, description) VALUES
('Bachelor of Science in Information Technology', 'BSIT', 'IT Program'),
('Bachelor of Science in Computer Science', 'BSCS', 'CS Program'),
('Bachelor of Business Administration', 'BBA', 'Business Program'); 

USE `library_db`;

DROP TABLE IF EXISTS `vborrower`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vborrower` AS
SELECT
    `tblborrower`.`borrow_id` AS `borrow_id`,
    `tblstudent`.`firstname` AS `firstname`,
    `tblstudent`.`lastname` AS `lastname`,
    `tblbooks`.`title` AS `title`,
    `tblcourse`.`course_name` AS `course_name`,
    `tblborrower`.`borrow_date` AS `borrow_date`,
    `tblborrower`.`return_date` AS `return_date`,
    `tblborrower`.`status` AS `status`
FROM
    (
        (
            `tblborrower`
            LEFT JOIN `tblstudent` ON `tblborrower`.`student_id` = `tblstudent`.`student_id`
        )
        LEFT JOIN `tblbooks` ON `tblborrower`.`book_id` = `tblbooks`.`book_id`
    )
    LEFT JOIN `tblcourse` ON `tblborrower`.`course_id` = `tblcourse`.`course_id`;
    









