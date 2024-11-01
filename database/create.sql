CREATE DATABASE IF NOT EXISTS leoscholar;
USE leoscholar;

-- Universities table
CREATE TABLE IF NOT EXISTS universities (
    university_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    university_id VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_email (email),
    FOREIGN KEY (university_id) REFERENCES universities(university_id) ON DELETE CASCADE
);

-- Category_Preference table
CREATE TABLE IF NOT EXISTS category_preference (
    pref_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    description TEXT,
    about_author TEXT,
    publication_year YEAR,
    category VARCHAR(100),
    cover_path VARCHAR(255),
    hard_copy INT DEFAULT 1,
    ebook_file_path VARCHAR(255) DEFAULT NULL,
    audio_file_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_books_isbn (isbn),
    FULLTEXT idx_books_search (title, author, description, category) -- Add this for better full-text search matching
);

-- Favourite_Books table
CREATE TABLE IF NOT EXISTS favourite_books (
    fav_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

-- Reviews table (maybe this is not necessary...)
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    review TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

-- Branches table
CREATE TABLE IF NOT EXISTS branches (
    branch_id INT AUTO_INCREMENT PRIMARY KEY,
    university_id VARCHAR(10) NOT NULL,
    branch_name VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(university_id) ON DELETE CASCADE
);

-- Book_Availability table
CREATE TABLE IF NOT EXISTS book_availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    branch_id INT NOT NULL,
    available_copies INT DEFAULT 0,
    shelf VARCHAR(10),
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE CASCADE,
    UNIQUE KEY (book_id, branch_id)
);

-- Loans table
CREATE TABLE IF NOT EXISTS loans (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    branch_id INT NOT NULL,
    loan_date DATE,
    return_date DATE,
    due_date DATE,
    status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE CASCADE
);

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    branch_id INT NOT NULL,
    reservation_date DATE,
    status ENUM('pending', 'fulfilled', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE CASCADE
);

-- Fines table:
CREATE TABLE IF NOT EXISTS fines (
    fine_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    loan_id INT,
    amount DECIMAL(10,2),
    reason TEXT,
    issued_date DATE,
    paid_date DATE,
    paid BOOLEAN DEFAULT false,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    isbn VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    description TEXT,
    about_author TEXT,
    publication_year YEAR,
    category VARCHAR(100),
    cover_path VARCHAR(255),
    branch_id INT NOT NULL,
    available_copies INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE CASCADE
);

-- DigitalResources table
CREATE TABLE IF NOT EXISTS digital_resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    description TEXT,
    about_author TEXT,
    publication_year YEAR,
    category VARCHAR(100),
    cover_path VARCHAR(255),
    file_path VARCHAR(255),
    type ENUM('ebook', 'audiobook'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Librarians table
CREATE TABLE IF NOT EXISTS librarians (
    librarian_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    university_id VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_id) REFERENCES universities(university_id) ON DELETE CASCADE
);
