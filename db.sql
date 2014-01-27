-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2014 at 03:09 PM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `lilyweibo`
--

-- --------------------------------------------------------

--
-- Table structure for table `dmx_log`
--

CREATE TABLE `dmx_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `dmx_datetime` int(11) DEFAULT '0' COMMENT '大冒险时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE `friend` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(16) NOT NULL COMMENT '用户id',
  `cid` int(11) DEFAULT NULL COMMENT '用户id',
  `friend_sns_uid` char(15) DEFAULT NULL COMMENT '好友微博id',
  `share_datetime` int(10) DEFAULT '0' COMMENT '分享时间',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scarf`
--

CREATE TABLE `scarf` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `content` text COMMENT '微博内容',
  `style` tinyint(1) DEFAULT '1' COMMENT '微博风格(1,2,3)',
  `image` varchar(100) DEFAULT '' COMMENT '服务器端生成的文字和预设图片合并后的图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '微博状态（0：unaproved；1：approved；2：printing；3：printed：4：deleted）',
  `add_datetime` int(10) DEFAULT '0' COMMENT '添加时间',
  `update_datetime` int(10) DEFAULT '0' COMMENT '更新时间',
  `rank` int(10) unsigned DEFAULT '0' COMMENT '当前内容的排名名次',
  PRIMARY KEY (`cid`),
  KEY `rank` (`rank`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `scarf`
--

INSERT INTO `scarf` (`cid`, `uid`, `content`, `style`, `image`, `status`, `add_datetime`, `update_datetime`, `rank`) VALUES
(1, 5, '要点是一个简单的方式与他人分享的片段和浆料。所有GISTs的Git仓库，所以他们会自动从版本，Git forkable可用。', 1, '', 1, 0, 0, 1),
(2, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 2),
(3, 5, '文字生成图片,PHP源码下载,红茶巴士文字生成图', 1, 'upload/1.jpg', 1, 1390681641, 0, 4),
(7, 5, '文字生成图片,PHP源码下载,红茶巴士文字生成图', 1, 'upload/1.jpg', 1, 1390681641, 0, 3),
(8, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 5),
(9, 5, '要点是一个简单的方式与他人分享的片段和浆料。所有GISTs的Git仓库，所以他们会自动从版本，Git forkable可用。', 1, '', 1, 0, 0, 6),
(10, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 7),
(11, 5, '要点是一个简单的方式与他人分享的片段和浆料。所有GISTs的Git仓库，所以他们会自动从版本，Git forkable可用。', 1, '', 1, 0, 0, 8),
(12, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 9),
(13, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 10),
(14, 5, '\r\n\r\n文章1984年6月26日出生于陕西省西安市，中国内地男演员、导演。2006年毕业于中央戏剧学院表演系。2004年参演', 2, '', 1, 0, 0, 11),
(15, 5, '文字生成图片,PHP源码下载,红茶巴士文字生成图', 1, 'upload/1.jpg', 1, 1390681641, 0, 12),
(16, 5, 'fdf', 1, '', 1, 0, 0, 13),
(17, 5, '山东省地方', 3, '/uploads/20140126/139075166597115.jpg', 1, 1390751672, 0, 14),
(18, 5, '双方的负担收费', 2, '/uploads/20140126/139075172266432.jpg', 1, 1390751724, 0, 15),
(19, 6, '似懂非懂说', 3, '/uploads/20140126/139075176364955.jpg', 1, 1390751764, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sns_uid` char(15) DEFAULT NULL COMMENT '微博用户id',
  `screen_name` varchar(30) DEFAULT NULL COMMENT '微博昵称',
  `avatar` varchar(150) DEFAULT '' COMMENT '微博头像',
  `access_token` varchar(15) DEFAULT '' COMMENT '微博token',
  `reg_datetime` int(10) DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `sns_uid`, `screen_name`, `avatar`, `access_token`, `reg_datetime`) VALUES
(1, 'sdfsdfsf', '我的最爱', '', '', 0),
(2, 'dsdfisfrsw', '你是我的菜', '', '', 0),
(5, '1682949923', '记忆是一杯茶', 'http://tp4.sinaimg.cn/1682949923/180/5632248604/1', '2.00bJUtpB0HO7p', 0),
(6, '3164070184', 'fuel-it-up', 'http://tp1.sinaimg.cn/3164070184/180/40026419140/1', '2.00EMHI9D5L_fM', 0);
