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
-- Table structure for table `originals`
--

DROP TABLE IF EXISTS `originals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `originals` (
  `id` mediumint(200) UNSIGNED NOT NULL,
  `format-id` mediumint(200) UNSIGNED NOT NULL DEFAULT '0',
  `image-id` mediumint(200) UNSIGNED NOT NULL DEFAULT '0',
  `collection-id` mediumint(200) UNSIGNED NOT NULL DEFAULT '0',
  `uid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `uids` tinyblob,
  `organisation` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `emailings` tinyblob,
  `unixname` varchar(255) NOT NULL DEFAULT '',
  `major` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `minor` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `revision` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `subrevision` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `image-format` varchar(255) NOT NULL DEFAULT '',
  `image-mime-type` varchar(64) NOT NULL DEFAULT '',
  `image-class` varchar(48) NOT NULL DEFAULT '',
  `image-geometry` varchar(48) NOT NULL DEFAULT '',
  `image-units` varchar(48) NOT NULL DEFAULT '',
  `image-endianess` varchar(48) NOT NULL DEFAULT '',
  `image-colorspace` varchar(48) NOT NULL DEFAULT '',
  `image-depth` varchar(48) NOT NULL DEFAULT '',
  `image-channel-depth-red` varchar(48) NOT NULL DEFAULT '',
  `image-channel-depth-green` varchar(48) NOT NULL DEFAULT '',
  `image-channel-depth-blue` varchar(48) NOT NULL DEFAULT '',
  `width` int(64) UNSIGNED NOT NULL DEFAULT '0',
  `height` int(64) UNSIGNED NOT NULL DEFAULT '0',
  `bytes` int(64) UNSIGNED NOT NULL DEFAULT '0',
  `emails` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `converts` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `uploads` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `downloads` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `caching` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `emails_bytes` mediumint(40) UNSIGNED NOT NULL DEFAULT '0',
  `converts_bytes` mediumint(40) UNSIGNED NOT NULL DEFAULT '0',
  `uploads_bytes` mediumint(40) UNSIGNED NOT NULL DEFAULT '0',
  `downloads_bytes` mediumint(40) UNSIGNED NOT NULL DEFAULT '0',
  `caching_bytes` mediumint(40) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `emailed` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `converted` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `uploaded` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `downloaded` int(13) UNSIGNED NOT NULL DEFAULT '0',
  `cached` int(13) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `icon__originals`
--

INSERT INTO `originals` (`id`, `format-id`, `image-id`, `collection-id`, `uid`, `uids`, `organisation`, `name`, `email`, `emailings`, `unixname`, `major`, `minor`, `revision`, `subrevision`, `image`, `image-format`, `image-mime-type`, `image-class`, `image-geometry`, `image-units`, `image-endianess`, `image-colorspace`, `image-depth`, `image-channel-depth-red`, `image-channel-depth-green`, `image-channel-depth-blue`, `width`, `height`, `bytes`, `emails`, `converts`, `uploads`, `downloads`, `caching`, `emails_bytes`, `converts_bytes`, `uploads_bytes`, `downloads_bytes`, `caching_bytes`, `created`, `updated`, `emailed`, `converted`, `uploaded`, `downloaded`, `cached`) VALUES
(1, 3514, 1, 1, 1, NULL, 'Chronolabs Cooperative', 'Dr. Simon Antony Roberts', 'simon@snails.email', NULL, 'chronolabs-it', 2, 1, 1, 0, 'chronolabs-it.png', 'PNG (Portable Network Graphics)', 'image/png', 'DirectClass', '1178x1178+0+0', 'PixelsPerCentimeter', 'Undefined', 'sRGB', '8-bit', '8-bit', '8-bit', '8-bit', 1178, 1178, 528199, 0, 33, 1, 843, 63, 0, 883870, 528199, 16777211, 16729840, 1514529339, 0, 0, 1552980042, 1514529339, 1553002232, 1552980042);
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
