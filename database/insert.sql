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
('Tran Huu Nghia', 'huunghia002@e.ntu.edu.sg', 'bbe3b53eb5210306e9dcfda8be238e9a', '87654321', 'NTU');

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
-- Choose file books.csv
-- Keep other default settings
-- Specify column order (no need to enclose): isbn, title, author, description, about_author, publication_year, category, cover_path

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
