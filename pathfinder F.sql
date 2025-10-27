-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 10:34 PM
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
-- Database: `pathfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL,
  `ssn` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `bdate` date NOT NULL,
  `tourid` int(11) DEFAULT NULL,
  `hotelid` int(11) DEFAULT NULL,
  `flightid` int(11) DEFAULT NULL,
  `destid` int(11) DEFAULT NULL,
  `visanum` varchar(50) NOT NULL,
  `profilepic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerid`, `ssn`, `username`, `password`, `email`, `gender`, `bdate`, `tourid`, `hotelid`, `flightid`, `destid`, `visanum`, `profilepic`) VALUES
(1, '1234', 'emad', '1234', 'emad@gmail.com', 'male', '1999-02-09', NULL, 4, 8, 4, '4444', 'uploads/682e2ee044331_peanut.png'),
(2, '4321', 'yousef', '1212', 'yousef@gmail.com', 'male', '2015-05-22', NULL, 9, 5, 9, '3333', 'uploads/lionel-messi_imago1019567000h.jpg'),
(26, '1112', 'haytham', '123', 'haytham@gmail.com', 'male', '2025-05-09', NULL, 19, 11, 19, '1212', ''),
(27, '2222', 'ahmad', '12', 'ahmad@gmail.com', 'male', '1999-05-11', NULL, NULL, NULL, NULL, '5555', ''),
(29, '3123', 'sara', '3322', 'sara@gmail.com', 'female', '2025-05-14', NULL, NULL, NULL, NULL, '4664', ''),
(30, '4543', 'kareem', 'kk11', 'kareem@gmail.com', 'male', '2018-06-13', NULL, NULL, NULL, NULL, '1574', ''),
(31, '2227', 'fadi', '147', 'fadi@gmail.com', 'male', '2022-05-15', NULL, NULL, NULL, NULL, '3532', ''),
(32, '7765', 'tariq', '221', 'tariq@gmail.com', 'male', '2025-02-14', NULL, NULL, NULL, NULL, '149', ''),
(34, '5645', 'tasneem', '112', 'tasneem@gmail.com', 'female', '2025-05-10', NULL, 19, 11, 19, '7845', ''),
(36, '1598', 'mohsen', '11', 'mohsen@gmail.com', 'male', '2024-06-12', NULL, NULL, NULL, NULL, '4568', ''),
(55, '1234', 'emad', '1234', 'emad@gmail.com', 'male', '1999-02-09', 7, 7, 4, 7, '4444', ''),
(56, '1234', 'emad', '1234', 'emad@gmail.com', 'male', '1999-02-09', NULL, 8, 3, 8, '4444', ''),
(57, '1234', 'emad', '1234', 'emad@gmail.com', 'male', '1999-02-09', NULL, 6, 7, 6, '4444', ''),
(58, '1112', 'haytham', '123', 'haytham@gmail.com', 'male', '2025-05-09', 4, 4, 1, 4, '1212', ''),
(59, '1112', 'haytham', '123', 'haytham@gmail.com', 'male', '2025-05-09', NULL, 14, 13, 14, '1212', ''),
(60, '4321', 'yousef', '1212', 'yousef@gmail.com', 'male', '2015-05-22', 9, 9, 5, 9, '3333', ''),
(61, '2222', 'ahmad', '12', 'ahmad@gmail.com', 'male', '1999-05-11', 13, 13, 6, 13, '5555', ''),
(62, '2222', 'ahmad', '12', 'ahmad@gmail.com', 'male', '1999-05-11', 8, 8, 3, 8, '5555', ''),
(63, '3123', 'sara', '3322', 'sara@gmail.com', 'female', '2025-05-14', 4, 4, 1, 4, '4664', ''),
(64, '3123', 'sara', '3322', 'sara@gmail.com', 'female', '2025-05-14', 4, 4, 1, 4, '4664', ''),
(65, '7765', 'tariq', '221', 'tariq@gmail.com', 'male', '2025-02-14', 12, 12, 1, 12, '149', ''),
(66, '7765', 'tariq', '221', 'tariq@gmail.com', 'male', '2025-02-14', 5, 5, 4, 5, '149', ''),
(67, '2227', 'fadi', '147', 'fadi@gmail.com', 'male', '2022-05-15', 10, 10, 3, 10, '3532', ''),
(68, '2227', 'fadi', '147', 'fadi@gmail.com', 'male', '2022-05-15', 13, 13, 6, 13, '3532', ''),
(69, '2227', 'fadi', '147', 'fadi@gmail.com', 'male', '2022-05-15', 9, 9, 5, 9, '3532', ''),
(70, '4632', 'morad', 'mm', 'morad@gmail.com', 'male', '2017-02-01', NULL, NULL, NULL, NULL, '5875', ''),
(71, '4543', 'kareem', 'kk11', 'kareem@gmail.com', 'male', '2018-06-13', 11, 11, 3, 11, '1574', ''),
(72, '4543', 'kareem', 'kk11', 'kareem@gmail.com', 'male', '2018-06-13', 6, 6, 7, 6, '1574', ''),
(73, '8747', 'alaa', 'aa', 'alaa@gmail.com', 'male', '2012-07-12', NULL, 8, 22, 8, '1687', ''),
(74, '8747', 'alaa', 'aa', 'alaa@gmail.com', 'male', '2012-07-12', 6, 6, 7, 6, '1687', ''),
(75, '4678', 'rajeh', '124', 'rajeh@gmail.com', 'male', '2004-05-21', NULL, 6, 7, 6, '7999', ''),
(76, '4678', 'rajeh', '124', 'rajeh@gmail.com', 'male', '2004-05-21', NULL, 8, 10, 8, '7999', ''),
(77, '1234', 'emad', '1234', 'emad@gmail.com', 'male', '1999-02-09', NULL, 21, 20, 20, '4444', '');

-- --------------------------------------------------------

--
-- Table structure for table `destination`
--

CREATE TABLE `destination` (
  `destid` int(11) NOT NULL,
  `continent` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `destimage` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `destination`
--

INSERT INTO `destination` (`destid`, `continent`, `country`, `city`, `description`, `destimage`) VALUES
(1, 'Africa', 'Egypt', 'Cairo', 'Home to the ancient pyramids and the magnificent Nile River with thousands of years of history.', 'cairoD.webp'),
(2, 'Africa', 'Morocco', 'Marrakech', 'Vibrant city with bustling souks, stunning palaces, and beautiful gardens.', 'marrakechD.jpg'),
(3, 'Africa', 'South Africa', 'Cape Town', 'Coastal city with stunning Table Mountain views and diverse cultural attractions.', 'Cape townD.webp'),
(4, 'Europe', 'France', 'Paris', 'City of lights featuring the iconic Eiffel Tower, Louvre Museum, and charming cafes.', 'PARISd.webp'),
(5, 'Europe', 'Italy', 'Rome', 'Ancient city with the Colosseum, Roman Forum, and Vatican City.', 'DROME.webp'),
(6, 'Europe', 'Spain', 'Barcelona', 'Mediterranean gem with stunning Gaudi architecture and beautiful beaches.', 'barcelona-D.jpg'),
(7, 'Europe', 'Greece', 'Santorini', 'Breathtaking island with white-washed buildings and spectacular Aegean Sea views.', 'santorini-Djpg.jpg'),
(8, 'Asia', 'Japan', 'Tokyo', 'Ultramodern metropolis with ancient temples, incredible food, and vibrant street life.', 'tokyoD.webp'),
(9, 'Asia', 'Thailand', 'Bangkok', 'Bustling city with ornate temples, floating markets, and exciting street food scene.', 'Bangkokd.webp'),
(10, 'Asia', 'India', 'Jaipur', 'The \"Pink City\" of India featuring magnificent palaces and colorful bazaars.', 'Jaipur-d.jpg'),
(11, 'Asia', 'United Arab Emirates', 'Dubai', 'Modern city with the world\'s tallest building and luxurious shopping malls.', 'dubaiD.webp'),
(12, 'North America', 'United States', 'New York', 'The Big Apple featuring iconic landmarks, Central Park, and a diverse cultural scene.', 'newYorkd.webp'),
(13, 'North America', 'Mexico', 'Mexico City', 'Historical capital with ancient ruins, colonial architecture, and vibrant food scene.', 'MexicoCityd.jpg'),
(14, 'North America', 'Canada', 'Vancouver', 'Coastal city surrounded by mountains, offering outdoor activities and urban experiences.', 'vancouver.png'),
(15, 'South America', 'Brazil', 'Rio de Janeiro', 'Famous for its beaches, Carnival, and the iconic Christ the Redeemer statue.', 'Rio de JaneiroDD.PNG'),
(16, 'South America', 'Peru', 'Cusco', 'Ancient Incan capital and gateway to Machu Picchu.', 'Cusco.JPG'),
(17, 'South America', 'Argentina', 'Buenos Aires', 'Vibrant capital known for tango, steak, and European-style architecture.', 'Buenos AiresD.jpg'),
(18, 'Europe', 'Portugal', 'Lisbon', 'Coastal capital with historic neighborhoods, trams, and delicious pastries.', 'lisbondd.jpg'),
(19, 'Asia', 'Singapore', 'Singapore', 'Modern city-state with futuristic gardens, diverse food, and clean streets.', 'singaporedd.jpg'),
(20, 'North America', 'United States', 'San Francisco', 'Iconic city with the Golden Gate Bridge, Victorian houses, and tech innovation.', 'san-francisco.jpg.webp');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flightid` int(11) NOT NULL,
  `airport` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `begin` varchar(50) NOT NULL,
  `destid` int(11) NOT NULL,
  `price` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flightid`, `airport`, `time`, `begin`, `destid`, `price`, `type`, `date`) VALUES
(1, 'JFK International Airport', '09:30 AM', 'New York', 4, '850', 'Economy', '2025-06-15'),
(2, 'Heathrow Airport', '14:45 PM', 'London', 1, '780', 'Economy', '2025-06-20'),
(3, 'Dubai International Airport', '22:15 PM', 'Dubai', 8, '925', 'Economy', '2025-07-05'),
(4, 'Charles de Gaulle Airport', '11:20 AM', 'Paris', 5, '430', 'Economy', '2025-07-10'),
(5, 'Narita International Airport', '08:45 AM', 'Tokyo', 9, '1100', 'Economy', '2025-08-01'),
(6, 'Los Angeles International Airport', '16:30 PM', 'Los Angeles', 15, '1250', 'Economy', '2025-08-15'),
(7, 'O\'Hare International Airport', '07:15 AM', 'Chicago', 6, '890', 'Economy', '2025-09-01'),
(8, 'JFK International Airport', '10:30 AM', 'New York', 4, '2350', 'Business', '2025-06-15'),
(9, 'Heathrow Airport', '15:45 PM', 'London', 1, '2100', 'Business', '2025-06-20'),
(10, 'Dubai International Airport', '23:15 PM', 'Dubai', 8, '2680', 'Business', '2025-07-05'),
(11, 'Singapore Changi Airport', '13:20 PM', 'Singapore', 19, '3100', 'First Class', '2025-09-10'),
(12, 'Hong Kong International Airport', '19:45 PM', 'Hong Kong', 10, '3500', 'First Class', '2025-10-01'),
(13, 'Toronto Pearson Airport', '08:00 AM', 'Toronto', 14, '950', 'Economy', '2025-09-12'),
(14, 'Galeão International Airport', '12:30 PM', 'Rio de Janeiro', 15, '1400', 'Business', '2025-09-20'),
(15, 'OR Tambo International Airport', '10:15 AM', 'Johannesburg', 3, '1280', 'Economy', '2025-10-05'),
(16, 'Indira Gandhi International Airport', '23:00 PM', 'Delhi', 10, '1100', 'Economy', '2025-10-18'),
(17, 'Lisbon Portela Airport', '06:20 AM', 'Lisbon', 18, '870', 'Economy', '2025-11-02'),
(18, 'Ben Gurion Airport', '14:10 PM', 'Tel Aviv', 7, '970', 'Business', '2025-11-15'),
(19, 'El Dorado International Airport', '17:50 PM', 'Bogotá', 16, '1320', 'Economy', '2025-11-25'),
(20, 'George Bush Intercontinental Airport', '15:40 PM', 'Houston', 20, '1250', 'Economy', '2025-12-01'),
(21, 'Suvarnabhumi Airport', '21:35 PM', 'Bangkok', 9, '1000', 'Economy', '2025-12-10'),
(22, 'Haneda Airport', '18:20 PM', 'Tokyo', 8, '2650', 'First Class', '2025-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotelid` int(11) NOT NULL,
  `hotelname` varchar(50) NOT NULL,
  `destid` int(11) NOT NULL,
  `price` varchar(50) NOT NULL,
  `stars` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `numofpeople` varchar(50) NOT NULL,
  `location` varchar(200) NOT NULL,
  `hotelimage` varchar(200) NOT NULL,
  `locationlink` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotelid`, `hotelname`, `destid`, `price`, `stars`, `time`, `numofpeople`, `location`, `hotelimage`, `locationlink`) VALUES
(1, 'Grand Pyramids Hotel', 1, '120', '4', '14:00', '2', 'Giza', 'Grand Pyramids Hotel.jpg', 'https://maps.google.com/?q=Grand+Pyramids+Hotel+Giza+Egypt'),
(2, 'Le Riad Yasmine', 2, '95', '3', '15:00', '2', 'Rue Ank Jemel', 'HLeRiadYasmine.jpeg', 'https://maps.google.com/?q=Le+Riad+Yasmine+Marrakech+Morocco'),
(3, 'Table Bay Hotel', 3, '210', '5', '15:00', '2', 'V&A Waterfront', 'cape-town.jpg', 'https://maps.google.com/?q=Table+Bay+Hotel+V%26A+Waterfront+Cape+Town'),
(4, 'Hôtel Plaza Athénée', 4, '450', '5', '15:00', '2', 'Avenue Montaigne', 'HôtelPlazaAthénée.JPG', 'https://maps.google.com/?q=Hotel+Plaza+Athenee+Avenue+Montaigne+Paris'),
(5, 'Hotel Artemide', 5, '180', '4', '14:00', '2', 'Via Nazionale', 'Hotel Artemide.jpg', 'https://maps.google.com/?q=Hotel+Artemide+Via+Nazionale+Rome'),
(6, 'H10 Madison', 6, '215', '4', '14:00', '2', 'Gothic Quarter', 'Barcelona-H10-Madison-Hotel-Rooftop-View.webp', 'https://maps.google.com/?q=H10+Madison+Gothic+Quarter+Barcelona'),
(7, 'Canaves Oia Suites', 7, '580', '5', '15:00', '2', 'Oia', 'CanavesOiaSuites.jpg', 'https://maps.google.com/?q=Canaves+Oia+Suites+Santorini+Greece'),
(8, 'Park Hyatt Tokyo', 8, '450', '5', '15:00', '2', 'Shinjuku', 'Park-Hyatt-Tokyo.webp', 'https://maps.google.com/?q=Park+Hyatt+Tokyo+Shinjuku'),
(9, 'Mandarin Oriental Bangkok', 9, '320', '5', '14:00', '2', 'Riverside', 'mandarin-oriental-bangkok.jpg', 'https://maps.google.com/?q=Mandarin+Oriental+Bangkok+Riverside'),
(10, 'Taj Rambagh Palace', 10, '380', '5', '14:00', '2', 'Bhawani Singh Road', 'Taj Rambagh Palace.jpg', 'https://maps.google.com/?q=Taj+Rambagh+Palace+Bhawani+Singh+Road+Jaipur'),
(11, 'Burj Al Arab Jumeirah', 11, '1200', '5', '15:00', '2', 'Jumeirah', 'Burj_Al_Arab,_Dubai,_by_Joi_Ito_Dec2007.jpg', 'https://maps.google.com/?q=Burj+Al+Arab+Jumeirah+Dubai'),
(12, 'The Plaza', 12, '550', '5', '16:00', '2', 'Fifth Avenue', 'ThePlaza.jpg', 'https://maps.google.com/?q=The+Plaza+Hotel+Fifth+Avenue+New+York'),
(13, 'St. Regis Mexico City', 13, '310', '5', '15:00', '2', 'Paseo de la Reforma', 'HMEXICO.jpg', 'https://maps.google.com/?q=St+Regis+Mexico+City+Paseo+de+la+Reforma'),
(14, 'Fairmont Pacific Rim', 14, '290', '5', '16:00', '2', 'Downtown', 'RIM.webp', 'https://maps.google.com/?q=Fairmont+Pacific+Rim+Vancouver+Downtown'),
(15, 'Belmond Copacabana Palace', 15, '340', '5', '15:00', '2', 'Copacabana Beach', 'copacabana.jpg', 'https://maps.google.com/?q=Belmond+Copacabana+Palace+Rio+de+Janeiro'),
(16, 'JW Marriott El Convento Cusco', 16, '250', '5', '15:00', '2', 'Historic Center', 'JW Marriott El Convento Cusco.jpg', 'https://maps.google.com/?q=JW+Marriott+El+Convento+Cusco+Historic+Center'),
(17, 'Alvear Palace Hotel', 17, '320', '5', '15:00', '2', 'Recoleta', 'ALVEAR.jpg', 'https://maps.google.com/?q=Alvear+Palace+Hotel+Recoleta+Buenos+Aires'),
(18, 'Four Seasons Hotel Lisbon', 18, '290', '5', '15:00', '2', 'Avenida da Liberdade', '4SEASONS.jpg', 'https://maps.google.com/?q=Four+Seasons+Hotel+Lisbon+Avenida+da+Liberdade'),
(19, 'Marina Bay Sands', 19, '450', '5', '15:00', '2', 'Marina Bay', 'MARINA.avif', 'https://maps.google.com/?q=Marina+Bay+Sands+Singapore'),
(20, 'The Fairmont San Francisco', 20, '380', '4', '16:00', '2', 'Nob Hill', 'The Fairmont San Francisco.webp', 'https://maps.google.com/?q=Fairmont+San+Francisco+Nob+Hill'),
(21, 'the embassy Hotel', 20, '500', '5', '08:00', '3', 'little saigon', 'hotel_683066b99dacb.jpg', 'https://maps.google.com/?q=Embassy+Hotel+Little+Saigon+San+Francisco');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `tourid` int(11) NOT NULL,
  `tourname` varchar(50) NOT NULL,
  `destid` int(11) NOT NULL,
  `flightid` int(11) NOT NULL,
  `hotelid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `duration` int(11) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`tourid`, `tourname`, `destid`, `flightid`, `hotelid`, `price`, `rating`, `duration`, `image`) VALUES
(1, 'Egyptian Wonders', 1, 2, 1, 1250.00, 4.5, 7, 'EGYPT1.webp'),
(2, 'Magical Marrakech', 2, 2, 2, 890.00, 4.3, 5, 'Trip-in-Marrakech.jpg'),
(3, 'Cape Explorer', 3, 2, 3, 1750.00, 4.8, 10, 'cape-town-city-tour.jpg'),
(4, 'Paris Wonderer', 4, 1, 4, 1950.00, 4.7, 6, 'paris-at-spring.webp'),
(5, 'Roman Holiday', 5, 4, 5, 1450.00, 4.6, 7, 'romeTour.jpg'),
(6, 'Barcelona Experience', 6, 7, 6, 1350.00, 4.4, 6, 'barcelonaTourjpg.jpg'),
(7, 'Santorini Sunset', 7, 4, 7, 2100.00, 4.9, 8, 'greecetour.webp'),
(8, 'Tokyo Explorer', 8, 3, 8, 2450.00, 4.7, 9, 'tokyotour.jpg'),
(9, 'Bangkok Adventure', 9, 5, 9, 1550.00, 4.3, 7, 'bangkok.jpeg'),
(10, 'Jaipur Heritage', 10, 3, 10, 1650.00, 4.5, 8, 'JindiaTour.jpg'),
(11, 'Dubai Luxury', 11, 3, 11, 2950.00, 4.8, 6, 'dubaiTour.jpg'),
(12, 'New York Discovery', 12, 1, 12, 2250.00, 4.6, 7, 'newYorkTour.jpg'),
(13, 'Mexico City Culinary Tour', 13, 6, 13, 1750.00, 4.5, 8, 'Mexico-City.jpg'),
(14, 'Vancouver & Nature', 14, 7, 14, 1850.00, 4.7, 9, 'vancouver-nature.avif'),
(15, 'Rio Carnival Experience', 15, 6, 15, 2450.00, 4.9, 10, 'BrazilTour.jpg'),
(16, 'Inca Trail Adventure', 16, 6, 16, 2150.00, 4.8, 12, 'peruTour.jpg'),
(17, 'Soul of Buenos Aires Tour', 17, 6, 17, 1950.00, 4.6, 9, 'argentinaTourjpg.jpg'),
(18, 'Lisbon Discovery', 18, 4, 18, 1450.00, 4.4, 6, 'lisbonTour.jpg'),
(19, 'Singapore Splendor', 19, 11, 19, 2350.00, 4.7, 5, 'singaporTour.avif'),
(20, 'San Francisco Highlights', 20, 6, 20, 1850.00, 4.5, 7, 'sanfrancisco.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerid`),
  ADD KEY `customer_destid_fk` (`destid`),
  ADD KEY `customer_tourid_fk` (`tourid`),
  ADD KEY `customer_hotelid_fk` (`hotelid`),
  ADD KEY `customer_flightid_fk` (`flightid`);

--
-- Indexes for table `destination`
--
ALTER TABLE `destination`
  ADD PRIMARY KEY (`destid`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`flightid`),
  ADD KEY `flights_destid_fk` (`destid`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotelid`),
  ADD KEY `hotels_destid_fk` (`destid`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`tourid`),
  ADD KEY `tours_destid_fk` (`destid`),
  ADD KEY `tours_flightid_fk` (`flightid`),
  ADD KEY `tours_hotelid_fk` (`hotelid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `destination`
--
ALTER TABLE `destination`
  MODIFY `destid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `flightid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotelid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `tourid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `flights_destid_fk` FOREIGN KEY (`destid`) REFERENCES `destination` (`destid`);

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_destid_fk` FOREIGN KEY (`destid`) REFERENCES `destination` (`destid`);

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_destid_fk` FOREIGN KEY (`destid`) REFERENCES `destination` (`destid`),
  ADD CONSTRAINT `tours_flightid_fk` FOREIGN KEY (`flightid`) REFERENCES `flights` (`flightid`),
  ADD CONSTRAINT `tours_hotelid_fk` FOREIGN KEY (`hotelid`) REFERENCES `hotels` (`hotelid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
