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