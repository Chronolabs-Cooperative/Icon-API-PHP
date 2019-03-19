-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: icons-localhost
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.17.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `collections` (
  `id` mediumint(200) NOT NULL,
  `pid` mediumint(200) NOT NULL,
  `uid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `uids` mediumblob,
  `oids` mediumblob,
  `organisation` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `emailings` tinyblob,
  `unixname` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `description` tinyblob,
  `originals` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `downloads` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `converts` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `emails` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `emailed` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `added` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `downloaded` int(13) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `pid`, `uid`, `uids`, `oids`, `organisation`, `name`, `email`, `emailings`, `unixname`, `title`, `description`, `originals`, `downloads`, `converts`, `emails`, `created`, `updated`, `emailed`, `added`, `downloaded`) VALUES
(1, 0, 1, 0x613a313a7b693a303b693a313b7d, 0x613a313a7b693a303b693a313b7d, 'Chronolabs Cooperative', 'Dr. Simon Antony Roberts', 'simonxaies@gmail.com', 0x613a313a7b693a303b693a313b7d, 'chronolabs-coop', 'Chronolabs Cooperative', '', 1, 0, 0, 0, 0, 0, 0, 0, 0);


LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-30  1:14:31
