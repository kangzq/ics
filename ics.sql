-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: ics
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

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
-- Current Database: `ics`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ics` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ics`;

--
-- Table structure for table `in_orders`
--

DROP TABLE IF EXISTS `in_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `in_orders` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `no` varchar(10) DEFAULT NULL,
  `created` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_no` (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `in_orders`
--

LOCK TABLES `in_orders` WRITE;
/*!40000 ALTER TABLE `in_orders` DISABLE KEYS */;
INSERT INTO `in_orders` VALUES (6,'16030100',1456826210),(7,'16030101',1456826255),(8,'16030300',1457008888);
/*!40000 ALTER TABLE `in_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `out_order_detail`
--

DROP TABLE IF EXISTS `out_order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `out_order_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` mediumint(8) DEFAULT NULL COMMENT 'out_orders.id',
  `item_id` int(10) DEFAULT NULL COMMENT 'storage.id',
  `quantity` tinyint(6) DEFAULT NULL COMMENT '出货数量',
  `item_from` varchar(32) DEFAULT NULL,
  `item_to` varchar(32) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `out_order_detail`
--

LOCK TABLES `out_order_detail` WRITE;
/*!40000 ALTER TABLE `out_order_detail` DISABLE KEYS */;
INSERT INTO `out_order_detail` VALUES (2,7,14,2,'Hua','AMZ','PACK'),(3,7,12,6,'QCS','AMZ','ok'),(9,15,14,4,'hua','qqq',''),(10,15,16,6,'hua','qqq','');
/*!40000 ALTER TABLE `out_order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `out_orders`
--

DROP TABLE IF EXISTS `out_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `out_orders` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `no` varchar(10) DEFAULT NULL,
  `created` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_no` (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `out_orders`
--

LOCK TABLES `out_orders` WRITE;
/*!40000 ALTER TABLE `out_orders` DISABLE KEYS */;
INSERT INTO `out_orders` VALUES (7,'160303001',1457006506),(15,'160303002',1457008919);
/*!40000 ALTER TABLE `out_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage`
--

DROP TABLE IF EXISTS `storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` mediumint(8) NOT NULL COMMENT 'ref in_orders.id',
  `item_sku` varchar(64) NOT NULL,
  `item_left` mediumint(8) DEFAULT NULL COMMENT 'boxes left',
  `pallet_id` tinyint(4) DEFAULT NULL COMMENT '拖板编号',
  `is_packed` tinyint(1) DEFAULT '1' COMMENT '是否打板装箱',
  `box_num` tinyint(6) DEFAULT NULL COMMENT '箱数',
  `box_id` varchar(16) DEFAULT NULL COMMENT '箱号',
  `box_capacity` tinyint(4) DEFAULT NULL COMMENT '每箱个数',
  `item_quantity` mediumint(8) DEFAULT NULL COMMENT 'PCS',
  `created` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_sku_idx` (`item_sku`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage`
--

LOCK TABLES `storage` WRITE;
/*!40000 ALTER TABLE `storage` DISABLE KEYS */;
INSERT INTO `storage` VALUES (12,6,'SC0139284',14,1,1,20,'1-20',40,800,1456826210),(13,6,'SC0239284',15,2,1,15,'1-15',40,600,1456826210),(14,7,'AX012',0,1,1,10,'1-10',100,1000,1456826255),(15,7,'AX013',15,2,1,15,'1-15',80,1200,1456826255),(16,8,'ax012',4,1,1,10,'1-10',40,400,1457008888);
/*!40000 ALTER TABLE `storage` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-07 22:08:36
