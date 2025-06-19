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