/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : spruchuniverse

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-10 23:57:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `clients`
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(255) NOT NULL,
  `liked` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of clients
-- ----------------------------

-- ----------------------------
-- Table structure for `quotes`
-- ----------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(122) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of quotes
-- ----------------------------
INSERT INTO `quotes` VALUES ('1', 'Niko', 'Macht einen Test', '1460815727', '4');
INSERT INTO `quotes` VALUES ('10', 'Milos', 'Alles in Butter', '1515624796', '0');
INSERT INTO `quotes` VALUES ('11', 'Spr&uuml;cheklopfer', 'Weil die Kl&uuml;geren nachgeben, regieren die Dummen die Welt', '1515624823', '0');
