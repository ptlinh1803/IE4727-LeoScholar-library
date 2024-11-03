USE leoscholar;

-- Universities table
INSERT INTO universities (university_id, name, address) VALUES
('NUS', 'National University of Singapore', '21 Lower Kent Ridge Rd, Singapore 119077'),
('NTU', 'Nanyang Technological University', '50 Nanyang Ave, Singapore 639798'),
('SMU', 'Singapore Management University', '81 Victoria St, Singapore 188065'),
('SUTD', 'Singapore University of Technology and Design', '8 Somapah Rd, Singapore 487372'),
('SIT', 'Singapore Institute of Technology', '10 Dover Dr, Singapore 138683'),
('SUSS', 'Singapore University of Social Sciences', '463 Clementi Rd, Singapore 599494');

-- Users table
INSERT INTO users (name, email, password, phone, university_id) VALUES
('Pham Thuy Linh', 'thuylinh001@e.ntu.edu.sg', '12be439712901640aa0a9e271b65d9fc', '12345678', 'NTU'),
('Tran Huu Nghia', 'huunghia002@e.ntu.edu.sg', 'bbe3b53eb5210306e9dcfda8be238e9a', '87654321', 'NTU'),
('Test User', 'testuser1@e.ntu.edu.sg', '2c4bed4d73c86619fcf1627fb72011fa', '11111111', 'NTU');

-- Librarians table
INSERT INTO librarians (name, email, password, university_id) VALUES 
('NTU Librarian 1', 'ntulib1@e.ntu.edu.sg', 'f2b0ce4019fdabe180ffadc5ebf8d74b', 'NTU'), 
('NUS Librarian 1', 'nuslib1@e.nus.edu.sg', 'ffc04b4ec676dd123fcc0b0d5c3427aa', 'NUS'),
('SMU Librarian 1', 'smulib1@e.smu.edu.sg', 'ee1964c2e52569373f2c67d5b2a844b3', 'SMU'), 
('SUTD Librarian 1', 'sutdlib1@e.sutd.edu.sg', 'a4d110bcb2c1ff9486c9cc1615a83c4c', 'SUTD'),
('SIT Librarian 1', 'sitlib1@e.sit.edu.sg', 'a0863d6ac753ab1fbf5222129870b5ee', 'SIT'), 
('SUSS Librarian 1', 'susslib1@e.suss.edu.sg', '0301f9825d0ffabd5fef69ecd06fb3e2', 'SUSS');

-- Category_Preference table
INSERT INTO category_preference (user_id, category) VALUES 
(1, 'Mathematics & Statistics'), 
(1, 'Natural Sciences'),
(1, 'Computer Science & Technology'),
(2,  'Computer Science & Technology'),
(2, 'Humanities & Social Science'),
(2, 'Business & Finance');

-- Branches table
INSERT INTO branches (university_id, branch_name, address) VALUES
('NTU', 'Art, Design & Media Library', 'ART-01-03'),
('NTU', 'Business Library', 'N2-B2b-07'),
('NTU', 'Communication & Information Library', 'CS-01-18'),
('NTU', 'Humanities & Social Sciences Library', 'S4-B3C-05'),
('NTU', 'Lee Wee Nam Library', 'NS3-03-01'),
('NUS', 'Central Library', '12 Kent Ridge Crescent'),
('NUS', 'Hon Sui Sen Memorial Library', '1 Hon Sui Sen Drive'),
('NUS', 'Medicine+Science Library', '11 Lower Kent Ridge Road'),
('SMU', 'Bridwell Library', '6005 Bishop Blvd Dalla'),
('SMU', 'Fondren Library', '6414 Robert S. Hyer Lane'),
('SMU', 'Duda Family Business Library', '6214 Bishop Boulevard'),
('SMU', 'Hamon Arts Library', '6100 Hillcrest Avenue');

-- Books table
-- Go to phpmyadmin > leoscholar > books > Import
-- Choose file full_books.csv
-- Keep other default settings
-- Specify column order (no need to enclose): isbn, title, author, description, about_author, publication_year, category, cover_path, hard_copy, ebook_file_path, audio_file_path

-- Book_Availability table
-- Go to phpmyadmin > leoscholar > book_availability > Import
-- Choose file book_availability.csv
-- Keep other default settings
-- Specify column order (no need to enclose): book_id, branch_id, available_copies

-- Digital_Resources table
-- Go to phpmyadmin > leoscholar > digital_resources > Import
-- Choose file digital_resources.csv
-- Keep other default settings
-- Specify column order (no need to enclose): isbn, title, author, description, about_author, publication_year, category, cover_path, file_path, type

-- After importing digital_resources.csv
-- Change the resource_id of 'Pride and Prejudice' to 35 (to match with book_id)
-- UPDATE digital_resources
-- SET resource_id = 35
-- WHERE title = 'Pride and Prejudice';

-- Favourite Books table (only do after inserting Books table)
-- INSERT INTO favourite_books (user_id, book_id) VALUES
-- (1, 1),
-- (1, 2),
-- (1, 3),
-- (2, 4),
-- (2, 5),
-- (2, 6),
-- (2, 7);

-- Step 1: Add the new column "Shelf" to the "book_availability" table
-- ALTER TABLE book_availability
-- ADD COLUMN shelf VARCHAR(10);

-- Step 2: Update each row with the corresponding shelf number
-- UPDATE book_availability
-- SET shelf = CASE 
--     WHEN availability_id = 1 THEN 'AB F1-1'
--     WHEN availability_id = 2 THEN 'XY F2-2'
--     WHEN availability_id = 3 THEN 'UV F3-5'
--     WHEN availability_id = 4 THEN 'CD F1-4'
--     WHEN availability_id = 5 THEN 'EF F2-3'
--     WHEN availability_id = 6 THEN 'GH F2-6'
--     WHEN availability_id = 7 THEN 'IJ F3-1'
--     WHEN availability_id = 8 THEN 'KL F1-2'
--     WHEN availability_id = 9 THEN 'MN F2-4'
--     WHEN availability_id = 10 THEN 'OP F3-3'
--     WHEN availability_id = 11 THEN 'QR F1-5'
--     WHEN availability_id = 12 THEN 'ST F2-6'
--     WHEN availability_id = 13 THEN 'UV F1-4'
--     WHEN availability_id = 14 THEN 'WX F2-2'
--     WHEN availability_id = 15 THEN 'YZ F3-1'
--     WHEN availability_id = 16 THEN 'AB F1-3'
--     WHEN availability_id = 17 THEN 'CD F1-6'
--     WHEN availability_id = 18 THEN 'EF F2-1'
--     WHEN availability_id = 19 THEN 'GH F3-3'
--     WHEN availability_id = 20 THEN 'IJ F2-5'
--     WHEN availability_id = 21 THEN 'KL F3-6'
--     WHEN availability_id = 22 THEN 'MN F1-1'
--     WHEN availability_id = 23 THEN 'OP F2-3'
--     WHEN availability_id = 24 THEN 'QR F1-2'
--     WHEN availability_id = 25 THEN 'ST F3-4'
--     WHEN availability_id = 26 THEN 'UV F2-5'
--     WHEN availability_id = 27 THEN 'WX F1-3'
--     WHEN availability_id = 28 THEN 'YZ F3-2'
--     WHEN availability_id = 29 THEN 'AB F1-5'
--     WHEN availability_id = 30 THEN 'CD F2-1'
--     WHEN availability_id = 31 THEN 'EF F3-4'
--     WHEN availability_id = 32 THEN 'GH F1-6'
--     WHEN availability_id = 33 THEN 'IJ F2-2'
--     WHEN availability_id = 34 THEN 'KL F1-4'
--     WHEN availability_id = 35 THEN 'MN F3-5'
--     WHEN availability_id = 36 THEN 'OP F2-6'
--     WHEN availability_id = 37 THEN 'QR F1-3'
--     WHEN availability_id = 38 THEN 'ST F2-4'
--     WHEN availability_id = 39 THEN 'UV F1-5'
--     WHEN availability_id = 40 THEN 'WX F2-1'
--     WHEN availability_id = 41 THEN 'YZ F3-6'
--     WHEN availability_id = 42 THEN 'AB F3-2'
--     WHEN availability_id = 43 THEN 'CD F1-2'
--     WHEN availability_id = 44 THEN 'EF F1-4'
--     WHEN availability_id = 45 THEN 'GH F3-3'
--     WHEN availability_id = 46 THEN 'IJ F2-5'
--     WHEN availability_id = 47 THEN 'KL F1-1'
--     WHEN availability_id = 48 THEN 'MN F2-3'
--     WHEN availability_id = 49 THEN 'OP F3-4'
--     WHEN availability_id = 50 THEN 'QR F2-6'
--     WHEN availability_id = 51 THEN 'ST F1-5'
--     WHEN availability_id = 52 THEN 'UV F1-3'
--     WHEN availability_id = 53 THEN 'WX F2-4'
--     WHEN availability_id = 54 THEN 'YZ F3-1'
--     WHEN availability_id = 55 THEN 'AB F2-6'
--     WHEN availability_id = 56 THEN 'CD F1-6'
--     WHEN availability_id = 57 THEN 'EF F3-2'
--     WHEN availability_id = 58 THEN 'GH F2-1'
--     WHEN availability_id = 59 THEN 'IJ F1-5'
--     WHEN availability_id = 60 THEN 'KL F3-4'
--     WHEN availability_id = 61 THEN 'MN F2-3'
--     WHEN availability_id = 62 THEN 'OP F1-2'
--     WHEN availability_id = 63 THEN 'QR F1-5'
--     WHEN availability_id = 64 THEN 'ST F3-6'
--     WHEN availability_id = 65 THEN 'UV F2-2'
--     WHEN availability_id = 66 THEN 'WX F2-5'
--     WHEN availability_id = 67 THEN 'YZ F3-3'
--     WHEN availability_id = 68 THEN 'AB F1-4'
--     WHEN availability_id = 69 THEN 'CD F3-1'
--     WHEN availability_id = 70 THEN 'EF F2-4'
--     WHEN availability_id = 71 THEN 'GH F1-3'
--     WHEN availability_id = 72 THEN 'IJ F2-6'
--     WHEN availability_id = 73 THEN 'KL F1-5'
--     WHEN availability_id = 74 THEN 'OP F3-3'
--     WHEN availability_id = 75 THEN 'QR F1-5'
--     WHEN availability_id = 76 THEN 'ST F2-6'
--     WHEN availability_id = 77 THEN 'UV F1-4'
--     WHEN availability_id = 78 THEN 'WX F2-2'
--     WHEN availability_id = 79 THEN 'YZ F3-1'
--     WHEN availability_id = 80 THEN 'AB F1-3'
--     WHEN availability_id = 81 THEN 'CD F1-6'
--     WHEN availability_id = 82 THEN 'EF F2-1'
--     WHEN availability_id = 83 THEN 'ST F2-4'
--     END;

