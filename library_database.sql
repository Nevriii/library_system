-- Library System Database Setup
-- This SQL file creates the complete database structure for the library system
-- Based on Library-Menu.php requirements

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `test`;

-- Drop existing tables if they exist (for clean setup)
DROP TABLE IF EXISTS `book`;
DROP TABLE IF EXISTS `test`; -- User registration table

-- Create the book table with enhanced structure
CREATE TABLE `book` (
    `BOOKID` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255) NOT NULL,
    `genre` VARCHAR(100) NOT NULL,
    `language` VARCHAR(50) NOT NULL,
    `year` INT(4) NOT NULL,
    `picture` VARCHAR(255) DEFAULT 'default-image.jpg',
    `price` DECIMAL(10,2) DEFAULT 0.00,
    `description` TEXT,
    `isbn` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`BOOKID`),
    INDEX `idx_title` (`title`),
    INDEX `idx_author` (`author`),
    INDEX `idx_genre` (`genre`),
    INDEX `idx_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the user registration table (for login/registration system)
CREATE TABLE `test` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `firstname` VARCHAR(50) NOT NULL,
    `lastname` VARCHAR(50) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `number` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample books data
INSERT INTO `book` (`title`, `author`, `genre`, `language`, `year`, `price`, `description`, `isbn`, `picture`) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 'English', 1925, 15.99, 'A story of the Jazz Age and the American Dream, following the mysterious millionaire Jay Gatsby and his love for the beautiful Daisy Buchanan.', '978-0743273565', 'gatsby.jpg'),
('To Kill a Mockingbird', 'Harper Lee', 'Fiction', 'English', 1960, 12.99, 'A powerful story about racial injustice in the American South, told through the eyes of young Scout Finch.', '978-0446310789', 'mockingbird.jpg'),
('1984', 'George Orwell', 'Science Fiction', 'English', 1949, 14.99, 'A dystopian novel about totalitarianism and surveillance, following Winston Smith in a world of constant government monitoring.', '978-0451524935', '1984.jpg'),
('Pride and Prejudice', 'Jane Austen', 'Romance', 'English', 1813, 11.99, 'A classic romance novel about love and social class, following the spirited Elizabeth Bennet and the proud Mr. Darcy.', '978-0141439518', 'pride.jpg'),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 'English', 1937, 16.99, 'An adventure story about a hobbit\'s journey to help reclaim a dwarf kingdom from a fearsome dragon.', '978-0547928241', 'hobbit.jpg'),
('Lord of the Flies', 'William Golding', 'Fiction', 'English', 1954, 13.99, 'A novel about the dark side of human nature, following a group of boys stranded on an island who descend into savagery.', '978-0399501487', 'flies.jpg'),
('The Catcher in the Rye', 'J.D. Salinger', 'Fiction', 'English', 1951, 12.99, 'A coming-of-age story about teenage alienation, following Holden Caulfield through New York City.', '978-0316769488', 'catcher.jpg'),
('Animal Farm', 'George Orwell', 'Allegory', 'English', 1945, 10.99, 'A political allegory about the Russian Revolution, using farm animals to represent historical figures and events.', '978-0451526342', 'animal.jpg'),
('The Alchemist', 'Paulo Coelho', 'Fiction', 'English', 1988, 14.99, 'A novel about following your dreams and destiny, following Santiago on his journey to find a hidden treasure.', '978-0062315007', 'alchemist.jpg'),
('Brave New World', 'Aldous Huxley', 'Science Fiction', 'English', 1932, 15.99, 'A dystopian novel about a futuristic society where people are genetically engineered and conditioned for their roles.', '978-0060850524', 'brave.jpg'),
('The Lord of the Rings', 'J.R.R. Tolkien', 'Fantasy', 'English', 1954, 24.99, 'An epic fantasy trilogy about the quest to destroy a powerful ring and save Middle-earth from darkness.', '978-0547928210', 'lotr.jpg'),
('Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Fantasy', 'English', 1997, 18.99, 'The first book in the Harry Potter series, following young wizard Harry Potter as he discovers his magical heritage.', '978-0747532699', 'harry-potter.jpg'),
('The Da Vinci Code', 'Dan Brown', 'Thriller', 'English', 2003, 16.99, 'A mystery thriller about a murder in the Louvre Museum and a religious mystery involving Leonardo da Vinci.', '978-0307474278', 'davinci.jpg'),
('The Kite Runner', 'Khaled Hosseini', 'Fiction', 'English', 2003, 13.99, 'A powerful story about friendship, betrayal, and redemption set against the backdrop of Afghanistan\'s turbulent history.', '978-1594631931', 'kite-runner.jpg'),
('Life of Pi', 'Yann Martel', 'Fiction', 'English', 2001, 14.99, 'A philosophical novel about a young man stranded in the Pacific Ocean with a Bengal tiger, exploring themes of survival and faith.', '978-0156027328', 'life-of-pi.jpg');

-- Insert sample user data (for testing login system)
INSERT INTO `test` (`firstname`, `lastname`, `gender`, `email`, `password`, `number`) VALUES
('John', 'Doe', 'Male', 'john.doe@example.com', 'password123', '09123456789'),
('Jane', 'Smith', 'Female', 'jane.smith@example.com', 'password123', '09234567890'),
('Michael', 'Johnson', 'Male', 'michael.johnson@example.com', 'password123', '09345678901'),
('Sarah', 'Williams', 'Female', 'sarah.williams@example.com', 'password123', '09456789012'),
('David', 'Brown', 'Male', 'david.brown@example.com', 'password123', '09567890123');

-- Create additional useful tables for library management

-- Categories table for better book organization
CREATE TABLE `categories` (
    `category_id` INT(11) NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`category_id`),
    UNIQUE KEY `unique_category` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories
INSERT INTO `categories` (`category_name`, `description`) VALUES
('Fiction', 'Imaginative literature including novels and short stories'),
('Non-Fiction', 'Factual literature based on real events and information'),
('Science Fiction', 'Fiction dealing with futuristic science and technology'),
('Fantasy', 'Fiction involving magical and supernatural elements'),
('Romance', 'Fiction focusing on romantic relationships'),
('Mystery', 'Fiction involving crime, detective work, and suspense'),
('Thriller', 'Fiction designed to create excitement and suspense'),
('Biography', 'Non-fiction accounts of people\'s lives'),
('History', 'Non-fiction about past events and periods'),
('Self-Help', 'Non-fiction designed to help readers improve themselves');

-- Book status table for tracking book availability
CREATE TABLE `book_status` (
    `status_id` INT(11) NOT NULL AUTO_INCREMENT,
    `book_id` INT(11) NOT NULL,
    `status` ENUM('Available', 'Borrowed', 'Reserved', 'Maintenance') DEFAULT 'Available',
    `borrowed_by` INT(11) NULL,
    `borrowed_date` DATE NULL,
    `return_date` DATE NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`status_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`BOOKID`) ON DELETE CASCADE,
    FOREIGN KEY (`borrowed_by`) REFERENCES `test`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial book status (all books available)
INSERT INTO `book_status` (`book_id`, `status`) 
SELECT `BOOKID`, 'Available' FROM `book`;

-- Create views for easier data access

-- View for available books
CREATE VIEW `available_books` AS
SELECT 
    b.BOOKID,
    b.title,
    b.author,
    b.genre,
    b.language,
    b.year,
    b.price,
    b.description,
    b.isbn,
    b.picture,
    bs.status
FROM book b
JOIN book_status bs ON b.BOOKID = bs.book_id
WHERE bs.status = 'Available';

-- View for borrowed books
CREATE VIEW `borrowed_books` AS
SELECT 
    b.BOOKID,
    b.title,
    b.author,
    u.firstname,
    u.lastname,
    u.email,
    bs.borrowed_date,
    bs.return_date
FROM book b
JOIN book_status bs ON b.BOOKID = bs.book_id
JOIN test u ON bs.borrowed_by = u.id
WHERE bs.status = 'Borrowed';

-- Create stored procedures for common operations

-- Procedure to borrow a book
DELIMITER //
CREATE PROCEDURE `BorrowBook`(
    IN p_book_id INT,
    IN p_user_id INT,
    IN p_return_days INT
)
BEGIN
    DECLARE book_status VARCHAR(20);
    DECLARE return_date DATE;
    
    -- Check if book is available
    SELECT status INTO book_status 
    FROM book_status 
    WHERE book_id = p_book_id;
    
    IF book_status = 'Available' THEN
        SET return_date = DATE_ADD(CURDATE(), INTERVAL p_return_days DAY);
        
        UPDATE book_status 
        SET status = 'Borrowed', 
            borrowed_by = p_user_id, 
            borrowed_date = CURDATE(), 
            return_date = return_date
        WHERE book_id = p_book_id;
        
        SELECT 'Book borrowed successfully' AS message;
    ELSE
        SELECT 'Book is not available' AS message;
    END IF;
END //
DELIMITER ;

-- Procedure to return a book
DELIMITER //
CREATE PROCEDURE `ReturnBook`(
    IN p_book_id INT
)
BEGIN
    UPDATE book_status 
    SET status = 'Available', 
        borrowed_by = NULL, 
        borrowed_date = NULL, 
        return_date = NULL
    WHERE book_id = p_book_id;
    
    SELECT 'Book returned successfully' AS message;
END //
DELIMITER ;

-- Create triggers for data integrity

-- Trigger to update book status when a book is deleted
DELIMITER //
CREATE TRIGGER `before_book_delete`
BEFORE DELETE ON `book`
FOR EACH ROW
BEGIN
    DELETE FROM book_status WHERE book_id = OLD.BOOKID;
END //
DELIMITER ;

-- Trigger to log book status changes
CREATE TABLE `book_status_log` (
    `log_id` INT(11) NOT NULL AUTO_INCREMENT,
    `book_id` INT(11) NOT NULL,
    `old_status` VARCHAR(20),
    `new_status` VARCHAR(20),
    `changed_by` INT(11),
    `change_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER //
CREATE TRIGGER `after_book_status_update`
AFTER UPDATE ON `book_status`
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO book_status_log (book_id, old_status, new_status, changed_by)
        VALUES (NEW.book_id, OLD.status, NEW.status, NEW.borrowed_by);
    END IF;
END //
DELIMITER ;

-- Grant permissions (adjust as needed for your setup)
-- GRANT ALL PRIVILEGES ON test.* TO 'root'@'localhost';
-- FLUSH PRIVILEGES;

-- Display summary information
SELECT 'Database setup completed successfully!' AS status;
SELECT COUNT(*) AS total_books FROM book;
SELECT COUNT(*) AS total_users FROM test;
SELECT COUNT(*) AS total_categories FROM categories;

-- Show sample data
SELECT 'Sample Books:' AS info;
SELECT title, author, genre, year, price FROM book LIMIT 5;

SELECT 'Sample Users:' AS info;
SELECT firstname, lastname, email FROM test LIMIT 3; 