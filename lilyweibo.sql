/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : lilyweibo

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-01-23 01:04:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for friend
-- ----------------------------
DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `friend_sns_id` char(15) DEFAULT NULL COMMENT '好友微博id',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of friend
-- ----------------------------

-- ----------------------------
-- Table structure for scarf
-- ----------------------------
DROP TABLE IF EXISTS `scarf`;
CREATE TABLE `scarf` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `content` text COMMENT '微博内容',
  `style` tinyint(1) DEFAULT '1' COMMENT '微博风格(1,2,3)',
  `image` varchar(100) DEFAULT '' COMMENT '服务器端生成的文字和预设图片合并后的图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '微博状态（0：unaproved；1：approved；2：produced：3：deleted）',
  `rank` int(10) unsigned DEFAULT '0' COMMENT '当前内容的排名名次',
  `dmx_datetime` int(10) DEFAULT '0' COMMENT '最后一次大冒险时间',
  `dmx_count` tinyint(4) DEFAULT '0' COMMENT '大冒险次数',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of scarf
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sns_id` char(15) DEFAULT NULL COMMENT '微博用户id',
  `screen_name` varchar(30) DEFAULT NULL COMMENT '微博昵称',
  `avatar` varchar(150) DEFAULT '' COMMENT '微博头像',
  `reg_time` int(10) DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
