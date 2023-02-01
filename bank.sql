-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2022 at 06:52 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `bankaccount`
--

CREATE TABLE `bankaccount` (
  `BankAccountID` int(11) NOT NULL,
  `BACreationDate` int(11) NOT NULL DEFAULT current_timestamp(),
  `BACurrentBalance` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bankaccount`
--

INSERT INTO `bankaccount` (`BankAccountID`, `BACreationDate`, `BACurrentBalance`, `CustomerID`) VALUES
(98806, 2147483647, 400, 1),
(115148, 2147483647, 1250, 3),
(174285, 2147483647, 1350, 2);

-- --------------------------------------------------------

--
-- Table structure for table `banktransaction`
--

CREATE TABLE `banktransaction` (
  `BankTransactionID` int(11) NOT NULL,
  `BTCreationDate` int(11) NOT NULL DEFAULT current_timestamp(),
  `BTAmount` int(11) NOT NULL,
  `BTFromAccount` int(11) NOT NULL,
  `BTToAccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banktransaction`
--

INSERT INTO `banktransaction` (`BankTransactionID`, `BTCreationDate`, `BTAmount`, `BTFromAccount`, `BTToAccount`) VALUES
(536, 1668039341, 500, 98806, 115148),
(543, 1668043166, 100, 98806, 174285),
(547, 1668059047, 250, 115148, 174285);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerPassword` varchar(20) NOT NULL,
  `CustomerName` varchar(100) NOT NULL,
  `CustomerAddress` varchar(100) NOT NULL,
  `CustomerMobile` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerPassword`, `CustomerName`, `CustomerAddress`, `CustomerMobile`) VALUES
(1, '1234', 'Mohamed Ahmed', 'Cairo', '01002244909'),
(2, '12345', 'Ibrahim Hassan', 'Cairo', '01003333444'),
(3, '123456', 'Diab', 'Giza', '01155522222');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bankaccount`
--
ALTER TABLE `bankaccount`
  ADD PRIMARY KEY (`BankAccountID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `banktransaction`
--
ALTER TABLE `banktransaction`
  ADD PRIMARY KEY (`BankTransactionID`),
  ADD KEY `BTFromAccount` (`BTFromAccount`),
  ADD KEY `BTToAccount` (`BTToAccount`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banktransaction`
--
ALTER TABLE `banktransaction`
  MODIFY `BankTransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=548;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bankaccount`
--
ALTER TABLE `bankaccount`
  ADD CONSTRAINT `bankaccount_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `banktransaction`
--
ALTER TABLE `banktransaction`
  ADD CONSTRAINT `banktransaction_ibfk_1` FOREIGN KEY (`BTToAccount`) REFERENCES `bankaccount` (`BankAccountID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `banktransaction_ibfk_2` FOREIGN KEY (`BTFromAccount`) REFERENCES `bankaccount` (`BankAccountID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
