-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 05:27 AM
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
-- Database: `business_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `coverletter` text NOT NULL,
  `cv_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

CREATE TABLE `employe` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `gender` enum('male','female','other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id`, `fullname`, `email`, `password`, `phone`, `gender`) VALUES
(1, 'rabin', 'rabindrapoudel25@gmail.com', '$2y$10$CXiccpYbNJtbf/Y0S4VQ1OJpO7jxu4cxbCzAsiOrxXHOzJSW66nqW', '9816153637', 'male'),
(3, 'Narendr', 'rabindrapoudel215@gmail.com', '$2y$10$t3qq5orIwtj2P/ylBkPJu.oJs4LGJ9OmM2NTZsK4E0xlqHVrwX6.G', '9816113637', 'male'),
(4, 'Narendr', 'rabindrapoudel235@gmail.com', '$2y$10$FhmhQTgMFzNl.wuHrliaJ.uvBmDZy/lh8Uu316nw3yFT1He1d6e2u', '9816153657', 'male'),
(5, 'Naren', 'rabindrapl25@gmail.com', '$2y$10$dpwzg3.f00E1jovryz7Qx.f8zKdG5cb/q47QEbUGPIbaMloK0GICa', '9816353637', 'male'),
(6, 'Narendr', 'rabirapoudel25@gmail.com', '$2y$10$WNtyuRMSjW4pJZ1.wBuzteDE51ypEXVGo77vnRtnXNY4tv2KlTPba', '9816150637', 'male'),
(7, 'Narendr', 'rabirapoud4l25@gmail.com', '$2y$10$tav32p.w5Od8OeKDjFK8ROZDgYFQqXfGDPoa6wZHHndDExvBuJPPa', '9816150737', 'male'),
(8, 'Narend', 'rabapoudel25@gmail.com', '$2y$10$cBGnmJGxALMj2H0cNPuLE.iXi8k8NnZJnCHH2Owh6Md3bNs7A9awa', '9886153637', 'male'),
(9, 'Narend  ewferg', 'rabapoudel25@gmail.com1', '$2y$10$XxhUMP4r2R9FwSjRY/pW8u7an7iyZ2UqFm.VRJODN8UdTJHZnc/Bi', '9886137', 'male'),
(10, 'Narendr', 'rabiudel25@gmail.com', '$2y$10$xOAUzh3Wx6.Mrt8B4VCeQOrY1r/9F0cICjVb1WsNOpwMs.WBxpZyC', '9816153630', 'male'),
(11, 'Narendr', 'rabil25@gmail.com', '$2y$10$BR2D6zUGv67zZfx/hkW0ouNL9HMrWJThOA/vvGW26EU1Kkotzqinm', '981615363', 'male'),
(12, 'Narendra', 'rabindrapoudel', '$2y$10$UeLp03mHQNXzAqoguCSdoev2F47TY31.yWRd31Amrq9l5sWc287EW', '9816153', 'male'),
(13, 'dfghjk', 'sdfvgbhn@gmail.com', '$2y$10$cQBj2eg6bgb32LBOLghQLOobNLhL/Z/0l16SfuYRjQZcua3u34IBC', '9816151515', 'male'),
(14, 'Bishal Adhikari', 'adbishal@gmail.com', '$2y$10$82I10JKnUup33GW0GZemp.g5p7v1s030A3XRwG3Qv8ChjpX.fhLL6', '9825150365', 'male'),
(15, 'bijaya bhurtel', 'bijaya01@gmail.com', '$2y$10$caWykl.8H1epwcfTCvZgM.iTbMzQFF9YH933VfuGn65i0tyd7drDe', '9826107361', 'female'),
(17, 'sajan', 'sajankshteri@gmail.com', '$2y$10$h8gnwho99WmqbJVJtYN.6eC89fCfB3hcZFgHSwZ1nFwhTSuwdg1jO', '9856421311', 'male'),
(18, 'rabindra poudel', 'rabindrapoudel@gmail.com', '$2y$10$luYAVECLcubTSvzHdA6q5euDP1LPmgTXA2KMNCAbsaWuMwDKrqaRO', '9898989898', 'male');

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

CREATE TABLE `job_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `salary` varchar(255) NOT NULL,
  `dateOpen` date NOT NULL,
  `dateEnd` date NOT NULL,
  `education` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `interviewDate` date DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_posts`
--

INSERT INTO `job_posts` (`id`, `user_id`, `title`, `salary`, `dateOpen`, `dateEnd`, `education`, `experience`, `description`, `interviewDate`, `startTime`, `endTime`) VALUES
(1, 41, 'rabindra', 'Negotiable ', '2024-06-22', '2024-06-27', '+2dihfjd', 'dewlsf', 'mlsf.sd/ vddsfldsf', NULL, NULL, NULL),
(2, 41, 'kfdg', 'fdnmmg,f', '2024-06-06', '2024-06-14', 'djfdoskfmd', 'dfndkmffknd', 'd mfnmds', NULL, NULL, NULL),
(3, 42, 'manager', '50,000', '2024-05-05', '2024-05-15', 'degree in bba', '2 years ', 'we are finding energy person', NULL, NULL, NULL),
(4, 42, 'Desk Helper', 'Negotiable ', '2024-12-31', '2023-01-31', '+2', '3years Experience', 'We are finding Young and Energetic person', NULL, NULL, NULL),
(5, 43, 'web design ', '10090-200000', '2024-06-05', '2024-06-27', '+2', 'fresh can apply ', 'we are hiring a skill candidate', NULL, NULL, NULL),
(6, 44, 'designer', '100000', '2024-06-11', '2024-06-20', 'bachelor in bca', '+2 expreience', 'we are searching for young and skill ', NULL, NULL, NULL),
(7, 45, 'google', '10k', '2024-06-04', '2024-06-30', '+2', 'no ', 'nskdsf skjsd', NULL, NULL, NULL),
(8, 46, 'fkgfg', 'fdkf', '2024-06-11', '2024-06-13', 'fmkd', 'dkf', 'kcvf', NULL, NULL, NULL),
(9, 46, 'rabindra ', '10k', '2024-06-01', '2024-06-13', 'edfgrhn', 'lkf,vfg', 'fmkegfeg', '2024-06-28', '23:10:00', '00:10:00'),
(10, 47, 'BRANCH MANAGAR', '100', '2024-07-28', '2024-08-03', '+2', 'CKDN CD', 'KMDdms', '2024-08-01', '07:35:00', '10:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `businessName` varchar(255) NOT NULL,
  `businessAddress` varchar(255) NOT NULL,
  `businessType` varchar(255) NOT NULL,
  `businessEmail` varchar(255) NOT NULL,
  `businessPan` varchar(20) NOT NULL,
  `businessPhoneNumber` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `panDocument` varchar(255) NOT NULL,
  `profileImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `businessName`, `businessAddress`, `businessType`, `businessEmail`, `businessPan`, `businessPhoneNumber`, `password`, `panDocument`, `profileImage`) VALUES
(15, 'rabindra', 'rabindra', 'rabindra', '1@gmail.com', '1234567', '098765432654', 'rabi', '662f1d3d359c1_image 1.png', 'image 1.png'),
(16, 'sajan', 'sajan pokhara', 'sajan', 'sajan@gmail.com', '0987654', '9816151551515', 'sajan', '662f52f2507f8_hard.png', '662f70bee77be_Screenshot (8).png'),
(17, 'rabindra', 'sajan pokhara', 'sajan', 'sajan1@gmail.com', '44444', '234565432', 'sajan1', '662f5825a8eac_image 1.png', 'pixlr-image-generator-74d6cbba-30f7-4d4e-8ae7-3b25e83c98ed.png'),
(18, 'rasds', '234567ui', 'aqwsedfrgthjk', '123@gmail.com', '345654398756798765', '678976543234', '123123', '662f6aabf3b3f_image 1.png', '662fb21548c80_pixlr-image-generator-74d6cbba-30f7-4d4e-8ae7-3b25e83c98ed (1).png'),
(19, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'Narendra Madhira Pasal', 'rabindrapoudel25@gmail.com', '123456789', '9816153637', '12345', '6631ff0f887f6_pixlr-image-generator-74d6cbba-30f7-4d4e-8ae7-3b25e83c98ed.png', '6666d7ce466eb_java to sql.png'),
(20, 'nirmal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'Narendra Madhira Pasal', 'nirmalbastola444@gmail.com', '677864', '778765678985', 'nirmalb', '6632de928cd1f_pic1.png', '6632deeae7b3b_Picture1.png'),
(21, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', '2r3wqf', 'rabindrapoudl25@gmail.com', '23333333333333333333', '9816153633', '1234A', '66331a00c197a_pic1.png', '66331a00c283f_pic1.png'),
(22, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'rabin', 'qwer@gmail.com', '123459403', '9876543210', '987654321', '6659f4cc71916_image 1.png', '6659f4cc71ef5_addimage.png'),
(23, 'ttt', 'dujjd', 'nkdcfsd', 't@gmail.com', '12345677', '987727773', '$2y$10$xh0T6YpBCShwdUG1uC94.OMTAcnKBUtCr3vQQ6lMl6qj9d.4y1L56', '665e9801193b9_first.img (31).jpg', '665e9801193b9_first.img (26).jpg'),
(24, 'ttt', 'dujjd', 'nkdcfsd', 't@gmail.com', '12345677', '987727773', '$2y$10$9YqD6gREc2rYqcgpTFPZCOOurzuBj3uWeBtBCnmbyutGBEw9r2YMu', '665e9801193b9_first.img (31).jpg', '665e9801193b9_first.img (26).jpg'),
(25, 'ttt', 'dujjd', 'nkdcfsd', 't@gmail.com', '12345677', '987727773', '$2y$10$nTD.L6Jaq7kLmfWyyxj6k.S3CIkJ15Q72FKD5rpCP8PzHQUAE9MqC', '665e9801193b9_first.img (31).jpg', '665e9801193b9_first.img (26).jpg'),
(26, 'rabi', 'qdasfd', '12345', 'rabindrapudel25@gmail.com', '12345678', '123456789', '$2y$10$WxoNK70R5AFEqNMRpJ20UOXQBjiaqdJ31M1BuVulgChFcRy0Xezly', 'uploads/665e9801193b9_first.img (32).jpg', 'uploads/665e9801193b9_first.img (33).jpg'),
(27, 'rabi', 'qdasfd', 'dsff', 'rabindrapoudel13@gmail.com', '12345675', '9816153613', '$2y$10$WtU0gWbmjXT9srYxHZPcfurVayd1hxEUPEfU3FFMRzPx9SQ1V39oa', 'uploads/665e9801193b9_first.img (33).jpg', 'uploads/665e9801193b9_first.img (33).jpg'),
(28, 'rabi', 'qdasfd', 'OTHER', 'rabindrapudel11@gmail.com', '12345670', '12345678', '$2y$10$eveh3g1D0yRMwnK5Ml3v1.43e7A3YXrIzRDQV.HIcxi6PQhWUXEB.', 'uploads/665e9801193b9_first.img (33).jpg', 'uploads/665e9801193b9_first.img (34).jpg'),
(29, 'rabi', 'qdasfd', 'Tourism and Hospitality', 'rabindrapoudel14@gmail.com', '123455555', '123456784', '$2y$10$izUrsRyAIYjIhxxkGH9k3O5MBB2eiRaKUnrxc9lUrsTzjwsXlKvmO', 'uploads/665e9801193b9_first.img (33).jpg', '6665fb54b818c_665e9801193b9_first.img (25).jpg'),
(30, 'dhjk', 'dnmkf,.', 'OTHER', 'good@gmail.com', '12345222', '981615368', '$2y$10$FpLGzg.MfnU3farnDljCNu8TCN7COufXRR1yb5NujeNl1R6huQzdS', 'uploads/6665fcc8badd4_665e9801193b9_first.img (33).jpg', 'uploads/6665fcc8badd4_665e9801193b9_first.img (33).jpg'),
(31, 'dhjk', 'dnmkf,.', 'OTHER', 'good1@gmail.com', '398763', '981615365', '123123a', 'uploads/6665fee2cd65f_665e9801193b9_first.img (33).jpg', 'uploads/6665fee2cd65f_665e9801193b9_first.img (33).jpg'),
(32, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'type', 'test121@gmail.com', '23432', '987263363', '123456', '666651d041d3a_665e9801193b9_first.img (25).jpg', '666651d042178_hard.png'),
(33, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'Narendra Madhira Pasal', 'rabindrapoudel23@gmail.com', '123456', '99999999', '12345', '6666d70bad409_java to sql.png', '6666d70bad741_multiple threading.png'),
(34, 'NEPALI TYPING', 'POKHARA 15', 'Narendra Madhira Pasal', 'b1@gmail.com', '39487637', '83433646', '123456', '6666dc89ad33e_multiple threading.png', '6666dcdc8a9c8_multiple threading.png'),
(35, 'rabindra', 'dnmkf,.', 'cdmsv,', 'dmvdf@gmail.com', '12345671', '9816150000', '12345', '6666ef1e8556d_665e9801193b9_first.img (25).jpg', '6666ef1e85b47_665e9801193b9_first.img (26).jpg'),
(36, 'rabindra', 'dnmkf,.', 'cdmsv,', 'apple1@gmail.com', '1234111', '9816153233', '12345a', '6666f3b378415_white.jpg', '6666f3b378892_white.jpg'),
(37, 'rabindra', 'dnmkf,.', 'cdmsv,', 'apple2@gmail.com', '1234511', '9816333333', '12345a', '6666f9d2c2095_hard.png', '6666f9d2c237f_hard.png'),
(38, 'rabindra', 'dnmkf,.', 'cdmsv,', 'poudel1@gmail.com', '1234456', '9815363001', '12345a', '6667175522428_hard.png', '666717552432a_hard.png'),
(39, 'dwjhnjmekw', 'amsawef', 'school', 'apple3@gmail.com', '65456765', '9818818155', '12345a', '66671a8e05a46_image 1.png', '66671a8e05d3a_image 1.png'),
(40, '12345', 'pokhara', 'google', 'apple4@gmail.com', '82736782', '0345263099', '$2y$10$UOPD.rNDWJx9tCqXT/wzl.lZ8H2DJc1lrAFaAuyQdkttZnH5yPfGe', '6667300c09013_image 1.png', '6667300c09bb7_image 1.png'),
(41, 'google', 'google', 'google', 'googletest@gmail.com', '6373847', '0999999999', '$2y$10$dqtFzDKwTVpyHFJgaQckdO0z/hnE3U8Tsi3xLeZJG96sX2PG/DGma', '6667311073c51_about photo.png', '66674e202df7c_1712159188922.gif'),
(42, 'prime bank', 'Bagar 1 pokhara', 'banking', 'primebank@gmail.com', '098765', '9846098460', '$2y$10$U29u6vIN1nU0hYiu9xslquvn/XUVddeeo67wnbo0ePongoAceuWzm', '6667b2935328d_c.png', '6667b29353779_about photo.png'),
(43, 'la grandee', 'pokhara 5', 'school', 'lgic@gmail.com', '34342333', '9816153212', '$2y$10$UInyA01kxe3lhgjOz3b1UuMtoSTZNNpjaY0LICf0ySWhxjx3xsZSa', '6667d9ef47b9e_Discover the Beauty of Nepal.png', '6667d9ef48016_665e9801193b9_first.img (21).jpg'),
(44, 'appple ', 'pokhara15', 'tech', 'apple1234@gmail.com', '32323223', '9876525299', '$2y$10$MRcwDVSimC7BBXtv5TIXN.XXujQ3X7BminG70w4Mi/EVsg9HybL0m', '6667dee3002e3_665e9801193b9_first.img (25).jpg', '6667dee300983_hard.png'),
(45, 'google', 'pokhara', 'Finance/Accounting', 'a1@gmail.com', '1212323', '9816153636', '$2y$10$aUigL2P6C9rpbL42GnFxf.9jXz1IyYQd1/tV/yNpZAJWLdwK7T.dm', '6675b0918932c_441967705_451239851191612_8952406799719146386_n.jpg', '6675b09189e5d_6667311073c51_about photo.png'),
(46, 'Narendra Madhira Pasal', 'RISHIMARGA -15 POKHARA RAMBAZAR', 'Agriculture/Farming', 'rabindrapoudel@gmail.com', '2723782', '9816161616', '$2y$10$1vGSsY8A6Q1OgAEgr1m/7.gy6Sq32TppJMxRod8e5NuE0b2OdE9N6', '667c3a615364a_addimage.png', '667c3a61557fd_addimage.png'),
(47, 'dssfedg', 'efdsfds', 'Banking/Insurance', '0@gmail.com', '0039333', '9816153237', '$2y$10$pyFkJi.Rdp1YecejuVBIwOe9E1K15COUOrzNShZ4hjd9qN4kiMaoa', '6682ee1ae9d70_white.jpg', '6682ee1aea098_6667311073c51_about photo.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `job_posts`
--
ALTER TABLE `job_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `employe` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`id`);

--
-- Constraints for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD CONSTRAINT `job_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
