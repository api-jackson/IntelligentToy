-- MySQL dump 10.13  Distrib 5.6.19, for Win32 (x86)
--
-- Host: localhost    Database: android
-- ------------------------------------------------------
-- Server version	5.6.19

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
-- Table structure for table `device_data`
--

DROP TABLE IF EXISTS `device_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data` (
  `DeviceId` char(10) NOT NULL COMMENT '设备ID，唯一标识不同设备的属性',
  `DeviceOwner` int(5) DEFAULT NULL COMMENT '外键，设备所有者的用户ID',
  PRIMARY KEY (`DeviceId`),
  KEY `DeviceOwner` (`DeviceOwner`),
  CONSTRAINT `device_data_ibfk_1` FOREIGN KEY (`DeviceOwner`) REFERENCES `user_data` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_data`
--

LOCK TABLES `device_data` WRITE;
/*!40000 ALTER TABLE `device_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_files`
--

DROP TABLE IF EXISTS `device_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_files` (
  `FilesName` char(20) NOT NULL COMMENT '设备中保存的文件名',
  `DeviceId` char(10) DEFAULT NULL COMMENT '外键，设备的ID',
  PRIMARY KEY (`FilesName`),
  KEY `DeviceId` (`DeviceId`),
  CONSTRAINT `device_files_ibfk_1` FOREIGN KEY (`DeviceId`) REFERENCES `device_data` (`DeviceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_files`
--

LOCK TABLES `device_files` WRITE;
/*!40000 ALTER TABLE `device_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_data` (
  `UserId` int(5) NOT NULL COMMENT '用户ID，唯一标识不同用户的属性',
  `UserName` char(10) DEFAULT NULL COMMENT '用户名，用户的昵称',
  `WXUserId` varchar(255) DEFAULT NULL COMMENT '绑定的微信账号的ID',
  `Password` char(10) DEFAULT NULL COMMENT '使用微信登陆，暂不需要密码',
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_data`
--

LOCK TABLES `user_data` WRITE;
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_files`
--

DROP TABLE IF EXISTS `user_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_files` (
  `FilesName` char(20) NOT NULL COMMENT '文件名',
  `FilesPath` char(50) DEFAULT NULL COMMENT '文件保存的路径',
  `UserId` int(5) DEFAULT NULL COMMENT '外键，文件所有者的用户ID',
  PRIMARY KEY (`FilesName`),
  KEY `UserId` (`UserId`),
  CONSTRAINT `user_files_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user_data` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_files`
--

LOCK TABLES `user_files` WRITE;
/*!40000 ALTER TABLE `user_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weixin_token`
--

DROP TABLE IF EXISTS `weixin_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weixin_token` (
  `AppId` varchar(255) DEFAULT NULL,
  `AppSecret` varchar(255) DEFAULT NULL,
  `Access_Token` varchar(255) DEFAULT NULL,
  `addTimestamp` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weixin_token`
--

LOCK TABLES `weixin_token` WRITE;
/*!40000 ALTER TABLE `weixin_token` DISABLE KEYS */;
INSERT INTO `weixin_token` VALUES ('wx3309b26898b21a67','3130556e5ef4925f1474af1538929867','wRfh5sB_NMoILVZTbF7J9iff9JD0A-e1EDIN7bsdr7OKg71l06hiCIv6BFW9JrGc3cIHi7qx0mquiaPSKF7ZK7e71rNDpGzmmyaBrArO_JE',1439285064,7200);
/*!40000 ALTER TABLE `weixin_token` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-11 21:48:39
