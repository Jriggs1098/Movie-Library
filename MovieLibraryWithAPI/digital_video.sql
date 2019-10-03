-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2019 at 03:30 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digitalvideo_db`
--
CREATE DATABASE IF NOT EXISTS `digitalvideo_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `digitalvideo_db`;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(1) NOT NULL,
  `genre` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre`) VALUES
(1, 'Action'),
(2, 'Comedy'),
(3, 'Horror'),
(4, 'Sci-Fi'),
(5, 'Drama'),
(6, 'Mystery');

-- --------------------------------------------------------

--
-- Table structure for table `libraries`
--

CREATE TABLE `libraries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `episode_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `libraries`
--

INSERT INTO `libraries` (`id`, `user_id`, `movie_id`, `episode_id`) VALUES
(89, 1, 2, NULL),
(90, 1, 1, NULL),
(91, 1, 6, NULL),
(92, 1, NULL, 1),
(94, 1, NULL, 33),
(97, 4, 10, NULL),
(98, 4, 2, NULL),
(100, 4, NULL, 207),
(101, 4, 1, NULL),
(104, 3, NULL, 3),
(106, 3, 9, NULL),
(108, 3, NULL, 178),
(109, 4, 3, NULL),
(113, 1, 13, NULL),
(114, 1, 11, NULL),
(115, 1, NULL, 403);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`) VALUES
(2, 'The Dark Knight', 2008),
(3, 'Blade Runner 2049', 2017),
(4, 'Captain Marvel', 2019),
(5, 'Pet Sematary', 2019),
(6, 'Room', 2015),
(10, 'Interstellar', 2014),
(11, 'Arrival', 2016),
(12, 'Avengers Endgame', 2019),
(20, 'Star Wars: The Force Awakens', 2015),
(22, 'The Avengers', 2012);

-- --------------------------------------------------------

--
-- Table structure for table `tv_episodes`
--

CREATE TABLE `tv_episodes` (
  `season_number` int(11) NOT NULL,
  `episode_number` int(2) NOT NULL,
  `title` varchar(40) NOT NULL,
  `id` int(11) NOT NULL,
  `series_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tv_episodes`
--

INSERT INTO `tv_episodes` (`season_number`, `episode_number`, `title`, `id`, `series_id`) VALUES
(1, 1, 'Vanish', 1, 2),
(1, 2, 'Dirt', 2, 2),
(1, 3, 'Fix', 3, 2),
(1, 4, 'Ripe', 4, 2),
(1, 5, 'Closer', 5, 2),
(1, 6, 'Cherry', 6, 2),
(1, 7, 'Falling', 7, 2),
(1, 8, 'Milk', 8, 2),
(1, 1, 'Pilot', 11, 1),
(1, 2, 'Diversity Day', 12, 1),
(1, 3, 'Health Care', 13, 1),
(1, 4, 'The Alliance', 14, 1),
(1, 5, 'Basketball', 15, 1),
(1, 6, 'Hot Girl', 16, 1),
(2, 1, 'The Dundies', 17, 1),
(2, 2, 'Sexual Harassment', 18, 1),
(2, 3, 'Office Olympics', 19, 1),
(2, 4, 'The Fire', 20, 1),
(2, 5, 'Halloween', 21, 1),
(2, 6, 'The Fight', 22, 1),
(2, 7, 'The Client', 23, 1),
(2, 8, 'Performance Review', 24, 1),
(2, 9, 'E-Mail Surveillance', 25, 1),
(2, 10, 'Christmas Party', 26, 1),
(2, 11, 'Booze Cruise', 27, 1),
(2, 12, 'The Injury', 28, 1),
(2, 13, 'The Secret', 29, 1),
(2, 14, 'The Carpet', 30, 1),
(2, 15, 'Boys and Girls', 31, 1),
(2, 16, 'Valentine\\\'s Day', 32, 1),
(2, 17, 'Dwight\\\'s Speech', 33, 1),
(2, 18, 'Take Your Daughter to Work Day', 34, 1),
(2, 19, 'Michael\\\'s Birthday', 35, 1),
(2, 20, 'Drug Testing', 36, 1),
(2, 21, 'Conflict Resolution', 37, 1),
(2, 22, 'Casino Night', 38, 1),
(3, 1, 'Gay Witch Hunt', 39, 1),
(3, 2, 'The Convention', 40, 1),
(3, 3, 'The Coup', 41, 1),
(3, 4, 'Grief Counseling', 42, 1),
(3, 5, 'Initiation', 43, 1),
(3, 6, 'Diwali', 44, 1),
(3, 7, 'Branch Closing', 45, 1),
(3, 8, 'The Merger', 46, 1),
(3, 9, 'The Convict', 47, 1),
(3, 10, 'A Benihana Christmas', 48, 1),
(3, 11, 'Back from Vacation', 49, 1),
(3, 12, 'Traveling Salesmen', 50, 1),
(3, 13, 'The Return', 51, 1),
(3, 14, 'Ben Franklin', 52, 1),
(3, 15, 'Phyllis\\\' Wedding', 53, 1),
(3, 16, 'Business School', 54, 1),
(3, 17, 'Cocktails', 55, 1),
(3, 18, 'The Negotiation', 56, 1),
(3, 19, 'Safety Training', 57, 1),
(3, 20, 'Product Recall', 58, 1),
(3, 21, 'Women\\\'s Appreciatio', 59, 1),
(3, 22, 'Beach Games', 60, 1),
(3, 23, 'The Job', 61, 1),
(4, 1, 'Fun Run', 62, 1),
(4, 2, 'Dunder Mifflin Infin', 63, 1),
(4, 3, 'Launch Party', 64, 1),
(4, 4, 'Money', 65, 1),
(4, 5, 'Local Ad', 66, 1),
(4, 6, 'Branch Wars', 67, 1),
(4, 7, 'Survivor Man', 68, 1),
(4, 8, 'The Deposition', 69, 1),
(4, 9, 'Dinner Party', 70, 1),
(4, 10, 'Chair Model', 71, 1),
(4, 11, 'Night Out', 72, 1),
(4, 12, 'Did I Stutter?', 73, 1),
(4, 13, 'Job Fair', 74, 1),
(4, 14, 'Goodbye, Toby', 75, 1),
(5, 1, 'Weight Loss', 76, 1),
(5, 2, 'Business Ethics', 77, 1),
(5, 3, 'Baby Shower', 78, 1),
(5, 4, 'Crime Aid', 79, 1),
(5, 5, 'Employee Transfer', 80, 1),
(5, 6, 'Customer Survey', 81, 1),
(5, 7, 'Business Trip', 82, 1),
(5, 8, 'Frame Toby', 83, 1),
(5, 9, 'The Surplus', 84, 1),
(5, 10, 'Moroccan Christmas', 85, 1),
(5, 11, 'The Duel', 86, 1),
(5, 12, 'Prince Family Paper', 87, 1),
(5, 13, 'Stress Relief', 88, 1),
(5, 14, 'Lecture Circuit: Par', 89, 1),
(5, 15, 'Lecture Circuit: Par', 90, 1),
(5, 16, 'Blood Drive', 91, 1),
(5, 17, 'Golden Ticket', 92, 1),
(5, 18, 'New Boss', 93, 1),
(5, 19, 'Two Weeks', 94, 1),
(5, 20, 'Dream Team', 95, 1),
(5, 21, 'Michael Scott Paper ', 96, 1),
(5, 22, 'Heavy Competition', 97, 1),
(5, 23, 'Broke', 98, 1),
(5, 24, 'Casual Friday', 99, 1),
(5, 25, 'Cafe Disco', 100, 1),
(5, 26, 'Company Picnic', 101, 1),
(6, 1, 'Gossip', 102, 1),
(6, 2, 'The Meeting', 103, 1),
(6, 3, 'The Promotion', 104, 1),
(6, 4, 'Niagara: Part 1', 105, 1),
(6, 5, 'Niagara Part 2', 106, 1),
(6, 6, 'Mafia', 107, 1),
(6, 7, 'The Lover', 108, 1),
(6, 8, 'Koi Pond', 109, 1),
(6, 9, 'Double Date', 110, 1),
(6, 10, 'Murder', 111, 1),
(6, 11, 'Shareholder Meeting', 112, 1),
(6, 12, 'Scott\\\'s Tots', 113, 1),
(6, 13, 'Secret Santa', 114, 1),
(6, 14, 'The Banker', 115, 1),
(6, 15, 'Sabre', 116, 1),
(6, 16, 'Manager and Salesman', 117, 1),
(6, 17, 'The Delivery: Part 1', 118, 1),
(6, 18, 'The Delivery: Part 2', 119, 1),
(6, 19, 'St. Patrick\\\'s Day', 120, 1),
(6, 20, 'New Leads', 121, 1),
(6, 21, 'Happy Hour', 122, 1),
(6, 22, 'Secretary\\\'s Day', 123, 1),
(6, 23, 'Body Language', 124, 1),
(6, 24, 'The Cover-Up', 125, 1),
(6, 25, 'The Chump', 126, 1),
(6, 26, 'Whistleblower', 127, 1),
(7, 1, 'Nepotism', 128, 1),
(7, 2, 'Counseling', 129, 1),
(7, 3, 'Andy\\\'s Play', 130, 1),
(7, 4, 'Sex Ed', 131, 1),
(7, 5, 'The Sting', 132, 1),
(7, 6, 'Costume Contest', 133, 1),
(7, 7, 'Christening', 134, 1),
(7, 8, 'Viewing Party', 135, 1),
(7, 9, 'WUPHF.com', 136, 1),
(7, 10, 'China', 137, 1),
(7, 11, 'Classy Christmas', 138, 1),
(7, 12, 'Ultimatum', 139, 1),
(7, 13, 'The Seminar', 140, 1),
(7, 14, 'The Search', 141, 1),
(7, 15, 'PDA', 142, 1),
(7, 16, 'Threat Level Midnight', 143, 1),
(7, 17, 'Todd Packer', 144, 1),
(7, 18, 'Garage Sale', 145, 1),
(7, 19, 'Training Day', 146, 1),
(7, 20, 'Michael&#39;s Last Dundies', 147, 1),
(7, 21, 'Goodbye, Michael', 148, 1),
(7, 22, 'The Inner Circle', 149, 1),
(7, 23, 'Dwight K. Schrute, (Acting) Manager', 150, 1),
(7, 24, 'Search Committee', 151, 1),
(8, 1, 'The List', 152, 1),
(8, 2, 'The Incentive', 153, 1),
(8, 3, 'Lotto', 154, 1),
(8, 4, 'Garden Party', 155, 1),
(8, 5, 'Spooked', 156, 1),
(8, 6, 'Doomsday', 157, 1),
(8, 7, 'Pam\\\'s Replacement', 158, 1),
(8, 8, 'Gettysburg', 159, 1),
(8, 9, 'Mrs. California', 160, 1),
(8, 10, 'Christmas Wishes', 161, 1),
(8, 11, 'Trivia', 162, 1),
(8, 12, 'Pool Party', 163, 1),
(8, 13, 'Jury Duty', 164, 1),
(8, 14, 'Special Project', 165, 1),
(8, 15, 'Tallahassee', 166, 1),
(8, 16, 'After Hours', 167, 1),
(8, 17, 'Test the Store', 168, 1),
(8, 18, 'Last Day in Florida', 169, 1),
(8, 19, 'Get the Girl', 170, 1),
(8, 20, 'Welcome Party', 171, 1),
(8, 21, 'Angry Andy', 172, 1),
(8, 22, 'Fundraiser', 173, 1),
(8, 23, 'Turf War', 174, 1),
(8, 24, 'Free Family Portrait', 175, 1),
(9, 1, 'New Guys', 176, 1),
(9, 2, 'Roy\\\'s Wedding', 177, 1),
(9, 3, 'Andy\\\'s Ancestry', 178, 1),
(9, 4, 'Work Bus', 179, 1),
(9, 5, 'Here Comes Treble', 180, 1),
(9, 6, 'The Boat', 181, 1),
(9, 7, 'The Whale', 182, 1),
(9, 8, 'The Target', 183, 1),
(9, 9, 'Dwight Christmas', 184, 1),
(9, 10, 'Lice', 185, 1),
(9, 11, 'Suit Warehouse', 186, 1),
(9, 12, 'Customer Loyalty', 187, 1),
(9, 13, 'Junior Salesman', 188, 1),
(9, 14, 'Vandalism', 189, 1),
(9, 15, 'Couples Discount', 190, 1),
(9, 16, 'Moving On', 191, 1),
(9, 17, 'The Farm', 192, 1),
(9, 18, 'Promos', 193, 1),
(9, 19, 'Stairmageddon', 194, 1),
(9, 20, 'Paper Airplane', 195, 1),
(9, 21, 'Livin\\\' the Dream', 196, 1),
(9, 22, 'A.A.R.M.', 197, 1),
(9, 23, 'Finale', 198, 1),
(1, 2, 'French Connection', 202, 7),
(1, 3, 'Black 22', 203, 7),
(1, 4, 'The Wolf', 204, 7),
(1, 5, 'End of Honor', 205, 7),
(1, 6, 'Sources and Methods', 206, 7),
(1, 7, 'The Boy', 207, 7),
(1, 8, 'Inshallah', 208, 7),
(1, 1, 'Pilot', 209, 7),
(1, 1, 'Pilot', 363, 8),
(1, 2, 'The Big Bran Hypothesis', 364, 8),
(1, 3, 'The Fuzzy Boots Corollary', 365, 8),
(1, 4, 'The Luminous Fish Effect', 366, 8),
(1, 5, 'The Hamburger Postulate', 367, 8),
(1, 6, 'The Middle Earth Paradigm', 368, 8),
(1, 7, 'The Dumpling Paradox', 369, 8),
(1, 8, 'The Grasshopper Experiment', 370, 8),
(1, 9, 'The Cooper-Hofstadter Polarization', 371, 8),
(1, 10, 'The Loobenfeld Decay', 372, 8),
(1, 11, 'The Pancake Batter Anomaly', 373, 8),
(1, 12, 'The Jerusalem Duality', 374, 8),
(1, 13, 'The Bat Jar Conjecture', 375, 8),
(1, 14, 'The Nerdvana Annihilation', 376, 8),
(1, 15, 'The Pork Chop Indeterminacy', 377, 8),
(1, 16, 'The Peanut Reaction', 378, 8),
(1, 17, 'The Tangerine Factor', 379, 8),
(2, 1, 'The Bad Fish Paradigm', 381, 8),
(2, 2, 'The Codpiece Topology', 382, 8),
(2, 3, 'The Barbarian Sublimation', 383, 8),
(2, 4, 'The Griffin Equivalency', 384, 8),
(2, 5, 'The Euclid Alternative', 385, 8),
(2, 6, 'The Cooper-Nowitzki Theorem', 386, 8),
(2, 7, 'The Panty PiÃ±ata Polarization', 387, 8),
(2, 8, 'The Lizard-Spock Expansion', 388, 8),
(2, 9, 'The White Asparagus Triangulation', 389, 8),
(2, 10, 'The Vartabedian Conundrum', 390, 8),
(2, 11, 'The Bath Item Gift Hypothesis', 391, 8),
(2, 12, 'The Killer Robot Instability', 392, 8),
(2, 13, 'The Friendship Algorithm', 393, 8),
(2, 14, 'The Financial Permeability', 394, 8),
(2, 15, 'The Maternal Capacitance', 395, 8),
(2, 16, 'The Cushion Saturation', 396, 8),
(2, 17, 'The Terminator Decoupling', 397, 8),
(2, 18, 'The Work Song Nanocluster', 398, 8),
(2, 19, 'The Dead Hooker Juxtaposition', 399, 8),
(2, 20, 'The Hofstadter Isotope', 400, 8),
(2, 21, 'The Vegas Renormalization', 401, 8),
(2, 22, 'The Classified Materials Turbulence', 402, 8),
(2, 23, 'The Monopolar Expedition', 403, 8),
(3, 1, 'The Electric Can Opener Fluctuation', 404, 8),
(3, 2, 'The Jiminy Conjecture', 405, 8),
(3, 3, 'The Gothowitz Deviation', 406, 8),
(3, 4, 'The Pirate Solution', 407, 8),
(3, 5, 'The Creepy Candy Coating Corollary', 408, 8),
(3, 6, 'The Cornhusker Vortex', 409, 8),
(3, 7, 'The Guitarist Amplification', 410, 8),
(3, 8, 'The Adhesive Duck Deficiency', 411, 8),
(3, 9, 'The Vengeance Formulation', 412, 8),
(3, 10, 'The Gorilla Experiment', 413, 8),
(3, 11, 'The Maternal Congruence', 414, 8),
(3, 12, 'The Psychic Vortex', 415, 8),
(3, 13, 'The Bozeman Reaction', 416, 8),
(3, 14, 'The Einstein Approximation', 417, 8),
(3, 15, 'The Large Hadron Collision', 418, 8),
(3, 16, 'The Excelsior Acquisition', 419, 8),
(3, 17, 'The Precious Fragmentation', 420, 8),
(3, 18, 'The Pants Alternative', 421, 8),
(3, 19, 'The Wheaton Recurrence', 422, 8),
(3, 20, 'The Spaghetti Catalyst', 423, 8),
(3, 21, 'The Plimpton Stimulation', 424, 8),
(3, 22, 'The Staircase Implementation', 425, 8),
(3, 23, 'The Lunar Excitation', 426, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tv_series`
--

CREATE TABLE `tv_series` (
  `id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tv_series`
--

INSERT INTO `tv_series` (`id`, `title`) VALUES
(1, 'The Office'),
(2, 'Sharp Objects'),
(7, 'Tom Clancy\'s Jack Ryan'),
(8, 'The Big Bang Theory');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `firstname`, `lastname`, `username`, `password`) VALUES
(1, 1, 'Jack', 'Riggs', 'admin', 'password'),
(3, 2, 'Jack', 'Riggs', 'guest', 'password'),
(4, 2, 'Guest', 'Number2', 'guest2', '$2y$10$gWn2g4dynl4eZvsILzR7r.nA0RmUQY9Igp0T7mSM6Y7OKJJ6oglqa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `libraries`
--
ALTER TABLE `libraries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_movie` (`user_id`,`movie_id`),
  ADD UNIQUE KEY `idx_user_episode` (`user_id`,`episode_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tv_episodes`
--
ALTER TABLE `tv_episodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tv_series`
--
ALTER TABLE `tv_series`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `libraries`
--
ALTER TABLE `libraries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tv_episodes`
--
ALTER TABLE `tv_episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=427;

--
-- AUTO_INCREMENT for table `tv_series`
--
ALTER TABLE `tv_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
