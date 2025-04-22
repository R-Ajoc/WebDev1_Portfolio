-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 08:31 PM
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
-- Database: `nurse_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `bed_id` int(11) NOT NULL,
  `bed_number` varchar(50) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `bed_uuid` binary(16) NOT NULL,
  `patient_uuid` binary(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`bed_id`, `bed_number`, `department_id`, `bed_uuid`, `patient_uuid`) VALUES
(1, '1', 14, 0x02a35de5721d4889beac21f1c7a9616e, NULL),
(2, '2', 12, 0xcfa92df7799d4f72b209ff2fd1684938, NULL),
(5, '5', 8, 0x07831cc939fd4a7f9c21b9c3272d33a8, NULL),
(6, '6', 10, 0x378f1c386b9649a2bb83c3ca5c234048, NULL),
(7, '7', 14, 0xb8312ff1a318462aade54527434e32f7, 0x10da528ec67a4f568f944cff97e41d27),
(8, '23', 16, 0x5c0a5ecb24cb45638e588653d464c962, 0xf8dd7ad1e4564c77aab8bac982ad7b35);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `deptname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `deptname`) VALUES
(1, 'Emergency Department'),
(2, 'General Medical Ward'),
(3, 'Medical Ward Extension'),
(4, 'Surgical Ward'),
(5, 'PICU'),
(6, 'ICU'),
(7, 'NICU'),
(8, 'Pediatric Ward'),
(9, 'Pulmo-Pediatric Ward'),
(10, 'OB-Gyne Ward'),
(11, 'Oncology Ward'),
(12, 'Geriatric Ward'),
(13, 'Gastroenterology Ward'),
(14, 'Infectious Disease Ward'),
(15, 'Trauma Ward'),
(16, 'Orthopedic Ward');

-- --------------------------------------------------------

--
-- Table structure for table `endorsements`
--

CREATE TABLE `endorsements` (
  `endorsement_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `endorsement_views`
--

CREATE TABLE `endorsement_views` (
  `view_id` int(11) NOT NULL,
  `endorsement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ivf_infusions`
--

CREATE TABLE `ivf_infusions` (
  `ivf_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `bottle_no` varchar(50) DEFAULT NULL,
  `ivf` text DEFAULT NULL,
  `rate` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `bed_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `midinit` char(1) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `physician` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `patient_uuid` binary(16) NOT NULL,
  `bed_uuid` binary(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `gender`, `diagnosis`, `bed_id`, `created_at`, `lastname`, `firstname`, `midinit`, `status`, `nationality`, `religion`, `physician`, `date_of_birth`, `patient_uuid`, `bed_uuid`) VALUES
(10, '', 'DDA', NULL, '2025-04-18 17:43:08', 'KLFDS', 'SADN', '', '', '', '', '', '0000-00-00', 0x10da528ec67a4f568f944cff97e41d27, 0xb8312ff1a318462aade54527434e32f7),
(11, '', 'DAS', NULL, '2025-04-18 18:07:38', 'LKJLS', 'SADK', '', '', '', '', '', '0000-00-00', 0xf8dd7ad1e4564c77aab8bac982ad7b35, 0x5c0a5ecb24cb45638e588653d464c962),
(12, '', 'asd', NULL, '2025-04-18 18:29:13', 'Guiral', 'Ralph Miguel', '', '', '', '', '', '0000-00-00', 0xb26cc61eecb74a2fb42d87a2adfb84da, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referral_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `referred_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `treatment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `treatment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `midinit` char(1) DEFAULT NULL,
  `passcode` varchar(255) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `department_id`, `created_at`, `lastname`, `firstname`, `midinit`, `passcode`, `contactNo`) VALUES
(2, '1231@GMAIL.COM', '$2y$10$rFrfWnVtmv5VsaEzK/zpsOj6hf5tBEtqge/UfNhJI7mN/RvaT8Zfy', 2, '2025-04-17 10:04:27', 'GJHDGSJF', 'AGSHDA', 'G', '$2y$10$NfKcsE8Ua0xA71No7YDpV.IQhnc2B3c9SAw0q6RkQYTM1YPrdkIaW', '1231231'),
(3, 'raphzsguiral@yahoo.com', '$2y$10$xAHufDhSJ64vs6tlis1vU.pc0BzGNtdasvWZyJVoboN0nk9PWgQmO', 2, '2025-04-17 10:07:15', 'Guiral', 'Ralph Miguel', 'C', '$2y$10$qjz2b911lS4K5a./YnULHOOjcD6u7H7Rv48Eqnma1/r6W.J2L2AoG', '09232929'),
(4, '12331@GMAIL.COM', '$2y$10$Tn4YGjHGbWaoDRcTwaJdi.vNAi2edx6Yvi7P9SZ.Kb6KZog8jGpnK', 1, '2025-04-17 10:09:21', 'afaf', 'Ralph Miguel', 'm', '$2y$10$GccUSUOBV0DWtCAYacIYx.kYl08poyvBhd7Ko1fnmKqteYZV/3QTa', '123'),
(5, 'askakls@gmail.com', '$2y$10$Wjl4xfnVIGz0VpxxcHtAHuBdIfce9fCfWGaIpHRnz66/39PmXXn26', 1, '2025-04-17 10:31:17', 'AFSD', 'Ralph Miguel', 'M', '$2y$10$P.n/G2UrzOJzTo4VaGFFxOKK2J.ZBLpTUcy/0O0pu0gFGW0M5iiWO', '12313'),
(6, 'margaritaguiral@gmail.com', '$2y$10$/S465kB9baKQ3tBUTBrUieq2CDjH5sU40grLCNyjYE2LfbBCUVm6y', 2, '2025-04-17 11:10:59', 'Guiral', 'Ralph Miguel', '', '$2y$10$.3Df7lzTSJVb3FyzX.aQs.sHVsewmC.oXraD8.c7HiqzCwqKU1K82', ''),
(7, 'raphzsguiral@yahoo.', '$2y$10$KeZGEp26nUGRd8MBsL94rOjR2ing55qkgubYsXD0lf4nDVRjnLvNO', 12, '2025-04-17 11:11:59', 'Guiral', 'Ralph Miguel', 'C', '$2y$10$0HUO9afweRG6YCqBi/i7b.aeDmXVz/udL.wknBCBGL7FhqyjO5uNG', ''),
(8, 'neverthemind25@gmail.com', '$2y$10$117rq7UoBxvZGG0a5MPsBep5eZnAfClNRlUyyAqYwW.MZt1h8YzH2', 13, '2025-04-17 13:14:57', 'Guiral', 'Ralph Miguel', 'c', '$2y$10$5/xDBkqZjMh5ayd.iEpoc.ipYBJRSdoozcXfSlZBnYyQxVflXKgNy', '09166244271'),
(9, 'toraja@gmail.com', '$2y$10$AVMKLsuL3BTvLhUIzuqZk.g8TRgZ2Z4tmuAs56i0HNFK/2q80AAL2', 13, '2025-04-17 14:02:46', 'Toraja', 'John Elmer', '', '$2y$10$Uep04HiofgiMeOw1eADjr.ot8hRYh1ojbNX5PO5anFZnbcyX3Bgde', ''),
(10, 'animal@gmail.com', '$2y$10$E27RfVRuHri/Ztz0X/Z4ourlN3jZlK/G9fHmXOi2xcuiNM/7vPbhe', 10, '2025-04-17 14:04:21', 'animal', 'boang ko', '', '$2y$10$h4jWoLUU936G.sBOUNXhDOdoVcCyAJAwHp1ZpBfv943itchPsCReC', ''),
(11, 'guiral@gmail.com', '$2y$10$X86yS5t8qzWtE.egKh5q8u50T5WDeeR28Pp8gKaEIlUka.oxo5EK2', 10, '2025-04-17 14:05:38', 'Guiral', 'Ralph Miguel', '', '$2y$10$r2kTuUwB8VcqL14PmhVinuuKaWI5kN9iQS6Asr9VRkkDir.DSwUzu', ''),
(12, 'karenpig@gmail.com', '$2y$10$04gFDB/cyTnlvsxTmmes2e02uV.fAVsjShZKdlXN3kXVCmFgoUkJG', 9, '2025-04-18 00:44:19', 'Guiral', 'Karen Marie', '', '$2y$10$9azaKaT49fHLpfP1CMuuIuFDYTKTSnoqWAE0GvkuubZ/O/74GKVju', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`bed_id`),
  ADD UNIQUE KEY `bed_uuid` (`bed_uuid`),
  ADD UNIQUE KEY `patient_uuid` (`patient_uuid`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `endorsements`
--
ALTER TABLE `endorsements`
  ADD PRIMARY KEY (`endorsement_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `endorsement_views`
--
ALTER TABLE `endorsement_views`
  ADD PRIMARY KEY (`view_id`),
  ADD KEY `endorsement_id` (`endorsement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ivf_infusions`
--
ALTER TABLE `ivf_infusions`
  ADD PRIMARY KEY (`ivf_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `patient_uuid` (`patient_uuid`),
  ADD UNIQUE KEY `bed_uuid` (`bed_uuid`),
  ADD KEY `bed_id` (`bed_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`referral_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`treatment_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `endorsements`
--
ALTER TABLE `endorsements`
  MODIFY `endorsement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `endorsement_views`
--
ALTER TABLE `endorsement_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ivf_infusions`
--
ALTER TABLE `ivf_infusions`
  MODIFY `ivf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `treatment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `fk_patient_uuid` FOREIGN KEY (`patient_uuid`) REFERENCES `patients` (`patient_uuid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `endorsements`
--
ALTER TABLE `endorsements`
  ADD CONSTRAINT `endorsements_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `endorsements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `endorsement_views`
--
ALTER TABLE `endorsement_views`
  ADD CONSTRAINT `endorsement_views_ibfk_1` FOREIGN KEY (`endorsement_id`) REFERENCES `endorsements` (`endorsement_id`),
  ADD CONSTRAINT `endorsement_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ivf_infusions`
--
ALTER TABLE `ivf_infusions`
  ADD CONSTRAINT `ivf_infusions_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_bed_uuid` FOREIGN KEY (`bed_uuid`) REFERENCES `beds` (`bed_uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`bed_id`);

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `treatments`
--
ALTER TABLE `treatments`
  ADD CONSTRAINT `treatments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
