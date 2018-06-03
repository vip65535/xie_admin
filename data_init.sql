# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.20)
# Database: xly_admin
# Generation Time: 2018-06-03 12:14:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `department` varchar(200) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态值:1-normal-正常,2-delete-删除',
  `sex` int(11) DEFAULT NULL COMMENT '性别:1-man-男,2-women-女',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO `admin` (`id`, `user_name`, `nickname`, `department`, `status`, `sex`, `created_at`, `updated_at`)
VALUES
	(1,'vip65535','管理员','开发',1,2,NULL,'2017-12-09 22:34:45');

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_log`;

CREATE TABLE `admin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员',
  `method` varchar(50) NOT NULL DEFAULT '' COMMENT '访问类型',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '访问链接',
  `param` text NOT NULL COMMENT '请求数据',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员日志';



# Dump of table admin_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role`;

CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '管理员id',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `created_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;

INSERT INTO `admin_role` (`id`, `admin_id`, `role_id`, `created_at`, `updated_at`)
VALUES
	(120,5,2,'2017-12-17 12:47:03','2017-12-17 12:47:03'),
	(123,7,1,'2017-12-17 12:47:15','2017-12-17 12:47:15'),
	(124,6,2,'2017-12-17 13:41:29','2017-12-17 13:41:29'),
	(130,1,1,'2018-05-31 23:20:04','2018-05-31 23:20:04'),
	(131,2,1,'2018-05-31 23:20:21','2018-05-31 23:20:21'),
	(138,3,1,'2018-05-31 23:21:40','2018-05-31 23:21:40'),
	(139,3,2,'2018-05-31 23:21:40','2018-05-31 23:21:40');

/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table functions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `functions`;

CREATE TABLE `functions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1为功能节点2为菜单',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父节点',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '功能名称',
  `icon_class` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `href` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限节点';

LOCK TABLES `functions` WRITE;
/*!40000 ALTER TABLE `functions` DISABLE KEYS */;

INSERT INTO `functions` (`id`, `sort`, `type`, `pid`, `name`, `icon_class`, `href`, `created_at`, `updated_at`)
VALUES
	(1,999,2,0,'后台首页','icon-zhuye','/index','2018-05-30 22:49:19','2018-05-30 22:49:19'),
	(2,998,2,0,'系统管理','icon-xitongpeizhi','','2017-12-19 22:30:09','2017-12-19 22:33:46'),
	(4,0,2,2,'权限节点','icon-xiaoximoban','/functions','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(6,0,1,4,'新增权限','icon-xitongcanshushezhi','/adminMenu/add','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(15,0,2,2,'管理员','icon-kehuguanli','/admin/lists','2018-05-31 22:51:36','2018-05-31 22:51:36'),
	(16,0,1,15,'新增权限','icon-dailihezuo','/admin/add','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(17,0,1,15,'删除权限','icon-dailihezuo','/admin/delete','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(18,0,1,15,'查询权限','icon-dailihezuo','/admin/lists','2018-05-31 22:51:41','2018-05-31 22:51:41'),
	(19,0,2,2,'角色权限','icon-zhaoshangdaili','/role/lists','2018-05-31 22:51:45','2018-05-31 22:51:45'),
	(275,0,2,2,'管理员日志','icon-xuqiudengji','/adminLog/lists','2018-05-31 22:54:08','2018-05-31 22:54:08'),
	(276,0,1,275,'新增权限','','/adminLog/add','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(277,0,1,275,'删除权限','','/adminLog/delete','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(278,0,1,275,'查询权限','','/adminLog/lists','2018-05-31 22:54:14','2018-05-31 22:54:14'),
	(279,0,1,275,'编辑权限','','/adminLog/edit','2018-01-20 18:41:14','2018-01-20 18:41:14'),
	(280,0,1,275,'查看权限','','/adminLog/show','2018-01-20 18:41:14','2018-01-20 18:41:14');

/*!40000 ALTER TABLE `functions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `created_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'系统管理员','2017-12-11 23:02:03','2017-12-11 23:02:03'),
	(2,'管理员','2017-12-11 23:02:26','2017-12-11 23:02:26');

/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table role_functions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_functions`;

CREATE TABLE `role_functions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL COMMENT '角色id',
  `functions_id` int(11) DEFAULT NULL COMMENT '权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `role_functions` WRITE;
/*!40000 ALTER TABLE `role_functions` DISABLE KEYS */;

INSERT INTO `role_functions` (`id`, `role_id`, `functions_id`, `created_at`, `updated_at`)
VALUES
	(191,2,1,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(192,2,2,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(193,2,3,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(194,2,4,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(195,2,6,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(196,2,15,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(197,2,16,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(198,2,17,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(199,2,18,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(200,2,19,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(201,2,275,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(202,2,276,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(203,2,277,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(204,2,278,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(205,2,279,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(206,2,280,'2018-03-08 21:31:30','2018-03-08 21:31:30'),
	(209,1,3,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(210,1,1,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(211,1,4,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(212,1,2,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(213,1,6,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(214,1,3,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(215,1,15,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(216,1,4,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(217,1,16,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(218,1,6,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(219,1,17,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(220,1,15,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(221,1,18,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(222,1,16,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(223,1,19,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(224,1,17,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(225,1,149,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(226,1,18,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(227,1,150,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(228,1,19,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(229,1,151,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(230,1,149,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(231,1,152,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(232,1,150,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(233,1,153,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(234,1,151,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(235,1,154,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(236,1,152,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(237,1,185,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(238,1,153,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(239,1,186,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(240,1,154,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(241,1,187,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(242,1,185,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(243,1,188,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(244,1,186,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(245,1,189,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(246,1,187,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(247,1,190,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(248,1,188,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(249,1,189,'2018-03-08 21:31:44','2018-03-08 21:31:44'),
	(250,1,190,'2018-03-08 21:31:44','2018-03-08 21:31:44');

/*!40000 ALTER TABLE `role_functions` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
