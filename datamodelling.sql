-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 23, 2018 at 01:25 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datamodelling`
--

-- --------------------------------------------------------

--
-- Table structure for table `dimcustomer`
--

CREATE TABLE `dimcustomer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerAltID` varchar(10) NOT NULL,
  `CustomerName` varchar(50) DEFAULT NULL,
  `Gender` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dimcustomer`
--

INSERT INTO `dimcustomer` (`CustomerID`, `CustomerAltID`, `CustomerName`, `Gender`) VALUES
(1, 'IMI-001', 'Henry Ford', 'M'),
(2, 'IMI-002', 'Bill Gates', 'M'),
(3, 'IMI-003', 'Muskan Shaikh', 'F'),
(4, 'IMI-004', 'Richard Thrubin', 'M'),
(5, 'IMI-005', 'Emma Wattson', 'F');

-- --------------------------------------------------------

--
-- Table structure for table `dimdate`
--

CREATE TABLE `dimdate` (
  `DateKey` int(11) NOT NULL,
  `DayOfMonth` varchar(2) DEFAULT NULL,
  `DayName` varchar(9) DEFAULT NULL,
  `MonthName` varchar(9) DEFAULT NULL,
  `Quarter` varchar(1) DEFAULT NULL,
  `Year` char(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dimdate`
--

INSERT INTO `dimdate` (`DateKey`, `DayOfMonth`, `DayName`, `MonthName`, `Quarter`, `Year`) VALUES
(20130101, '1', 'Tuesday', 'January', '1', '2013'),
(20130102, '2', 'Wednesday', 'January', '1', '2013'),
(20130103, '3', 'Thursday', 'January', '1', '2013'),
(20130104, '4', 'Friday', 'January', '1', '2013'),
(20130105, '5', 'Saturday', 'January', '1', '2013');

-- --------------------------------------------------------

--
-- Table structure for table `dimproduct`
--

CREATE TABLE `dimproduct` (
  `ProductKey` int(11) NOT NULL,
  `ProductAltKey` varchar(10) NOT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `ProductActualCost` int(11) DEFAULT NULL,
  `ProductSalesCost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dimproduct`
--

INSERT INTO `dimproduct` (`ProductKey`, `ProductAltKey`, `ProductName`, `ProductActualCost`, `ProductSalesCost`) VALUES
(1, 'ITM-001', 'Wheat Floor 1kg', 5, 6),
(2, 'ITM-002', 'Rice Grains 1kg', 22, 24),
(3, 'ITM-003', 'SunFlower Oil 1 ltr', 42, 43),
(4, 'ITM-004', 'Nirma Soap', 18, 20),
(5, 'ITM-005', 'Arial Washing Powder 1kg', 135, 139);

-- --------------------------------------------------------

--
-- Table structure for table `dimsalesperson`
--

CREATE TABLE `dimsalesperson` (
  `SalesPersonID` int(11) NOT NULL,
  `SalesPersonAltID` varchar(10) NOT NULL,
  `SalesPersonName` varchar(100) DEFAULT NULL,
  `storenumber` int(11) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dimsalesperson`
--

INSERT INTO `dimsalesperson` (`SalesPersonID`, `SalesPersonAltID`, `SalesPersonName`, `storenumber`, `City`, `State`, `Country`) VALUES
(1, 'SP-DMSPR1', 'Ashish', 1, 'Ahmedabad', 'Guj', 'India'),
(2, 'SP-DMSPR2', 'Ketan', 1, 'Ahmedabad', 'Guj', 'India'),
(3, 'SP-DMNGR1', 'Srinivas', 2, 'Ahmedabad', 'Guj', 'India'),
(4, 'SP-DMNGR2', 'Saad', 2, 'Ahmedabad', 'Guj', 'India'),
(5, 'SP-DMSVR1', 'Jasmin', 3, 'Ahmedabad', 'Guj', 'India'),
(6, 'SP-DMSVR2', 'Jacob', 3, 'Ahmedabad', 'Guj', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `dimstores`
--

CREATE TABLE `dimstores` (
  `StoreID` int(11) NOT NULL,
  `StoreAltID` varchar(10) NOT NULL,
  `StoreName` varchar(100) DEFAULT NULL,
  `StoreLocation` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dimstores`
--

INSERT INTO `dimstores` (`StoreID`, `StoreAltID`, `StoreName`, `StoreLocation`, `City`, `State`, `Country`) VALUES
(1, 'LOC-A1', 'X-Mart', 'S.P. RingRoad', 'Ahmedabad', 'Guj', 'India'),
(2, 'LOC-A2', 'X-Mart', 'Maninagar', 'Ahmedabad', 'Guj', 'India'),
(3, 'LOC-A3', 'X-Mart', 'Sivranjani', 'Ahmedabad', 'Guj', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `FactProductSales`
--

CREATE TABLE `FactTable` (
  `DateKey` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `SalesPersonID` int(11) NOT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `SalesTotalCost` int(11) DEFAULT NULL,
  `ProductActualCost` int(11) DEFAULT NULL,
  `Deviation` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `FactProductSales`
--

INSERT INTO `FactProductSales` (`TransactionId`, `DateKey`, `StoreID`, `CustomerID`, `ProductID`, `SalesPersonID`, `Quantity`, `SalesTotalCost`, `ProductActualCost`, `Deviation`) VALUES
(173766, 20130101, 1, 1, 1, 1, 2, 11, 13, 2),
(173767, 20130101, 1, 1, 2, 1, 1, 22, 24, 2),
(173768, 20130101, 1, 1, 3, 1, 1, 42, 43, 1),
(173769, 20130101, 1, 2, 3, 1, 1, 42, 43, 1),
(173770, 20130101, 1, 2, 4, 1, 3, 54, 60, 6),
(173771, 20130101, 1, 3, 2, 2, 2, 11, 13, 2),
(173772, 20130101, 1, 3, 3, 2, 1, 42, 43, 1),
(173773, 20130101, 1, 3, 4, 2, 3, 54, 60, 6),
(173774, 20130101, 1, 3, 5, 2, 1, 135, 139, 4),
(173775, 20130102, 1, 1, 1, 1, 2, 11, 13, 2),
(173776, 20130102, 1, 1, 2, 1, 1, 22, 24, 2),
(173777, 20130102, 1, 2, 3, 1, 1, 42, 43, 1),
(173778, 20130102, 1, 2, 4, 1, 3, 54, 60, 6),
(173779, 20130102, 1, 3, 2, 2, 2, 11, 13, 2),
(173780, 20130102, 1, 3, 5, 2, 1, 135, 139, 4),
(173781, 20130102, 2, 1, 4, 3, 3, 54, 60, 6),
(173782, 20130102, 2, 1, 5, 3, 1, 135, 139, 4),
(173783, 20130103, 1, 1, 3, 1, 2, 84, 87, 3),
(173784, 20130103, 1, 1, 4, 1, 3, 54, 60, 6),
(173785, 20130103, 1, 2, 1, 1, 1, 5, 6, 1),
(173786, 20130103, 1, 2, 2, 1, 1, 22, 24, 2),
(173787, 20130103, 1, 3, 1, 2, 2, 11, 13, 2),
(173788, 20130103, 1, 3, 4, 2, 3, 54, 60, 6),
(173789, 20130103, 2, 1, 2, 3, 1, 5, 6, 1),
(173790, 20130103, 2, 1, 3, 3, 1, 42, 43, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dimcustomer`
--
ALTER TABLE `dimcustomer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `dimdate`
--
ALTER TABLE `dimdate`
  ADD PRIMARY KEY (`DateKey`);

--
-- Indexes for table `dimproduct`
--
ALTER TABLE `dimproduct`
  ADD PRIMARY KEY (`ProductKey`);

--
-- Indexes for table `dimsalesperson`
--
ALTER TABLE `dimsalesperson`
  ADD PRIMARY KEY (`SalesPersonID`);

--
-- Indexes for table `dimstores`
--
ALTER TABLE `dimstores`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `FactProductSales`
--
ALTER TABLE `FactProductSales`
  ADD PRIMARY KEY (`TransactionId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dimcustomer`
--
ALTER TABLE `dimcustomer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dimproduct`
--
ALTER TABLE `dimproduct`
  MODIFY `ProductKey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dimsalesperson`
--
ALTER TABLE `dimsalesperson`
  MODIFY `SalesPersonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dimstores`
--
ALTER TABLE `dimstores`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `FactProductSales`
--
ALTER TABLE `FactProductSales`
  MODIFY `TransactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173791;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
