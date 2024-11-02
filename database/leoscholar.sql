-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 31, 2024 at 05:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leoscholar`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `about_author` text DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ebook_file_path` varchar(255) DEFAULT NULL,
  `audio_file_path` varchar(255) DEFAULT NULL,
  `hard_copy` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `description`, `about_author`, `publication_year`, `category`, `cover_path`, `created_at`, `ebook_file_path`, `audio_file_path`, `hard_copy`) VALUES
(1, '978-0134686998', 'Introduction to Mathematical Statistics', 'Robert V. Hogg, Joseph W. McKean, Allen T. Craig', 'For courses in mathematical statistics. Comprehensive coverage of mathematical statistics - with a proven approach Introduction to Mathematical Statistics by Hogg, McKean, and Craig enhances student comprehension and retention with numerous, illustrative examples and exercises. Classical statistical inference procedures in estimation and testing are explored extensively, and the text’s flexible organization makes it ideal for a range of mathematical statistics courses. Substantial changes to the 8th Edition - many based on user feedback - help students appreciate the connection between statistical theory and statistical practice, while other changes enhance the development and discussion of the statistical theory presented. ', 'Robert V. Hogg (deceased), Professor Emeritus of Statistics at the University of Iowa since 2001, received his B.A. in mathematics at the University of Illinois and his M.S. and Ph.D. degrees in mathematics, specializing in actuarial sciences and statistics, from the University of Iowa. Known for his gift of humor and his passion for teaching, Hogg had far-reaching influence in the field of statistics. Throughout his career, Hogg played a major role in defining statistics as a unique academic field, and he almost literally \"wrote the book\" on the subject. He wrote more than 70 research articles and co-authored four books, including Introduction of Mathematical Statistics, 6th Edition with J. W. McKean and A.T. Craig; Applied Statistics for Engineers and Physical Scientists, 3rd Edition with J. Ledolter; and A Brief Course in Mathematical Statistics, 1st Edition with E.A. Tanis. His texts have become classroom standards used by hundreds of thousands of students.\n\nAmong the many awards he received for distinction in teaching, Hogg was honored at the national level (the Mathematical Association of America Award for Distinguished Teaching), the state level (the Governor\'s Science Medal for Teaching), and the university level (Collegiate Teaching Award). His important contributions to statistical research have been acknowledged by his election to fellowship standing in the ASA and the Institute of Mathematical Statistics.\n\nElliot Tanis, Professor Emeritus of Mathematics at Hope College, received his M.S. and Ph.D. degrees from the University of Iowa. Tanis is the co-author of A Brief Course in Mathematical Statistics, 1st Edition with R. Hogg and Probability and Statistics: Explorations with MAPLE, 2nd Edition with Z. Karian. He has authored over 30 publications on statistics and is a past chairman and governor of the Michigan MAA, which presented him with both its Distinguished Teaching and Distinguished Service Awards. He taught at Hope for 35 years and in 1989 received the HOPE Award (Hope\'s Outstanding Professor Educator) for his excellence in teaching. In addition to his academic interests, Dr. Tanis is also an avid tennis player and devoted Hope sports fan.\n\nDale Zimmerman is the Robert V. Hogg Professor in the Department of Statistics and Actuarial Science at the University of Iowa.', '2018', 'Mathematics & Statistics', '1.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(2, '978-0030105678', 'Linear Algebra and Its Applications', 'Gilbert Strang', 'Renowned professor and author Gilbert Strang demonstrates that linear algebra is a fascinating subject by showing both its beauty and value. While the mathematics is there, the effort is not all concentrated on proofs. Strang\'s emphasis is on understanding. He explains concepts, rather than deduces. This book is written in an informal and personal style and teaches real mathematics. The gears change in Chapter 2 as students reach the introduction of vector spaces. Throughout the book, the theory is motivated and reinforced by genuine applications, allowing pure mathematicians to teach applied mathematics.', 'Gilbert Strang is Professor of Mathematics at the Massachusetts Institute of Technology and an Honorary Fellow of Balliol College. He was an undergraduate at MIT and a Rhodes Scholar at Oxford. His doctorate was from UCLA and since then he has taught at MIT. He has been a Sloan Fellow and a Fairchild Scholar and is a Fellow of the American Academy of Arts and Sciences. Professor Strang has published a monograph with George Fix, An Analysis of the Finite Element Method\", and has authored six widely used textbooks. He served as President of SIAM during 1999 and 2000 and he is Chair of the U.S. National Committee on Mathematics for 2003-2004.\"', '2005', 'Mathematics & Statistics', '2.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(3, '978-1032593036', 'Statistical Inference', 'George Casella, Roger Berger', 'This classic textbook builds theoretical statistics from the first principles of probability theory. Starting from the basics of probability, the authors develop the theory of statistical inference using techniques, definitions, and concepts that are statistical and natural extensions, and consequences, of previous concepts. It covers all topics from a standard inference course including: distributions, random variables, data reduction, point estimation, hypothesis testing, and interval estimation.\n\nFeatures\n- The classic graduate-level textbook on statistical inference\n- Develops elements of statistical theory from first principles of probability\n- Written in a lucid style accessible to anyone with some background in calculus\n- Covers all key topics of a standard course in inference\n- Hundreds of examples throughout to aid understanding\n- Each chapter includes an extensive set of graduated exercises\n\nStatistical Inference, Second Edition is primarily aimed at graduate students of statistics, but can be used by advanced undergraduate students majoring in statistics who have a solid mathematics background. It also stresses the more practical uses of statistical theory, being more concerned with understanding basic statistical concepts and deriving reasonable statistical procedures, while less focused on formal optimality considerations.\n\nThis is a reprint of the second edition originally published by Cengage Learning, Inc. in 2001.', 'Professor George Casella completed his undergraduate education at Fordham University and graduate education at Purdue University. He served on the faculty of Rutgers University, Cornell University, and the University of Florida. His contributions focused on the area of statistics including Monte Carlo methods, model selection, and genomic analysis. He was particularly active in Bayesian and empirical Bayes methods, with works connecting with the Stein phenomenon, on assessing and accelerating the convergence of Markov chain Monte Carlo methods, as in his Rao-Blackwellisation technique, and recasting lasso as Bayesian posterior mode estimation with independent Laplace priors.\n\nCasella was named as a Fellow of the American Statistical Association and the Institute of Mathematical Statistics in 1988, and he was made an Elected Fellow of the International Statistical Institute in 1989. In 2009, he was made a Foreign Member of the Spanish Royal Academy of Sciences.\n\nAfter receiving his doctorate in statistics from Purdue University, Professor Roger Berger held academic positions at Florida State University and North Carolina State University. He also spent two years with the National Science Foundation before coming to Arizona State University in 2004. Berger is co-author of the textbook \"Statistical Inference,\" now in its second edition. This book has been translated into Chinese and Portuguese. His articles have appeared in publications including Journal of the American Statistical Association, Statistical Science, Biometrics and Statistical Methods in Medical Research. Berger\'s areas of expertise include hypothesis testing, (bio)equivalence, generalized linear models, biostatistics, and statistics education.\n\nBerger was named as a Fellow of the American Statistical Association and the Institute of Mathematical Statistics.', '2001', 'Mathematics & Statistics', '3.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(4, '978-0201657029', 'Classical Mechanics', 'Herbert Goldstein, Charles Poole, John Safko', 'For 30 years, this book has been the acknowledged standard in advanced classical mechanics courses. This classic book enables readers to make connections between classical and modern physics - an indispensable part of a physicist\'s education. In this new edition, Beams Medal winner Charles Poole and John Safko have updated the book to include the latest topics, applications, and notation to reflect today\'s physics curriculum.', '', '2001', 'Natural Sciences', '4.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(5, '978-0306447907', 'Principles of Quantum Mechanics', 'R. Shankar', 'R. Shankar has introduced major additions and updated key presentations in this second edition of Principles of Quantum Mechanics. New features of this innovative text include an entirely rewritten mathematical introduction, a discussion of Time-reversal invariance, and extensive coverage of a variety of path integrals and their applications. Additional highlights include:\n\n- Clear, accessible treatment of underlying mathematics\n- A review of Newtonian, Lagrangian, and Hamiltonian mechanics\n- Student understanding of quantum theory is enhanced by separate treatment of mathematical theorems and physical postulates\n- Unsurpassed coverage of path integrals and their relevance in contemporary physics\n\nThe requisite text for advanced undergraduate- and graduate-level students, Principles of Quantum Mechanics, Second Edition is fully referenced and is supported by many exercises and solutions. The book’s self-contained chapters also make it suitable for independent study as well as for courses in applied disciplines.', '', '2011', 'Natural Sciences', '5.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(6, '978-1118230725', 'Fundamentals of Physics', 'David Halliday, Robert Resnick, Jearl Walker', 'The 10th edition of Halliday\'s Fundamentals of Physics, Extended building upon previous issues by offering several new features and additions. The new edition offers most accurate, extensive and varied set of assessment questions of any course management program in addition to all questions including some form of question assistance including answer specific feedback to facilitate success. The text also offers multimedia presentations (videos and animations) of much of the material that provide an alternative pathway through the material for those who struggle with reading scientific exposition. Furthermore, the book includes math review content in both a self-study module for more in-depth review and also in just-in-time math videos for a quick refresher on a specific topic. The Halliday content is widely accepted as clear, correct, and complete. The end-of-chapters problems are without peer. The new design, which was introduced in 9e continues with 10e, making this new edition of Halliday the most accessible and reader-friendly book on the market.', 'David Halliday was an American physicist known for his physics textbooks, Physics and Fundamentals of Physics, which he wrote with Robert Resnick. Both textbooks have been in continuous use since 1960 and are available in more than 47 languages.\n\nRobert Resnick was a physics educator and author of physics textbooks. He was born in Baltimore, Maryland on January 11, 1923 and graduated from the Baltimore City College high school in 1939. He received his B.A. in 1943 and his Ph.D. in 1949, both in physics from Johns Hopkins University.', '2013', 'Natural Sciences', '6.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(7, '978-1269406772\n', 'Organic Chemistry', 'Paula Yurkanis Bruice', 'All of Paula Bruice’s extensive revisions to the Seventh Edition of Organic Chemistryfollow a central guiding principle: support what modern students need in order to understand and retain what they learn in organic chemistry for successful futures in industry, research, and medicine.\n\nIn consideration of today’s classroom dynamics and the changes coming to the 2015 MCAT, this revision offers a completely new design with enhanced art throughout, reorganization of materials to reinforce fundamental skills and facilitate more efficient studying. ', 'Paula Yurkanis Bruice was raised primarily in Massachusetts. After graduating from the Girls’ Latin School in Boston, she earned an A.B. from Mount Holyoke College and a Ph.D. in chemistry from the University of Virginia. She then received an NIH postdoctoral fellowship for study in the Department of Biochemistry at the University of Virginia Medical School and held a postdoctoral appointment in the Department of Pharmacology at Yale Medical School.\n Paula has been a member of the faculty at the University of California, Santa Barbara since 1972, where she has received the Associated Students Teacher of the Year Award, the Academic Senate Distinguished Teaching Award, two Mortar Board Professor of the Year Awards, and the UCSB Alumni Association Teaching Award. Her research interests center on the mechanism and catalysis of organic reactions, particularly those of biological significance. Paula has a daughter and a son who are physicians and a son who is a lawyer. Her main hobbies are reading mystery and suspense novels and enjoying her pets (two dogs, two cats, and a parrot).', '2013', 'Natural Sciences', '7.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(8, '978-0198847816\n', 'Physical Chemistry', 'Peter Atkins and Julio de Paula', 'An excellent textbook: very easy to read and fosters great understanding. Physical chemistry can be a very mathematical and complex area, but this textbook makes it easy to understand and is something I see myself using to help me carry out both lab work and physical chemistry questions. - Sophie Shearlaw, student, University of Strathclyde\n\nThis book continuously improves and makes the learning process enjoyable. There are countless examples and exercises which can provide enormous support to both learners and lecturers. - Milan Antonijevic, lecturer, University of Greenwich\n\nThe explanation of the concepts is great. The examples are really helpful: the authors really address almost every way in which the equations could be used. Truly a helpful textbook. - Eva Pogacar, student, Heriot-Watt University\n\nCovers all the topics that you would want in an undergraduate course on physical chemistry. It includes succinct overviews of mathematical concepts that students need to understand, and is extremely well-organised, breaking material into manageable sections. - Kristin Dawn Krantzman, lecturer, College of Charleston\n\nThis textbook has always been, and continues to be, an excellent physical chemistry textbook. I highly recommend. - Mikko Linnolahti, lecturer, University of Eastern Finland\n\nExtremely useful Physical Chemistry textbook. Contains helpful overviews of useful equations and concepts. Schematics break down concepts and are good to support learning. Detailed content throughout. - Gabrielle Rennie, student, University of Strathclyde', 'Peter Atkins is a fellow of Lincoln College in the University of Oxford and Emeritus Professor of Physical Chemistry. He is the author of over seventy books for students and a general audience. His texts are market leaders around the globe. A frequent lecturer in the United States and throughout the world, he has held visiting professorships in France, Israel, Japan, China, Russia, the USA, and New Zealand. He was the founding chairman of the Committee on Chemistry Education of the International Union of Pure and Applied Chemistry and was a member of IUPAC\'s Physical and Biophysical Chemistry Division. Peter was the 2016 recipient of the American Chemical Society\'s Grady-Stack Award for the communication of chemistry. Julio de Paula is a Professor of Chemistry, Lewis & Clark College. A native of Brazil, he received a B.A. degree in chemistry from Rutgers, The State University of New Jersey, and a Ph.D. in biophysical chemistry from Yale University. His research activities encompass the areas of molecular spectroscopy, biophysical chemistry, and nanoscience. He has taught courses in general chemistry, physical chemistry, inorganic chemistry, biochemistry, environmental chemistry, instrumental analysis, and writing. Julio was a recipient of the 2020 STAR Award, given by the Research Corporation for Science Advancement. James Keeler is Associate Professor of Chemistry, University of Cambridge, and Walters Fellow in Chemistry at Selwyn College. He received his first degree and doctorate from the University of Oxford, specializing in nuclear magnetic resonance spectroscopy. He is presently Head of Department, and before that was Director of Teaching in the department and also Senior Tutor at Selwyn College.', '2022', 'Natural Sciences', '8.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(9, '978-1429229364', 'Biochemistry', 'Jeremy M. Berg et al.', 'With a balance of topic coverage and depth this updated third edition covers the subject of biochemistry, and reflects the advances made in this field since the second edition published in 1981. These advances are incorporated without loss of historical perspective and without obscuring the main goal of the text: to teach the enduring fundamentals of the discipline. Included in the third edition is a completely reorganized part one introducing the flow of information from gene to protein. It emphasizes the growing interrelatedness of molecular biology and biochemistry, and acquaints one with experimental methods of both disciplines. Also included is 150 new problems and a wealth of new material on molecular genetics and cellular processes.', '', '2010', 'Natural Sciences', '9.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(10, '978-0393884852', 'Molecular Biology of the Cell', 'Bruce Alberts et al.', 'For more than four decades, Molecular Biology of the Cell has distilled the vast amount of scientific knowledge to illuminate basic principles, enduring concepts and cutting-edge research. The Seventh Edition has been extensively revised and updated with the latest research, and has been thoroughly vetted by experts and instructors. The classic companion text, The Problems Book, has been reimagined as the Digital Problems Book in Smartwork, an interactive digital assessment course with a wide selection of questions and automatic-grading functionality. The digital format with embedded animations and dynamic question types makes the Digital Problems Book in Smartwork easier to assign than ever before-for both in-person and online classes.', 'Bruce Alberts received his PhD from Harvard University and is the Chancellor’s Leadership Chair in Biochemistry and Biophysics for Science and Education, University of California, San Francisco. He was the editor in chief of Science magazine from 2008 until 2013, and for 12 years he served as president of the U.S. National Academy of Sciences (1993–2005).\n\nRebecca Heald is an American professor of cell and developmental biology. She is currently a professor in the Department of Molecular and Cell Biology at the University of California, Berkeley. In May 2019, she was elected to the National Academy of Sciences.\n\nAlexander Johnson received his PhD from Harvard University and is a professor of microbiology and immunology at the University of California, San Francisco. He is a member of the National Academy of Sciences.\n\nDavid Morgan received his PhD from the University of California, San Francisco, and is a professor in the Department of Physiology as well as the vice dean for research for the School of Medicine. Dave is a fellow of the Royal Society of London.\n\nMartin Raff received his MD from McGill University and is emeritus professor of biology at the Medical Research Council Laboratory for Molecular Cell Biology at University College London. He is a foreign member of the National Academy of Sciences.', '2022', 'Natural Sciences', '10.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(11, '978-0134478647', 'Campbell Biology', 'Lisa A. Urry et al.', 'The World’s Most Successful Majors Biology Text and Media Program are Better than Ever!\n\nThe Eleventh Edition of the best-selling Campbell BIOLOGY sets students on the path to success in biology through its clear and engaging narrative, superior skills instruction, innovative use of art and photos, and fully integrated media resources to enhance teaching and learning.\n\nTo engage learners in developing a deeper understanding of biology, the Eleventh Edition challenges them to apply their knowledge and skills to a variety of new hands-on activities and exercises in the text and online.  Content updates throughout the text reflect rapidly evolving research, and new learning tools include Problem-Solving Exercises, Visualizing Figures, Visual Skills Questions, and more.', 'Lisa A. Urry\nLisa Urry (Chapter 1 and Units 1, 2, and 3) is Professor of Biology and Chair of the Biology Department at Mills College in Oakland, California, and a Visiting Scholar at the University of California, Berkeley. After graduating from Tufts University with a double major in biology and French, Lisa completed her Ph.D. in molecular and developmental biology at Massachusetts Institute of Technology (MIT) in the MIT/Woods Hole Oceanographic Institution Joint Program. She has published a number of research papers, most of them focused on gene expression during embryonic and larval development in sea urchins. Lisa has taught a variety of courses, from introductory biology to developmental biology and senior seminar. As a part of her mission to increase understanding of evolution, Lisa also teaches a nonmajors course called Evolution for Future Presidents and is on the Teacher Advisory Board for the Understanding Evolution website developed by the University of California Museum of Paleontology. Lisa is also deeply committed to promoting opportunities for women and underrepresented minorities in science.\n\n\nNeil A. Campbell\nNeil Campbell (1946–2004) combined the investigative nature of a research scientist with the soul of an experienced and caring teacher. He earned his M.A. in zoology from the University of California, Los Angeles, and his Ph.D. in plant biology from the University of California, Riverside, where he received the Distinguished Alumnus Award in 2001. Neil published numerous research articles on desert and coastal plants and how the sensitive plant (Mimosa) and other legumes move their leaves. His 30 years of teaching in diverse environments included introductory biology courses at Cornell University, Pomona College, and San Bernardino Valley College, where he received the college’s first Outstanding Professor Award in 1986. He was a visiting scholar in the Department of Botany and Plant Sciences at the University of California, Riverside. Neil was the lead author of Campbell Biology: Concepts & Connections, Campbell Essential Biology, and CAMPBELL BIOLOGY.\n\n', '2017', 'Natural Sciences', '11.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(12, '978-1119142287', 'Principles of Genetics', 'D. Peter Snustad and Michael J. Simmons', 'Principles of Genetics is one of the most popular texts in use for the introductory course. It opens a window on the rapidly advancing science of genetics by showing exactly how genetics is done. Throughout, the authors incorporate a human emphasis and highlight the role of geneticists to keep students interested and motivated. The seventh edition has been completely updated to reflect the latest developments in the field of genetics. Principles of Genetics continues to educate today’s students for tomorrows science by focusing on features that aid in content comprehension and application. This text is an unbound, three hole punched version.', 'D. Peter Snustad and Michael J. Simmons are the authors of Principles of Genetics, Binder Ready Version, 7th Edition, published by Wiley.', '2015', 'Natural Sciences', '12.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(13, '978-9332518742\n', 'Computer Networks', 'Andrew S. Tanenbaum and David J. Wetherall', 'The book is an introduction to computer networking for those seeking information on various aspects of establishing and maintaining wireless networks. A computer network is a group of computers that share data over a wireless or cable-connected set-up. To establish a computer network one needs to be equipped with the basic guidelines that help to lay down complex networks.\n\nThe book places emphasis on topics of 802.11, 802.16, Bluetooth, paired and fixed coverage of ADSL, 3G cellular, gigabit Ethernet, MLPS and peer-to-peer networks. The latest edition comprehensively discusses fibre to the home, RIFD, delay torrent networking, 802.11 security, internet routing, congestion control, quality of service, real-time transport and content distribution.\n\nPublished by Pearson Education, the 5th Edition includes eight chapters that begin with the petite topics of physical layer, data link layer and medium access control sub-layer. Tanenbaum gradually gets to the more intricate topics of network layer, transport layer and application layer where various concepts have been explained in a plain manner. The book also explains network security to assist in creating a secure networking system between computers.', 'Andrew Tanenbaum, born in 1944, is an American computer scientist who is a professor emeritus at Vrije Universiteit at Amsterdam, Netherlands. Known for being the author of MINIX, a unix like free operating system, Tanenbaum is devoted to his teaching profession.', '2013', 'Computer Science & Technology', '13.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(14, '978-1292401133\n', 'Artificial Intelligence: A Modern Approach', 'Stuart Russell and Peter Norvig', 'Thelong-anticipated revision of ArtificialIntelligence: A Modern Approach explores the full breadth and depth of the field of artificialintelligence (AI). The 4th Edition brings readers up to date on the latest technologies,presents concepts in a more unified manner, and offers new or expanded coverageof machine learning, deep learning, transfer learning, multi agent systems,robotics, natural language processing, causality, probabilistic programming,privacy, fairness, and safe AI.', 'Stuart Russell was born in 1962 in Portsmouth, England. He received his B.A. with first-class honours in physics from Oxford University in 1982, and his Ph.D. in computer science from Stanford in 1986. He then joined the faculty of the University of California, Berkeley, where he is a Professor and former Chair of Computer Science, Director of the Centre for Human-Compatible AI, and holder of the Smith–Zadeh Chair in Engineering.\n\nIn 1990, he received the Presidential Young Investigator Award of the National Science Foundation, and in 1995 he was co-winner of the Computers and Thought Award. He is a Fellow of the American Association for Artificial Intelligence, the Association for Computing Machinery, and the American Association for the Advancement of Science, and Honorary Fellow of Wadham College, Oxford, and an Andrew Carnegie Fellow. He held the Chaire Blaise Pascal in Paris from 2012 to 2014. He has published over 300 papers on a wide range of topics in artificial intelligence. His other books include: The Use of Knowledge in Analogy and Induction, Do the Right Thing: Studies in Limited Rationality (with Eric Wefald), and Human Compatible: Artificial Intelligence and the Problem of Control.\n\nPeter Norvig is currently Director of Research at Google, Inc., and was the director responsible for the core Web search algorithms from 2002 to 2005. He is a Fellow of the American Association for Artificial Intelligence and the Association for Computing Machinery. Previously, he was head of the Computational Sciences Division at NASA Ames Research Center, where he oversaw NASA\'s research and development in artificial intelligence and robotics, and chief scientist at Junglee, where he helped develop one of the first Internet information extraction services. He received a B.S. in applied mathematics from Brown University and a Ph.D. in computer science from the University of California at Berkeley.\n\nHe received the Distinguished Alumni and Engineering Innovation awards from Berkeley and the Exceptional Achievement Medal from NASA. He has been a professor at the University of Southern California and are research faculty member at Berkeley. His other books are: Paradigms of AI Programming: Case Studies in Common Lisp, Verbmobil: A Translation System for Face-to-Face Dialog, and Intelligent Help Systems for UNIX.\n\nThe two authors shared the inaugural AAAI/EAAI Outstanding Educator award in 2016.', '2021', 'Computer Science & Technology', '14.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(15, '978-0201633610', 'Design Patterns: Elements of Reusable Object-Oriented Software', 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides', 'Capturing a wealth of experience about the design of object-oriented software, four top-notch designers present a catalog of simple and succinct solutions to commonly occurring design problems. Previously undocumented, these 23 patterns allow designers to create more flexible, elegant, and ultimately reusable designs without having to rediscover the design solutions themselves.\n\nThe authors begin by describing what patterns are and how they can help you design object-oriented software. They then go on to systematically name, explain, evaluate, and catalog recurring designs in object-oriented systems. With Design Patterns as your guide, you will learn how these important patterns fit into the software development process, and how you can leverage them to solve your own design problems most efficiently.\n\nEach pattern describes the circumstances in which it is applicable, when it can be applied in view of other design constraints, and the consequences and trade-offs of using the pattern within a larger design. All patterns are compiled from real systems and are based on real-world examples. Each pattern also includes code that demonstrates how it may be implemented in object-oriented programming languages like C++ or Smalltalk.', 'Dr. Erich Gamma is technical director at the Software Technology Center of Object Technology International in Zurich, Switzerland. Dr. Richard Helm is a member of the Object Technology Practice Group in the IBM Consulting Group in Sydney, Australia. Dr. Ralph Johnson is a faculty member at the University of Illinois at Urbana-Champaign\'s Computer Science Department. John Vlissides is a member of the research staff at the IBM T. J. Watson Research Center in Hawthorne, New York. He has practiced object-oriented technology for more than a decade as a designer, implementer, researcher, lecturer, and consultant. In addition to co-authoring Design Patterns: Elements of Reusable Object-Oriented Software, he is co-editor of the book Pattern Languages of Program Design 2 (both from Addison-Wesley). He and the other co-authors of Design Patterns are recipients of the 1998 Dr. Dobb\'s Journal Excellence in Programming Award.', '1995', 'Computer Science & Technology', '15.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(16, '978-1478634058', 'Theories of Human Communication', 'Stephen W. Littlejohn, Karen A. Foss, John G. Oetzel', 'For over forty years, Theories of Human Communication has energized classroom learning. John Oetzel joined the team of Stephen Littlejohn and Karen Foss, adding his expertise in intercultural, health, and organizational communication.\nThe extensively updated edition is organized around two themes: elements of the basic communication model (communicator, message, medium, and \"beyond\" human communication) and communication contexts (relationship, group, organization, health, social media, culture, and society). A new chapter discusses frameworks by which theories can be organized, revealing how they contribute to and are impacted by larger issues about the nature of inquiry. Other outstanding features:\n\nThe text presents a full complement of foundational theories for the communication discipline, plus the contemporary evolutions of those theories.\n\nComprehensive, up-to-date coverage is based on a survey of the articles in communication journals over the last five years.\n\nNew areas covered include health, social media, and communication between humans and nature/objects/technology/the divine.\n\nEach chapter covers an average of thirteen theories, half of which are new to this edition. \"From the Source\" boxes give students a look at the theorists behind the theories their inspirations, motivations, and goals. Chapters are organized around the concepts and themes inherent to how that area has been studied.\n\nStudents in upper-division undergraduate and in graduate-level courses will benefit from this well-organized, absorbing treatment of communication theory.\n\nNot-for-sale instructor\'s resource materials available online to college and university faculty only; contact publisher directly.', '', '2016', 'Humanities & Social Science', '16.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(17, '978-1509539222', 'Sociology', 'Anthony Giddens et al.', 'The indispensable guide to understanding the world we make and the lives we lead.\n\nThis thoroughly revised and updated ninth edition remains unrivalled in its vibrant, engaging and authoritative introduction to sociology. The authors provide a commanding overview of the latest global developments and new ideas in this fascinating subject. Classic debates are also given careful coverage, with even the most complex ideas explained in a straightforward way.\n\nWritten in a fluent, easy-to-follow style, the book manages to be intellectually rigorous but still very accessible. With a strong focus on interactive pedagogy, it aims to engage and excite readers, helping them to see the enduring value of thinking sociologically.\n\nThe ninth edition includes:\n\na solid foundation in the basics of sociology: its purpose, methodology and theories;\nup-to-the-minute overviews of key topics in social life, from gender, personal life and poverty, to globalization, the media and politics;\nstimulating examples of what sociology has to say about key issues in our contemporary world, such as climate change, growing inequality and rising polarization in societies across the world;\na strong focus on global connections and the ways that digital technologies are radically transforming our lives;\nquality pedagogical features, such as ‘Classic Studies’ and ‘Global Society’ boxes, and ‘Thinking Critically’ reflection points, as well as end-of-chapter activities inviting readers to engage with popular culture and original research articles to gather sociological insights.\nThe ninth edition sets the standard for introductory sociology in a complex world. It is the ideal teaching text for first-year university and college courses, and will help to inspire a new generation of sociologists.', 'Anthony Giddens is the former director of the London School of Economics and Political Science, and is now a member of the UK House of Lords. His many books include The Third Way and The Consequences of Modernity.', '2021', 'Humanities & Social Science', '17.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(18, '978-0192803955\n', 'Political Philosophy: A Very Short Introduction', 'David Miller', 'This book introduces readers to the concepts of political philosophy. It starts by explaining why the subject is important and how it tackles basic ethical questions such as \'how should we live together in society?\' It looks at political authority, the reasons why we need politics at all, the limitations of politics, and whether there are areas of life that shouldn\'t be governed by politics. It explores the connections between political authority and justice, a constant theme in political philosophy, and the ways in which social justice can be used to regulate rather than destroy a market economy. David Miller discusses why nations are the natural units of government and whether the rise of multiculturalism and transnational co-operation will change this: will we ever see the formation of a world government? \n\nABOUT THE SERIES: The Very Short Introductions series from Oxford University Press contains hundreds of titles in almost every subject area. These pocket-sized books are the perfect way to get ahead in a new subject quickly. Our expert authors combine facts, analysis, perspective, new ideas, and enthusiasm to make interesting and challenging topics highly readable.', 'David Miller is Professor of Political Theory, University of Oxford, and an Official Fellow of Nuffield College. He has written books and articles on many aspects of political theory and philosophy. In 2002 he was elected to a Fellowship of the British Academy. He lives in Oxford and is married with three children.', '2003', 'Humanities & Social Science', '18.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(19, '978-1292446318', 'Corporate Finance', 'Jonathan Berk and Peter DeMarzo', 'Using the valuation framework based on the Law of One Price, top researchers Jonathan Berk and Peter DeMarzo have set the new canon for corporate finance texts. Corporate Finance blends coverage of time-tested principles and the latest advancements with the practical perspective of the financial manager. You can “practice finance to learn finance” by solving problems like those faced by today\'s professionals.\n\nThe 6th Edition features the latest research, data, events and technologies to help you develop the tools you need to make sound financial decisions.', 'Jonathan Berk is the A.P. Giannini Professor of Finance at the Graduate School of Business, Stanford University, and is a Research Associate at the National Bureau of Economic Research.\n\nBefore coming to Stanford, he was the Sylvan Coleman Professor of Finance at Hans School of Business at the University of California, Berkeley. Prior to earning his Ph.D., he worked as an Associate at Goldman Sachs (where his education in finance really began).\n\nPeter DeMarzo is the Staehelin Family Professor of Finance at the Graduate School of Business, Stanford University.\n\nHe is the current President of the American Finance Association and a Research Associate at the National Bureau of Economic Research. He teaches MBA and Ph.D. courses in Corporate Finance and Financial Modeling.', '2023', 'Business & Finance', '19.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(20, '978-1118334324', 'Financial Accounting', 'Jerry J. Weygandt, Paul D. Kimmel, Donald E. Kieso', 'More students get accounting when using Weygandt\'s Financial Accounting, 9th Edition because of the unique Framework of Success created and refined by the authors based on years of teaching and course design  experience. The Team for Success is focused on helping millennial students get the most out of their accounting courses in the digital age, and on helping instructors deliver the most effective courses whether face-to-face, hybrid, or online with model course plans designed for easy and effective implementation. Financial Accounting, 9th Edition by Weygandt, Kimmel, Kieso provides students with a clear and comprehensive introduction to financial accounting that begins with the building blocks of the accounting cycle. WileyPLUS sold separately from text.', 'Jerry J. Weygandt, PhD, CPA, is Arthur Andersen Alumni Professor of Accounting at the University of Wisconsin-Madison. He holds a Ph.D. in accounting from the University of Illinois. Articles by Professor Weygandt have appeared in the Accounting Review, Journal of Accounting Research, Accounting Horizons, Journal of Accountancy, and other academic and professional journals. These articles have examined such financial reporting issues as accounting for price-level adjustments, pensions, convertible securities, stock option contracts, and interim reports. Professor Weygandt is author of other accounting and financial reporting books and is a member of the American Accounting Association, the American Institute of Certified Public Accountants, and the Wisconsin Society of Certified Public Accountants. He has served on numerous committees of the American Accounting Association and as a member of the editorial board of the Accounting Review; he also has served as President and Secretary-Treasurer of the American Accounting Association. He is the recipient of the Wisconsin Institute of CPAs Outstanding Educator\'s Award and the Lifetime Achievement Award. In 2001 he received the American Accounting Association\'s Outstanding Accounting Educator Award.\n\nPaul D. Kimmel, PhD, CPA, received his bachelor\'s degree from the University of Minnesota and his doctorate in accounting from the University of Wisconsin. He is an Associate Professor at the University of Wisconsin -Milwaukee, and has public accounting experience with Deloitte & Touche (Minneapolis). He was the recipient of the UWM School of Business Advisory Council Teaching Award and the Reggie Taite Excellence in Teaching Award, and is a three-time winner of the Outstanding Teaching Assisting Award at the University of Wisconsin. He is also a recipient of the Elijah Watts Sells Award for Honorary Distinction for his results on the CPA exam. He is a member of the American Accounting Association and has published articles in Accounting Review, Accounting Horizons, Advances in Management Accounting, Managerial Finance, Issues in Accounting Education, Journal of Accounting Education, as well as other journals. His research interests include accounting for financial instruments and innovation in accounting education. He has published papers and given numerous talks on incorporating critical thinking into accounting education, and helped prepare a catalog of critical thinking resources for the Federated Schools of Accountancy.\n\nDonald E.  Kieso, PhD, CPA, received his bachelor\'s degree from Aurora University and his doctorate in accounting from the University of Illinois. He is currently the KPMG Peat Marwick Emeritus Professor of Accounting at Northern Illinois University. He has public  accounting experience with Price Waterhouse & Co. (San  Francisco and Chicago) and Arthur Andersen & Co. (Chicago) and  research experience with the Research Division of the American  Institute of Certified Public Accountants (New York). He has done  postdoctorate work as a Visiting Scholar at the University of California at Berkeley and is a recipient of NIU\'s Teaching Excellence Award and four Golden Apple Teaching Awards. Professor Kieso is the author of other accounting and business books and is a member of the American Accounting Association, the American Institute of Certified Public Accountants, and the Illinois CPA Society. He is currently serving on the Board of Trustees and Executive Committee of Aurora University, as a member of the Board of Directors of Castle BancGroup Inc., and as Treasurer and  Director of Valley West Community Hospital.', '2013', 'Business & Finance', '20.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(21, '978-0073511290\n', 'Economics', 'Paul Samuelson and William Nordhaus', 'Samuelson\'s text was first published in 1948, and it immediately became the authority for the principles of economics courses. The book continues to be the standard-bearer for principles courses, and this revision continues to be a clear, accurate, and interesting introduction to modern economics principles. Bill Nordhaus is now the primary author of this text, and he has revised the book to be as current and relevant as ever.', 'William D. Nordhaus, one of the most important American economists, teaches at Yale University.', '2009', 'Business & Finance', '21.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(22, '978-0702077050', 'Gray\'s Anatomy: The Anatomical Basis of Clinical Practice', 'Susan Standring', 'Susan Standring, MBE, PhD, DSc, FKC, Hon FAS, Hon FRCS Trust Gray\'s. Building on over 160 years of anatomical excellence In 1858, Drs Henry Gray and Henry Vandyke Carter created a book for their surgical colleagues that established an enduring standard among anatomical texts. After more than 160 years of continuous publication, Gray\'s Anatomy remains the definitive, comprehensive reference on the subject, offering ready access to the information you need to ensure safe, effective practice. This 42nd edition has been meticulously revised and updated throughout, reflecting the very latest understanding of clinical anatomy from the world\'s leading clinicians and biomedical scientists. The book\'s acclaimed, lavish art programme and clear text has been further enhanced, while major advances in imaging techniques and the new insights they bring are fully captured in state of the art X-ray, CT, MR and ultrasonic images. The accompanying eBook version is richly enhanced with additional content and media, covering all the body regions, cell biology, development and embryogenesis - and now includes two new systems-orientated chapters. This combines to unlock a whole new level of related information and interactivity, in keeping with the spirit of innovation that has characterised Gray\'s Anatomy since its inception. Each chapter has been edited by international leaders in their field, ensuring access to the very latest evidence-based information on topics Over 150 new radiology images, offering the very latest X-ray, multiplanar CT and MR perspectives, including state-of-the-art cinematic rendering The downloadable Expert Consult eBook version included with your (print) purchase allows you to easily search all of the text, figures, references and videos from the book on a variety of devices Electronic enhancements include additional text, tables, illustrations, labelled imaging and videos, as well as 21 specially commissioned \'Commentaries\' on new and emerging topics related to anatomy Now featuring two extensive electronic chapters providing full coverage of the peripheral nervous system and the vascular and lymphatic systems. The result is a more complete, practical and engaging resource than ever before, which will prove invaluable to all clinicians who require an accurate, in-depth knowledge of anatomy.', '', '2020', 'Medicine', '22.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(23, '978-0323597128', 'Guyton and Hall Textbook of Medical Physiology', 'John E. Hall', 'Known for its clear presentation style, single-author voice, and focus on content most relevant to clinical and pre-clinical students, Guyton and Hall Textbook of Medical Physiology, 14th Edition, employs a distinctive format to ensure maximum learning and retention of complex concepts. A larger font size emphasizes core information, while supporting information, including clinical examples, are detailed in smaller font and highlighted in pale blue - making it easy to quickly skim the essential text or pursue more in-depth study. This two-tone approach, along with other outstanding features, makes this bestselling text a favorite of students worldwide. Offers a clinically oriented perspective written with the clinical and preclinical student in mind, bridging basic physiology with pathophysiology. Focuses on core material and how the body maintains homeostasis to remain healthy, emphasizing the important principles that will aid in later clinical decision making. Presents information in short chapters using a concise, readable voice that facilitates learning and retention. Contains more than 1,200 full-color drawings and diagrams - all carefully crafted to make physiology easier to understand. Features expanded clinical coverage including obesity, metabolic and cardiovascular disorders, Alzheimer\'s disease, and other degenerative diseases. Includes online access to interactive figures, new audio of heart sounds, animations, self-assessment questions, and more. Enhanced eBook version included with purchase. Your enhanced eBook allows you to access all of the text, figures, and references from the book on a variety of devices.', '', '2020', 'Medicine', '23.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(24, '978-0393533354\n', 'The Immune System', 'Peter Parham', 'The Immune System is a concise yet thorough human-oriented introduction to how the human immune system works. It provides an up-to-date presentation of the field, written in an accessible style, replete with relevant medical examples. Plentiful illustrations and micrographs complement and illuminate the explanations. The Fifth Edition is supported by InQuizitive, Norton\'s award-winning, easy-to-use adaptive learning tool that provides student practice and promotes critical thinking.', 'Peter Parham is a professor in the departments of structural biology as well as microbiology and immunology at Stanford University. Dr. Parham\'s research has focused on proteins of the human immune system that vary greatly between individuals and populations. These differences, the consequence of natural selection, not only modulate the immune response to infection and cancer, but also influence the success of reproduction and therapeutic transplantation of cells, tissues, and organs. He was elected fellow of the Royal Society in 2008.', '2021', 'Medicine', '24.jpg', '2024-10-25 03:50:57', NULL, NULL, 1);
INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `description`, `about_author`, `publication_year`, `category`, `cover_path`, `created_at`, `ebook_file_path`, `audio_file_path`, `hard_copy`) VALUES
(25, '978-1285052458', 'Language: Its Structure and Use', 'Edward Finegan', 'Whatever you do and wherever you go, you use language to interact. This text explains what human language is and how it works, giving you a look into the multiple fascinating and surprising facets of this uniquely human trait. You willl find many opportunities to ask your own questions and explore the language in use all around you.', 'Edward Finegan (MA and PhD, Ohio University) specializes in sociolinguistics, discourse analysis, forensic linguistics, and the history and structure of the English language. He served as chair of the Department of Linguistics at USC and currently serves as director of USC\'s Center for Excellence in Teaching. President of the International Association of Forensic Linguists, Finegan is editor of DICTIONARIES: THE JOURNAL OF THE DICTIONARY SOCIETY OF NORTH AMERICA and has been Liberal Arts Fellow in Law and Linguistics, Harvard University; Visiting Professor at University of Zurich; and Visiting Scholar at University of Helsinki. He also served as Director of American Language Institute/National Iranian Radio and Television [1975-1976 in peaceful times]. He is the recipient of many teaching awards and honors.', '2014', 'Literature & Language', '25.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(26, '978-0192806871', 'The Oxford Companion to English Literature', 'Dinah Birch', 'This edition of the classic reference has been thoroughly revised and updated, offering unrivalled coverage of English literature. It continues to offer detailed and authoritative information on authors and works, alongside extended coverage of popular literary genres, as well as of the themes and concepts encountered by students.', '', '2009', 'Literature & Language', '26.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(27, '978-0230368316', 'A History of English Literature', 'Michael Alexander', 'This comprehensive text traces the development of one of the world’s richest literatures from the Old English period through to the present day, discussing a wide range of key authors without losing its clarity or verve.  Building on the book\'s established reputation and success, the third edition has been revised and updated throughout. It now provides a full final chapter on the contemporary scene, with more on genres and the impact of globalization.\nThis accessible book remains the essential companion for students of English literature and literary history, or for anyone wishing to follow the unfolding of writing in England from its beginnings. It is ideal for those who know a few landmark texts, but little of the literary landscape that surrounds them; those who want to know what English literature consists of; and those who simply want to read its fascinating story.', '', '2013', 'Literature & Language', '27.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(28, '978-0300179354', 'Interaction of Color', 'Josef Albers', '“One of the most important books on color ever written.”-Michael Hession, Gizmodo\n \n“Interaction of Color with its illuminating visual exercises and mind-bending optical illusions, remains an indispensable blueprint to the art of seeing. . . . An essential piece of visual literacy.“-Maria Popova, Brain Pickings\n \nJosef Albers’s classic Interaction of Color is a masterwork in art education. Conceived as a handbook and teaching aid for artists, instructors, and students, this influential book presents Albers’s singular explanation of complex color theory principles.\n \nOriginally published by Yale University Press in 1963 as a limited silkscreen edition with 150 color plates, Interaction of Color first appeared in paperback in 1971, featuring ten color studies chosen by Albers, and has remained in print ever since. With over a quarter of a million copies sold in its various editions since 1963, Interaction of Color  remains an essential resource on color, as pioneering today as when Albers first created it.\n \nFifty years after Interaction’s initial publication, this anniversary edition presents a significantly expanded selection of close to sixty color studies alongside Albers’s original text, demonstrating such principles as color relativity, intensity, and temperature; vibrating and vanishing boundaries; and the illusion of transparency and reversed grounds. A celebration of the longevity and unique authority of Albers’s contribution, this landmark edition will find new audiences in studios and classrooms around the world.', 'Josef Albers, one of the most influential artist-educators of the twentieth century, was a member of the Bauhaus group in Germany during the 1920s. In 1933 he came to the United States, where he taught at Black Mountain College for sixteen years. In 1950 he joined the faculty at Yale University as chairman of the department of design. Albers was elected to the National Institute of Arts and Letters in 1968 and was professor emeritus of art at Yale until his death in 1976. Nicholas Fox Weber is executive director of the Josef and Anni Albers Foundation.', '2013', 'Arts & Design', '28.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(29, '978-1616893323', 'Graphic Design: The New Basics', 'Ellen Lupton and Jennifer Cole Phillips', '\"A longstanding excellent primer, in an equally excellent updated edition.\"—Print\n\nEllen Lupton and Jennifer Cole Phillips\'s celebrated introduction to graphic design, available in a revised and updated edition. Graphic Design: The New Basics explains the key concepts of visual language that inform any work of design. A foundational graphic design book for students, Lupton and Phillips explore the formal elements of design through visual demonstrations and concise commentary. From logos to letterhead to complex website design, this is a graphic design book for everyone, no matter your design project or focus.\n\nTopics include:\n- Color\n- Texture\n- Rhythm and balance\n- Hierarchy\n- Layers\n- Grids\n- And much more!\n\nThe new revised edition features new chapters on:\n- Visualizing data\n- Typography\n- Modes of representation\n- Gestalt principles\n\nSixteen new pages of student and professional work covering such topics as working with grids and designing with color make this a course adoption favorite in any graphic design program and graphic design school. Graphic Design: The New Basics is an invaluable introduction to the field of graphic design for beginners from two accomplished designers and design educators.', 'Ellen Lupton is the author of thirteen books with PAPress. She is senior curator of contemporary design at Cooper-Hewitt, Smithsonian Design Museum.\n\nJennifer Cole Phillips is principal of J. Cole Phillips Design. Lupton and Phillips are directors of the Graphic Design MFA program at the Maryland Institute College of Art and the recipient of numerous awards for their work as designers and educators.', '2015', 'Arts & Design', '29.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(30, '978-0072407006', 'Art Fundamentals: Theory and Practice', 'Otto G. Ocvirk et al.', 'By using more color fine art reproductions than any other text to illustrate the concepts of design,Ocvirk introduces students to both the fundamental elements of art and to the rich and varied history of their uses. Art Fundamentals offers a wealth of full color and black-and-white fine art reproductions and a rich Instructor\'s Resource Manual but still remains the best value on the market. This new edition exposes students to a more diverse range of aesthetic and cultural perspectives and brings in more examples from the contemporary scene.', '', '2001', 'Arts & Design', '30.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(31, '978-1119724179', 'Engineering Mechanics: Dynamics', 'J.L. Meriam and L.G. Kraige', 'Engineering Mechanics: Dynamics provides a solid foundation of mechanics principles and helps students develop their problem-solving skills with an extensive variety of engaging problems related to engineering design. More than 50% of the homework problems are new, and there are also a number of new sample problems. To help students build necessary visualization and problem-solving skills, this product strongly emphasizes drawing free–body diagrams, the most important skill needed to solve mechanics problems.', 'Dr. James L. Meriam has contributed to the field of engineering mechanics as one of the premier engineering educators during the second half of the twentieth century. Dr. Meriam earned his B.E., M. Eng., and Ph.D. degrees from Yale University. He had early industrial experience with Pratt and Whitney Aircraft and the General Electric Company. During the Second World War, he served in the U.S. Coast Guard. He was a member of the faculty of the University of California-Berkeley, Dean of Engineering at Duke University, a faculty member at the California Polytechnic State University, and visiting professor at the University of California-Santa Barbara. He retired in 1990. Professor Meriam always placed great emphasis on teaching, and this trait was recognized by his students wherever he taught. At Berkeley in 1963, he was the first recipient of the Outstanding Faculty Award of Tau Beta Pi, given primarily for excellence in teaching. In 1978, he received the Distinguished Educator Award for Outstanding Service to Engineering Mechanics Education from the American Society for Engineering Education, and in 1992 was the Society\'s recipient of the Benjamin Garver Lamme Award, which is ASEE\'s highest annual national award.\n\nDr. L. G. Kraige, coauthor of the Engineering Mechanics series since the early 1980s, has also made significant contributions to mechanics education. Dr. Kraige earned his B.S., M.S., and Ph.D. degrees at the University of Virginia, principally in aerospace engineering, and he currently serves as Professor of Engineering Science and Mechanics at Virginia Polytechnic Institute and State University. In addition to his widely recognized research and publications in the field of spacecraft dynamics. Professor Kraige has devoted his attention to the teaching of mechanics at both introductory and advanced levels. His outstanding teaching has been widely recognized and has earned him teaching awards at the departmental, college, university, state, regional, and national levels.', '2020', 'Engineering', '31.jpg', '2024-10-25 03:50:57', NULL, NULL, 1),
(32, '978-0073398273', 'Fluid Mechanics', 'Frank M. White', 'White\'s Fluid Mechanics offers students a clear and comprehensive presentation of the material that demonstrates the progression from physical concepts to engineering applications and helps students quickly see the practical importance of fluid mechanics fundamentals. The wide variety of topics gives instructors many options for their course and is a useful resource to students long after graduation. The book’s unique problem-solving approach is presented at the start of the book and carefully integrated in all examples. Students can progress from general ones to those involving design, multiple steps and computer usage.', 'Frank M. White is Professor Emeritus of Mechanical and Ocean Engineering at the University of Rhode Island. He studied at Georgia Tech and M.I.T. In 1966 he helped  found, at URI, the first department of ocean engineering in the country. Known  primarily as a teacher and writer, he has received eight teaching awards and has written  four textbooks on fluid mechanics and heat transfer. From 1979 to 1990 he was editor-in-chief of the ASME Journal of Fluids  Engineering and then served from 1991 to 1997 as chairman of the ASME Board of Editors and of the Publications Committee. He is a Fellow of ASME and in 1991  received the ASME Fluids Engineering Award.', '2015', 'Engineering', '32.jpg', '2024-10-25 03:50:58', NULL, NULL, 1),
(33, '978-1292223124', 'Electrical Engineering: Principles and Applications', 'Allan R. Hambley', 'The #1 title in its market, Electrical Engineering: Principles and Applications helps students learn electrical-engineering fundamentals with minimal frustration. Its goals are to present basic concepts in a general setting, to show students how the principles of electrical engineering apply to specific problems in their own fields, and to enhance the overall learning process. This book covers circuit analysis, digital systems, electronics, and electromechanics at a level appropriate for either electrical-engineering students in an introductory course or non-majors in a survey course. A wide variety of pedagogical features stimulate student interest and engender awareness of the material’s relevance to their chosen profession. The only essential prerequisites are basic physics and single-variable calculus. The 7th Edition features technology and content updates throughout the text.', 'Allan R. Hambley received his B.S. degree from Michigan Technological University, his M.S. degree from Illinois Institute of Technology, and his Ph.D. from Worcester Polytechnic Institute. He has worked in industry for Hazeltine Research Inc., Warwick Electronics, and Harris Government Systems. He is currently Professor of Electrical Engineering at Michigan Tech. The Michigan Tech chapter of Eta Kappa Nu named him the Outstanding Electrical Engineering Teacher of the Year in 1995. He has won the National Technological University Outstanding Instructor Award six times for his courses in communication systems. The American Society for Engineering Education presented him with the 1998 Meriam Wiley Distinguished Author Award for the first edition of his book, Electronics. His hobbies include fishing, boating in remote areas of Lake Superior, and gardening.', '2018', 'Engineering', '33.jpg', '2024-10-25 03:50:58', NULL, NULL, 1),
(34, '978-0060935467', 'To Kill a Mockingbird', 'Harper Lee', 'Voted America\'s Best-Loved Novel in PBS\'s The Great American Read\n\nHarper Lee\'s Pulitzer Prize-winning masterwork of honor and injustice in the deep South—and the heroism of one man in the face of blind and violent hatred\n\nOne of the most cherished stories of all time, To Kill a Mockingbird has been translated into more than forty languages, sold more than forty million copies worldwide, served as the basis for an enormously popular motion picture, and was voted one of the best novels of the twentieth century by librarians across the country. A gripping, heart-wrenching, and wholly remarkable tale of coming-of-age in a South poisoned by virulent prejudice, it views a world of great beauty and savage inequities through the eyes of a young girl, as her father—a crusading local lawyer—risks everything to defend a black man unjustly accused of a terrible crime.', 'Harper Lee was born in 1926 in Monroeville, Alabama. She is the author of the acclaimed To Kill a Mockingbird and Go Set a Watchman, which became a phenomenal #1 New York Times bestseller when it was published in July 2015. Ms. Lee received the Pulitzer Prize, the Presidential Medal of Freedom, and numerous other literary awards and honors. She died on February 19, 2016.', '2005', 'Fiction & Novels', '34.jpg', '2024-10-25 03:50:58', NULL, NULL, 1),
(35, '978-0141330167', 'Pride and Prejudice', 'Jane Austen', 'When two rich young gentlemen move to town, they don\'t go unnoticed - especially when Mrs Bennett vows to have one of her five daughters marry into their fortunes. But love, as Jane and Elizabeth Bennett soon discover, is rarely straightforward, and often surprising. It\'s only a matter of time until their own small worlds are turned upside down and they discover that first impressions can be the most misleading of all.\nWith a behind-the-scenes journey, including an author profile, a guide to who\'s who, activities and more.\nLightly abridged for Puffin Classics.', 'Jane Austen, the daughter of a clergyman, was born in Hampshire in 1775, and later lived in Bath and the village of Chawton. As a child and teenager, she wrote brilliantly witty stories for her family\'s amusement, as well as a novella, Lady Susan. Her first published novel was Sense and Sensibility, which appeared in 1811 and was soon followed by Pride and Prejudice, Mansfield Park and Emma. Austen died in 1817, and Persuasion and Northanger Abbey were published posthumously in 1818.', '2018', 'Fiction & Novels', '35.jpg', '2024-10-25 03:50:58', NULL, 'pride_and_prejudice_01_austen.mp3', 1),
(36, '979-8745274824', 'The Great Gatsby', 'F. Scott Fitzgerald', '“So we beat on, boats against the current, borne back ceaselessly into the past.” - F. Scott Fitzgerald, The Great Gatsby\n\nThe Great Gatsby is a 1925 novel by American writer F. Scott Fitzgerald. Set in the Jazz Age on Long Island, the novel depicts narrator Nick Carraway\'s interactions with mysterious millionaire Jay Gatsby and Gatsby\'s obsession to reunite with his former lover, Daisy Buchanan.\n\nA youthful romance Fitzgerald had with socialite Ginevra King, and the riotous parties he attended on Long Island\'s North Shore in 1922 inspired the novel. Following a move to the French Riviera, he completed a rough draft in 1924. He submitted the draft to editor Maxwell Perkins, who persuaded Fitzgerald to revise the work over the following winter. After his revisions, Fitzgerald was satisfied with the text, but remained ambivalent about the book\'s title and considered several alternatives. The final title he desired was Under the Red, White, and Blue. Painter Francis Cugat\'s final cover design impressed Fitzgerald who incorporated a visual element from the art into the novel.\n\nGatsby continues to attract popular and scholarly attention. The novel was most recently adapted to film in 2013 by director Baz Luhrmann, while contemporary scholars emphasize the novel\'s treatment of social class, inherited wealth compared to those who are self-made, race, environmentalism, and its cynical attitude towards the American dream. The Great Gatsby is widely considered to be a literary masterpiece and a contender for the title of the Great American Novel.', 'F. Scott Fitzgerald (1896-1940) is widely considered the poet laureate of the Jazz Age and one of the great American authors of the 20th century. He became an instant literary sensation with his first novel, This Side of Paradise, published in 1920. His reputation as the voice of his generation was solidified with his succeeding novels, The Beautiful and Damned (1922), The Great Gatsby (1925) and Tender is the Night (1934). In addition, Fitzgerald was a master of the short story, publishing more than 150 in his short lifetime. In financial straits due to a lifetime of alcoholism and the declining popularity of his works, Fitzgerald secured a Hollywood contract to work on screenplays, including writing some unused dialogue for \"Gone With the Wind.\" His best work during this time was a series of short stories collected as \"The Pat Hobby Stories,\" in which he satirized the Hollywood hack writer. At the time of his death from a heart attack at age 44, he was working on his final novel, which was edited by his close friend, the literary critic by Edmund Wilson, and published posthumously as The Last Tycoon.', '2021', 'Fiction & Novels', '36.jpg', '2024-10-25 03:50:58', NULL, NULL, 1),
(37, '978-1292410654', 'Options, Futures, and Other Derivatives', 'John Hull', 'Build essential foundations around the derivatives market for your future career in finance with the definitive guide on the subject.\nOptions, Futures, and Other Derivatives, Global Edition, 11th edition by John Hull, is an industry-leading text and consistent best-seller known as \'The Bible\' to Business and Economics professionals.\n\nIdeal for students studying Business, Economics, and Financial Engineering and Mathematics, this edition gives you a modern look at the derivatives market by incorporating the industry\'s hottest topics, such as securitisation and credit crisis, bridging the gap between theory and practice.\n\nWritten with the knowledge of how Maths can be a key challenge for this course, the text adopts a simple language that makes learning approachable, providing a clear explanation of ideas throughout the text.\n\nThe latest edition covers the most recent regulations and trends, including the Black-Scholes-Merton formulas, overnight indexed swaps, and the valuation of commodity derivatives.\n\nKey features include:\n\nTables, charts, examples, and market data discussions, reflecting current market conditions.\nA delicate balance between theory and practice with the use of mathematics, adding numerical examples for added clarity.\nUseful practice-focused resources to help students overcome learning obstacles.\nEnd-of-chapter problems reflecting contemporary key ideas to support your understanding of the topics based on the new reference rates.\nWhether you need an introductory guide to derivatives to support your existing knowledge in algebra and probability distributions, or useful study content to advance your understanding of stochastic processes, this must-have textbook will support your learning and understanding from theory to practice.\n\n', 'John Hull is the Maple Financial Professor of Derivatives and Risk Management at the Joseph L. Rotman School of Management, University of Toronto (UofT). In 2016, he was awarded the title of University Professor (an honour granted to only 2% of faculty at UofT). He has acted as a consultant to many financial institutions around the world and has won many teaching awards, including UofT\'s prestigious Northrop Frye Award.\n\nHe is an internationally recognised authority on Derivatives and Risk Management and has many publications in this area. His work has an applied focus, with his research and teaching activities including risk management, regulation and machine learning, as well as derivatives. He is co-director of Rotman\'s Master in Finance and Master in Financial Risk Management Programs.', '2021', 'Business & Finance', '37.jpg', '2024-10-26 17:25:29', 'options_futures_and_derivatives.pdf', NULL, 0),
(38, '978-1634624091', 'AI for Data Science: Artificial Intelligence Frameworks and Functionality for Deep Learning, Optimization, and Beyond', 'Dr Zacharias Voulgaris Ph.D., Yunus Emrah Bulut', 'Master the approaches and principles of Artificial Intelligence (AI) algorithms, and apply them to Data Science projects with Python and Julia code.\n\nAspiring and practicing Data Science and AI professionals, along with Python and Julia programmers, will practice numerous AI algorithms and develop a more holistic understanding of the field of AI, and will learn when to use each framework to tackle projects in our increasingly complex world.\n\nThe first two chapters introduce the field, with Chapter 1 surveying Deep Learning models and Chapter 2 providing an overview of algorithms beyond Deep Learning, including Optimization, Fuzzy Logic, and Artificial Creativity.\n\nThe next chapters focus on AI frameworks; they contain data and Python and Julia code in a provided Docker, so you can practice. Chapter 3 covers Apache\'s MXNet, Chapter 4 covers TensorFlow, and Chapter 5 investigates Keras. After covering these Deep Learning frameworks, we explore a series of optimization frameworks, with Chapter 6 covering Particle Swarm Optimization (PSO), Chapter 7 on Genetic Algorithms (GAs), and Chapter 8 discussing Simulated Annealing (SA).\n\nChapter 9 begins our exploration of advanced AI methods, by covering Convolutional Neural Networks (CNNs) and Recurrent Neural Networks (RNNs). Chapter 10 discusses optimization ensembles and how they can add value to the Data Science pipeline.\n\nChapter 11 contains several alternative AI frameworks including Extreme Learning Machines (ELMs), Capsule Networks (CapsNets), and Fuzzy Inference Systems (FIS).\n\nChapter 12 covers other considerations complementary to the AI topics covered, including Big Data concepts, Data Science specialization areas, and useful data resources to experiment on.\n\nA comprehensive glossary is included, as well as a series of appendices covering Transfer Learning, Reinforcement Learning, Autoencoder Systems, and Generative Adversarial Networks. There is also an appendix on the business aspects of AI in data science projects, and an appendix on how to use the Docker image to access the book\'s data and code.\n\nThe field of AI is vast, and can be overwhelming for the newcomer to approach. This book will arm you with a solid understanding of the field, plus inspire you to explore further.', 'Dr. Zacharias Voulgaris was born in Athens, Greece. He studied Production Engineering and Management at the Technical University of Crete, shifted to Computer Science through a Masters in Information Systems & Technology, and then to Data Science through a PhD on machine learning. He has worked at Georgia Tech as a Research Fellow, at an e-marketing startup in Cyprus as an SEO manager, and as a Data Scientist in both Elavon (GA) and G2 Web Services (WA). He also was a Program Manager at Microsoft, on a data analytics pipeline for Bing. Zacharias has authored several books on Data Science, mentors aspiring data scientists through Thinkful, and maintains a Data Science / AI blog.\n\nYunus Emrah Bulut was born in Amasya, Turkey. After he studied Computer Science in Bilkent University, he has worked as a computer scientist at several corporations including Turkeys biggest telecom operator and the Central Bank of Turkey. After he completed his Master of Science degree at the Economics department of the Middle East Technical University (METU), he worked several years in the research department of the Central Bank of Turkey as a research economist. More recently, he has started to work as a Data Science consultant for companies in Turkey and USA. He is also a Data Science instructor at Datajarlabs and Data Science mentor in Thinkful.', '2018', 'Computer Science & Technology', '38.jpg', '2024-10-26 17:25:29', 'ai_for_data_science.pdf', NULL, 0),
(39, '978-0987122827', 'Quant Job Interview Questions and Answers', 'Mark Joshi, Nicholas Denson, Andrew Downes', 'The quant job market has never been tougher. Extensive preparation is essential. Expanding on the successful first edition, this second edition has been updated to reflect the latest questions asked. It now provides over 300 interview questions taken from actual interviews in the City and Wall Street. Each question comes with a full detailed solution, discussion of what the interviewer is seeking and possible follow-up questions. Topics covered include option pricing, probability, mathematics, numerical algorithms and C++, as well as a discussion of the interview process and the non-technical interview. All three authors have worked as quants and they have done many interviews from both sides of the desk. Mark Joshi has written many papers and books including the very successful introductory textbook, \"The Concepts and Practice of Mathematical Finance.\"', '', '2013', 'Business & Finance', '39.jpg', '2024-10-26 17:25:29', 'quant_job_interview_qa.pdf', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `book_availability`
--

CREATE TABLE `book_availability` (
  `availability_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `available_copies` int(11) DEFAULT 0,
  `shelf` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_availability`
--

INSERT INTO `book_availability` (`availability_id`, `book_id`, `branch_id`, `available_copies`, `shelf`) VALUES
(1, 1, 5, 2, 'AB F1-1'),
(2, 1, 6, 2, 'XY F2-2'),
(3, 2, 5, 4, 'UV F3-5'),
(4, 2, 6, 4, 'CD F1-4'),
(5, 3, 5, 2, 'EF F2-3'),
(6, 3, 6, 1, 'GH F2-6'),
(7, 4, 5, 5, 'IJ F3-1'),
(8, 4, 6, 4, 'KL F1-2'),
(9, 4, 8, 1, 'MN F2-4'),
(10, 4, 10, 3, 'OP F3-3'),
(11, 5, 6, 3, 'QR F1-5'),
(12, 5, 8, 3, 'ST F2-6'),
(13, 5, 10, 1, 'UV F1-4'),
(14, 6, 5, 3, 'WX F2-2'),
(15, 6, 6, 1, 'YZ F3-1'),
(16, 6, 8, 1, 'AB F1-3'),
(17, 7, 5, 1, 'CD F1-6'),
(18, 7, 8, 3, 'EF F2-1'),
(19, 7, 10, 3, 'GH F3-3'),
(20, 8, 5, 3, 'IJ F2-5'),
(21, 8, 6, 4, 'KL F3-6'),
(22, 8, 10, 5, 'MN F1-1'),
(23, 9, 5, 1, 'OP F2-3'),
(24, 9, 6, 3, 'QR F1-2'),
(25, 9, 8, 3, 'ST F3-4'),
(26, 10, 5, 3, 'UV F2-5'),
(27, 10, 6, 3, 'WX F1-3'),
(28, 10, 8, 3, 'YZ F3-2'),
(29, 11, 5, 3, 'AB F1-5'),
(30, 11, 6, 2, 'CD F2-1'),
(31, 12, 5, 4, 'EF F3-4'),
(32, 12, 6, 1, 'GH F1-6'),
(33, 13, 5, 3, 'IJ F2-2'),
(34, 13, 6, 4, 'KL F1-4'),
(35, 14, 5, 2, 'MN F3-5'),
(36, 14, 6, 1, 'OP F2-6'),
(37, 15, 5, 4, 'QR F1-3'),
(38, 15, 6, 1, 'ST F2-4'),
(39, 15, 10, 1, 'UV F1-5'),
(40, 16, 4, 4, 'WX F2-1'),
(41, 16, 10, 1, 'YZ F3-6'),
(42, 17, 4, 2, 'AB F3-2'),
(43, 17, 10, 3, 'CD F1-2'),
(44, 18, 4, 3, 'EF F1-4'),
(45, 19, 2, 2, 'GH F3-3'),
(46, 19, 7, 3, 'IJ F2-5'),
(47, 19, 11, 3, 'KL F1-1'),
(48, 20, 2, 5, 'MN F2-3'),
(49, 20, 7, 3, 'OP F3-4'),
(50, 20, 11, 3, 'QR F2-6'),
(51, 21, 2, 5, 'ST F1-5'),
(52, 21, 11, 4, 'UV F1-3'),
(53, 22, 5, 4, 'WX F2-4'),
(54, 22, 8, 2, 'YZ F3-1'),
(55, 23, 5, 2, 'AB F2-6'),
(56, 23, 8, 3, 'CD F1-6'),
(57, 24, 8, 1, 'EF F3-2'),
(58, 25, 3, 3, 'GH F2-1'),
(59, 25, 6, 1, 'IJ F1-5'),
(60, 26, 3, 2, 'KL F3-4'),
(61, 26, 9, 3, 'MN F2-3'),
(62, 27, 3, 4, 'OP F1-2'),
(63, 27, 9, 4, 'QR F1-5'),
(64, 28, 1, 2, 'ST F3-6'),
(65, 28, 12, 5, 'UV F2-2'),
(66, 29, 1, 1, 'WX F2-5'),
(67, 29, 12, 5, 'YZ F3-3'),
(68, 30, 1, 4, 'AB F1-4'),
(69, 30, 12, 4, 'CD F3-1'),
(70, 31, 5, 1, 'EF F2-4'),
(71, 31, 6, 4, 'GH F1-3'),
(72, 31, 10, 1, 'IJ F2-6'),
(73, 32, 5, 3, 'KL F1-5'),
(74, 32, 6, 3, 'OP F3-3'),
(75, 33, 5, 4, 'QR F1-5'),
(76, 34, 4, 4, 'ST F2-6'),
(77, 34, 9, 4, 'UV F1-4'),
(78, 34, 10, 4, 'WX F2-2'),
(79, 35, 3, 1, 'YZ F3-1'),
(80, 35, 4, 2, 'AB F1-3'),
(81, 36, 3, 5, 'CD F1-6'),
(82, 36, 6, 1, 'EF F2-1'),
(83, 36, 9, 4, 'ST F2-4');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `university_id` varchar(10) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `university_id`, `branch_name`, `address`, `created_at`) VALUES
(1, 'NTU', 'Art, Design & Media Library', 'ART-01-03', '2024-10-24 18:53:27'),
(2, 'NTU', 'Business Library', 'N2-B2b-07', '2024-10-24 18:53:27'),
(3, 'NTU', 'Communication & Information Library', 'CS-01-18', '2024-10-24 18:53:27'),
(4, 'NTU', 'Humanities & Social Sciences Library', 'S4-B3C-05', '2024-10-24 18:53:27'),
(5, 'NTU', 'Lee Wee Nam Library', 'NS3-03-01', '2024-10-24 18:53:27'),
(6, 'NUS', 'Central Library', '12 Kent Ridge Crescent', '2024-10-24 18:53:27'),
(7, 'NUS', 'Hon Sui Sen Memorial Library', '1 Hon Sui Sen Drive', '2024-10-24 18:53:27'),
(8, 'NUS', 'Medicine+Science Library', '11 Lower Kent Ridge Road', '2024-10-24 18:53:27'),
(9, 'SMU', 'Bridwell Library', '6005 Bishop Blvd Dalla', '2024-10-24 18:53:27'),
(10, 'SMU', 'Fondren Library', '6414 Robert S. Hyer Lane', '2024-10-24 18:53:27'),
(11, 'SMU', 'Duda Family Business Library', '6214 Bishop Boulevard', '2024-10-24 18:53:27'),
(12, 'SMU', 'Hamon Arts Library', '6100 Hillcrest Avenue', '2024-10-24 18:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `category_preference`
--

CREATE TABLE `category_preference` (
  `pref_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_preference`
--

INSERT INTO `category_preference` (`pref_id`, `user_id`, `category`) VALUES
(1, 1, 'Mathematics & Statistics'),
(2, 1, 'Natural Sciences'),
(3, 1, 'Computer Science & Technology'),
(4, 2, 'Computer Science & Technology'),
(5, 2, 'Humanities & Social Science'),
(6, 2, 'Business & Finance');

-- --------------------------------------------------------

--
-- Table structure for table `digital_resources`
--

CREATE TABLE `digital_resources` (
  `resource_id` int(11) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `about_author` text DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `type` enum('ebook','audiobook') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `digital_resources`
--

INSERT INTO `digital_resources` (`resource_id`, `isbn`, `title`, `author`, `description`, `about_author`, `publication_year`, `category`, `cover_path`, `file_path`, `type`, `created_at`) VALUES
(1, '978-1292410654', 'Options, Futures, and Other Derivatives', 'John Hull', 'Build essential foundations around the derivatives market for your future career in finance with the definitive guide on the subject.\nOptions, Futures, and Other Derivatives, Global Edition, 11th edition by John Hull, is an industry-leading text and consistent best-seller known as \'The Bible\' to Business and Economics professionals.\n\nIdeal for students studying Business, Economics, and Financial Engineering and Mathematics, this edition gives you a modern look at the derivatives market by incorporating the industry\'s hottest topics, such as securitisation and credit crisis, bridging the gap between theory and practice.\n\nWritten with the knowledge of how Maths can be a key challenge for this course, the text adopts a simple language that makes learning approachable, providing a clear explanation of ideas throughout the text.\n\nThe latest edition covers the most recent regulations and trends, including the Black-Scholes-Merton formulas, overnight indexed swaps, and the valuation of commodity derivatives.\n\nKey features include:\n\nTables, charts, examples, and market data discussions, reflecting current market conditions.\nA delicate balance between theory and practice with the use of mathematics, adding numerical examples for added clarity.\nUseful practice-focused resources to help students overcome learning obstacles.\nEnd-of-chapter problems reflecting contemporary key ideas to support your understanding of the topics based on the new reference rates.\nWhether you need an introductory guide to derivatives to support your existing knowledge in algebra and probability distributions, or useful study content to advance your understanding of stochastic processes, this must-have textbook will support your learning and understanding from theory to practice.\n\n', 'John Hull is the Maple Financial Professor of Derivatives and Risk Management at the Joseph L. Rotman School of Management, University of Toronto (UofT). In 2016, he was awarded the title of University Professor (an honour granted to only 2% of faculty at UofT). He has acted as a consultant to many financial institutions around the world and has won many teaching awards, including UofT\'s prestigious Northrop Frye Award.\n\nHe is an internationally recognised authority on Derivatives and Risk Management and has many publications in this area. His work has an applied focus, with his research and teaching activities including risk management, regulation and machine learning, as well as derivatives. He is co-director of Rotman\'s Master in Finance and Master in Financial Risk Management Programs.', '2021', 'Business & Finance', '37.jpg', 'options_futures_and_derivatives.pdf', 'ebook', '2024-10-25 06:42:24'),
(2, '978-1634624091', 'AI for Data Science: Artificial Intelligence Frameworks and Functionality for Deep Learning, Optimization, and Beyond', 'Dr Zacharias Voulgaris Ph.D., Yunus Emrah Bulut', 'Master the approaches and principles of Artificial Intelligence (AI) algorithms, and apply them to Data Science projects with Python and Julia code.\n\nAspiring and practicing Data Science and AI professionals, along with Python and Julia programmers, will practice numerous AI algorithms and develop a more holistic understanding of the field of AI, and will learn when to use each framework to tackle projects in our increasingly complex world.\n\nThe first two chapters introduce the field, with Chapter 1 surveying Deep Learning models and Chapter 2 providing an overview of algorithms beyond Deep Learning, including Optimization, Fuzzy Logic, and Artificial Creativity.\n\nThe next chapters focus on AI frameworks; they contain data and Python and Julia code in a provided Docker, so you can practice. Chapter 3 covers Apache\'s MXNet, Chapter 4 covers TensorFlow, and Chapter 5 investigates Keras. After covering these Deep Learning frameworks, we explore a series of optimization frameworks, with Chapter 6 covering Particle Swarm Optimization (PSO), Chapter 7 on Genetic Algorithms (GAs), and Chapter 8 discussing Simulated Annealing (SA).\n\nChapter 9 begins our exploration of advanced AI methods, by covering Convolutional Neural Networks (CNNs) and Recurrent Neural Networks (RNNs). Chapter 10 discusses optimization ensembles and how they can add value to the Data Science pipeline.\n\nChapter 11 contains several alternative AI frameworks including Extreme Learning Machines (ELMs), Capsule Networks (CapsNets), and Fuzzy Inference Systems (FIS).\n\nChapter 12 covers other considerations complementary to the AI topics covered, including Big Data concepts, Data Science specialization areas, and useful data resources to experiment on.\n\nA comprehensive glossary is included, as well as a series of appendices covering Transfer Learning, Reinforcement Learning, Autoencoder Systems, and Generative Adversarial Networks. There is also an appendix on the business aspects of AI in data science projects, and an appendix on how to use the Docker image to access the book\'s data and code.\n\nThe field of AI is vast, and can be overwhelming for the newcomer to approach. This book will arm you with a solid understanding of the field, plus inspire you to explore further.', 'Dr. Zacharias Voulgaris was born in Athens, Greece. He studied Production Engineering and Management at the Technical University of Crete, shifted to Computer Science through a Masters in Information Systems & Technology, and then to Data Science through a PhD on machine learning. He has worked at Georgia Tech as a Research Fellow, at an e-marketing startup in Cyprus as an SEO manager, and as a Data Scientist in both Elavon (GA) and G2 Web Services (WA). He also was a Program Manager at Microsoft, on a data analytics pipeline for Bing. Zacharias has authored several books on Data Science, mentors aspiring data scientists through Thinkful, and maintains a Data Science / AI blog.\n\nYunus Emrah Bulut was born in Amasya, Turkey. After he studied Computer Science in Bilkent University, he has worked as a computer scientist at several corporations including Turkeys biggest telecom operator and the Central Bank of Turkey. After he completed his Master of Science degree at the Economics department of the Middle East Technical University (METU), he worked several years in the research department of the Central Bank of Turkey as a research economist. More recently, he has started to work as a Data Science consultant for companies in Turkey and USA. He is also a Data Science instructor at Datajarlabs and Data Science mentor in Thinkful.', '2018', 'Computer Science & Technology', '38.jpg', 'ai_for_data_science.pdf', 'ebook', '2024-10-25 06:42:24'),
(3, '978-0987122827', 'Quant Job Interview Questions and Answers', 'Mark Joshi, Nicholas Denson, Andrew Downes', 'The quant job market has never been tougher. Extensive preparation is essential. Expanding on the successful first edition, this second edition has been updated to reflect the latest questions asked. It now provides over 300 interview questions taken from actual interviews in the City and Wall Street. Each question comes with a full detailed solution, discussion of what the interviewer is seeking and possible follow-up questions. Topics covered include option pricing, probability, mathematics, numerical algorithms and C++, as well as a discussion of the interview process and the non-technical interview. All three authors have worked as quants and they have done many interviews from both sides of the desk. Mark Joshi has written many papers and books including the very successful introductory textbook, \"The Concepts and Practice of Mathematical Finance.\"', '', '2013', 'Business & Finance', '39.jpg', 'quant_job_interview_qa.pdf', 'ebook', '2024-10-25 06:42:24'),
(35, '978-0141330167', 'Pride and Prejudice', 'Jane Austen', 'When two rich young gentlemen move to town, they don\'t go unnoticed - especially when Mrs Bennett vows to have one of her five daughters marry into their fortunes. But love, as Jane and Elizabeth Bennett soon discover, is rarely straightforward, and often surprising. It\'s only a matter of time until their own small worlds are turned upside down and they discover that first impressions can be the most misleading of all.\nWith a behind-the-scenes journey, including an author profile, a guide to who\'s who, activities and more.\nLightly abridged for Puffin Classics.', 'Jane Austen, the daughter of a clergyman, was born in Hampshire in 1775, and later lived in Bath and the village of Chawton. As a child and teenager, she wrote brilliantly witty stories for her family\'s amusement, as well as a novella, Lady Susan. Her first published novel was Sense and Sensibility, which appeared in 1811 and was soon followed by Pride and Prejudice, Mansfield Park and Emma. Austen died in 1817, and Persuasion and Northanger Abbey were published posthumously in 1818.', '2018', 'Fiction & Novels', '35.jpg', 'pride_and_prejudice_01_austen.mp3', 'audiobook', '2024-10-25 06:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `about_author` text DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `available_copies` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favourite_books`
--

CREATE TABLE `favourite_books` (
  `fav_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourite_books`
--

INSERT INTO `favourite_books` (`fav_id`, `user_id`, `book_id`) VALUES
(3, 1, 3),
(4, 2, 4),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(32, 1, 1),
(41, 1, 8),
(42, 1, 2),
(45, 1, 37),
(46, 1, 39),
(47, 1, 38);

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `paid` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `librarians`
--

CREATE TABLE `librarians` (
  `librarian_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `university_id` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarians`
--

INSERT INTO `librarians` (`librarian_id`, `name`, `email`, `password`, `university_id`, `created_at`) VALUES
(1, 'NTU Librarian 1', 'ntulib1@e.ntu.edu.sg', 'f2b0ce4019fdabe180ffadc5ebf8d74b', 'NTU', '2024-10-24 18:26:36'),
(2, 'NUS Librarian 1', 'nuslib1@e.nus.edu.sg', 'ffc04b4ec676dd123fcc0b0d5c3427aa', 'NUS', '2024-10-24 18:26:36'),
(3, 'SMU Librarian 1', 'smulib1@e.smu.edu.sg', 'ee1964c2e52569373f2c67d5b2a844b3', 'SMU', '2024-10-24 18:26:36'),
(4, 'SUTD Librarian 1', 'sutdlib1@e.sutd.edu.sg', 'a4d110bcb2c1ff9486c9cc1615a83c4c', 'SUTD', '2024-10-24 18:26:36'),
(5, 'SIT Librarian 1', 'sitlib1@e.sit.edu.sg', 'a0863d6ac753ab1fbf5222129870b5ee', 'SIT', '2024-10-24 18:26:36'),
(6, 'SUSS Librarian 1', 'susslib1@e.suss.edu.sg', '0301f9825d0ffabd5fef69ecd06fb3e2', 'SUSS', '2024-10-24 18:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `loan_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('active','returned','overdue') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `user_id`, `book_id`, `branch_id`, `loan_date`, `return_date`, `due_date`, `status`) VALUES
(3, 1, 1, 5, '2024-11-01', NULL, '2024-11-02', 'active'),
(4, 1, 5, 8, '2024-11-01', NULL, '2024-11-02', 'active'),
(5, 1, 2, 5, '2024-11-01', NULL, '2024-11-02', 'active'),
(6, 1, 11, 5, '2024-11-01', NULL, '2024-11-15', 'active'),
(7, 1, 19, 7, '2024-11-01', NULL, '2024-11-14', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `reservation_date` date DEFAULT NULL,
  `status` enum('pending','fulfilled','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `book_id`, `branch_id`, `reservation_date`, `status`) VALUES
(10, 1, 19, 12, '2024-10-31', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `university_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`university_id`, `name`, `address`, `created_at`) VALUES
('NTU', 'Nanyang Technological University', '50 Nanyang Ave, Singapore 639798', '2024-10-24 18:06:54'),
('NUS', 'National University of Singapore', '21 Lower Kent Ridge Rd, Singapore 119077', '2024-10-24 18:06:54'),
('SIT', 'Singapore Institute of Technology', '10 Dover Dr, Singapore 138683', '2024-10-24 18:06:54'),
('SMU', 'Singapore Management University', '81 Victoria St, Singapore 188065', '2024-10-24 18:06:54'),
('SUSS', 'Singapore University of Social Sciences', '463 Clementi Rd, Singapore 599494', '2024-10-24 18:06:54'),
('SUTD', 'Singapore University of Technology and Design', '8 Somapah Rd, Singapore 487372', '2024-10-24 18:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `university_id` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `university_id`, `created_at`) VALUES
(1, 'Pham Thuy Linh', 'thuylinh001@e.ntu.edu.sg', '12be439712901640aa0a9e271b65d9fc', '12345678', 'NTU', '2024-10-24 18:16:27'),
(2, 'Tran Huu Nghia', 'huunghia002@e.ntu.edu.sg', 'bbe3b53eb5210306e9dcfda8be238e9a', '87654321', 'NTU', '2024-10-24 18:16:27'),
(3, 'Test User', 'testuser1@e.ntu.edu.sg', '2c4bed4d73c86619fcf1627fb72011fa', '11111111', 'NTU', '2024-10-25 15:22:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `idx_books_isbn` (`isbn`);
ALTER TABLE `books` ADD FULLTEXT KEY `idx_books_search` (`title`,`author`,`description`,`category`);

--
-- Indexes for table `book_availability`
--
ALTER TABLE `book_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `category_preference`
--
ALTER TABLE `category_preference`
  ADD PRIMARY KEY (`pref_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `digital_resources`
--
ALTER TABLE `digital_resources`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `favourite_books`
--
ALTER TABLE `favourite_books`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `librarians`
--
ALTER TABLE `librarians`
  ADD PRIMARY KEY (`librarian_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`university_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `university_id` (`university_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `book_availability`
--
ALTER TABLE `book_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `category_preference`
--
ALTER TABLE `category_preference`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `digital_resources`
--
ALTER TABLE `digital_resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favourite_books`
--
ALTER TABLE `favourite_books`
  MODIFY `fav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `librarians`
--
ALTER TABLE `librarians`
  MODIFY `librarian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_availability`
--
ALTER TABLE `book_availability`
  ADD CONSTRAINT `book_availability_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_availability_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE;

--
-- Constraints for table `category_preference`
--
ALTER TABLE `category_preference`
  ADD CONSTRAINT `category_preference_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;

--
-- Constraints for table `favourite_books`
--
ALTER TABLE `favourite_books`
  ADD CONSTRAINT `favourite_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favourite_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `librarians`
--
ALTER TABLE `librarians`
  ADD CONSTRAINT `librarians_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`university_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
