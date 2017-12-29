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
  `id` mediumint(200) unsigned NOT NULL AUTO_INCREMENT,
  `format-id` mediumint(200) unsigned NOT NULL DEFAULT '0',
  `image-id` mediumint(200) unsigned NOT NULL DEFAULT '0',
  `collection-id` mediumint(200) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uids` tinyblob,
  `organisation` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `emailings` tinyblob,
  `unixname` varchar(255) NOT NULL DEFAULT '',
  `major` int(4) unsigned NOT NULL DEFAULT '0',
  `minor` int(4) unsigned NOT NULL DEFAULT '0',
  `revision` int(4) unsigned NOT NULL DEFAULT '0',
  `subrevision` int(4) unsigned NOT NULL DEFAULT '0',
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
  `width` int(64) unsigned NOT NULL DEFAULT '0',
  `height` int(64) unsigned NOT NULL DEFAULT '0',
  `bytes` int(64) unsigned NOT NULL DEFAULT '0',
  `emails` int(20) unsigned NOT NULL DEFAULT '0',
  `converts` int(20) unsigned NOT NULL DEFAULT '0',
  `uploads` int(20) unsigned NOT NULL DEFAULT '0',
  `downloads` int(20) unsigned NOT NULL DEFAULT '0',
  `caching` int(20) unsigned NOT NULL DEFAULT '0',
  `emails_bytes` mediumint(40) unsigned NOT NULL DEFAULT '0',
  `converts_bytes` mediumint(40) unsigned NOT NULL DEFAULT '0',
  `uploads_bytes` mediumint(40) unsigned NOT NULL DEFAULT '0',
  `downloads_bytes` mediumint(40) unsigned NOT NULL DEFAULT '0',
  `caching_bytes` mediumint(40) unsigned NOT NULL DEFAULT '0',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  `updated` int(13) unsigned NOT NULL DEFAULT '0',
  `emailed` int(13) unsigned NOT NULL DEFAULT '0',
  `converted` int(13) unsigned NOT NULL DEFAULT '0',
  `uploaded` int(13) unsigned NOT NULL DEFAULT '0',
  `downloaded` int(13) unsigned NOT NULL DEFAULT '0',
  `cached` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `searching` (`format-id`,`image-id`,`unixname`(32)),
  KEY `indicies` (`major`,`minor`,`revision`,`subrevision`,`width`,`height`,`image-mime-type`(16)),
  KEY `chronometry` (`created`,`updated`,`emailed`,`converted`,`uploaded`,`downloaded`,`cached`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `originals`
--

LOCK TABLES `originals` WRITE;
/*!40000 ALTER TABLE `originals` DISABLE KEYS */;
INSERT INTO `originals` VALUES (1,138,1,0,1,NULL,'Chronolabs Cooperative','Dr. Simon Antony Roberts','simon@snails.email',NULL,'chronolabs-it',2,1,1,0,'chronolabs-it.png','PNG (Portable Network Graphics)','image/png','DirectClass','1178x1178+0+0','PixelsPerCentimeter','Undefined','sRGB','8-bit','8-bit','8-bit','8-bit',0,0,528199,0,9,1,2,14,0,162299,528199,86618,2803294,1514529339,0,0,1514529625,1514529339,1514529625,1514529625);
/*!40000 ALTER TABLE `originals` ENABLE KEYS */;
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
