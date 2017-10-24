/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : mythinkphp

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-10-24 17:31:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for my_admin
-- ----------------------------
DROP TABLE IF EXISTS `my_admin`;
CREATE TABLE `my_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_username` varchar(50) DEFAULT NULL,
  `admin_password` varchar(50) DEFAULT NULL,
  `addtime` int(11) DEFAULT '0',
  `is_show` int(11) DEFAULT '0',
  `isdel` int(11) DEFAULT '0',
  `role_id` int(11) DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `subcompany_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_admin
-- ----------------------------
INSERT INTO `my_admin` VALUES ('1', 'admin', '4f9d4202c12cdee87dee8d7fbce2cc3e', '1424448000', '1', '0', '8', null, null);

-- ----------------------------
-- Table structure for my_category
-- ----------------------------
DROP TABLE IF EXISTS `my_category`;
CREATE TABLE `my_category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(30) NOT NULL DEFAULT '',
  `cat_title` varchar(200) DEFAULT NULL,
  `cat_keyword` varchar(200) DEFAULT NULL,
  `cat_description` text,
  `cat_content` varchar(255) DEFAULT '',
  `cat_order` int(11) DEFAULT '0' COMMENT '顺序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1 显示 0隐藏',
  `cat_addtime` int(11) DEFAULT '0',
  `company_id` int(11) DEFAULT '0' COMMENT '总公司',
  `subcompany_id` int(11) DEFAULT '0' COMMENT '子公司',
  `root_id` int(11) DEFAULT '0',
  `cat_path` varchar(200) DEFAULT NULL,
  `isdel` int(11) DEFAULT '0' COMMENT '1删除 0正常',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_category
-- ----------------------------

-- ----------------------------
-- Table structure for my_company
-- ----------------------------
DROP TABLE IF EXISTS `my_company`;
CREATE TABLE `my_company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `companyname` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `model_id` int(11) DEFAULT '0' COMMENT '模板ID',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`company_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_company
-- ----------------------------
INSERT INTO `my_company` VALUES ('1', '公司名称', null, '', null, '0', '0');

-- ----------------------------
-- Table structure for my_goods
-- ----------------------------
DROP TABLE IF EXISTS `my_goods`;
CREATE TABLE `my_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_sn` varchar(50) DEFAULT NULL COMMENT '商品编号',
  `goods_name` varchar(100) DEFAULT NULL,
  `goods_num` int(11) DEFAULT '0',
  `goods_price` decimal(10,2) DEFAULT '0.00',
  `goods_img` varchar(100) DEFAULT NULL,
  `company_id` int(11) DEFAULT '0',
  `subcompany_id` int(11) DEFAULT '0',
  `is_show` int(11) DEFAULT '0',
  `cat_id` int(11) DEFAULT '0',
  `goods_tj` int(11) DEFAULT '0',
  `isdel` int(11) DEFAULT '0',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_goods
-- ----------------------------
INSERT INTO `my_goods` VALUES ('1', null, 'HTC', '20', '50.00', 'goods/20171024/dog.jpg', '1', '1', '1', '0', '0', '0');
INSERT INTO `my_goods` VALUES ('2', null, 'Apple', '10', '120.00', 'goods/20171024/dog2.jpg', '1', '1', '1', '0', '0', '0');
INSERT INTO `my_goods` VALUES ('3', null, 'HuaWei', '15', '80.00', 'goods/20171024/goods/20171024/Monkey.jpg', '1', '2', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for my_goods_input
-- ----------------------------
DROP TABLE IF EXISTS `my_goods_input`;
CREATE TABLE `my_goods_input` (
  `id` int(11) NOT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `inum` int(11) DEFAULT '0' COMMENT '入库数量',
  `compnay_id` int(11) DEFAULT '0' COMMENT '所属公司',
  `subcompany_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_goods_input
-- ----------------------------

-- ----------------------------
-- Table structure for my_model
-- ----------------------------
DROP TABLE IF EXISTS `my_model`;
CREATE TABLE `my_model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(100) DEFAULT NULL,
  `model_order` int(11) DEFAULT '0' COMMENT '顺序',
  `is_show` int(11) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_model
-- ----------------------------

-- ----------------------------
-- Table structure for my_orders
-- ----------------------------
DROP TABLE IF EXISTS `my_orders`;
CREATE TABLE `my_orders` (
  `order_id` int(11) NOT NULL,
  `sum_goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `company_id` int(11) DEFAULT '0' COMMENT '所属公司',
  `subcompany_id` int(11) DEFAULT '0' COMMENT '子公司',
  `no_num` int(11) DEFAULT '0' COMMENT '桌号',
  `addtime` int(11) DEFAULT '0' COMMENT '下单时间',
  `status` int(11) DEFAULT '0' COMMENT '2 订单成功 1取消的订单 0未开始',
  `endtime` int(11) DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_orders
-- ----------------------------

-- ----------------------------
-- Table structure for my_orders_goods
-- ----------------------------
DROP TABLE IF EXISTS `my_orders_goods`;
CREATE TABLE `my_orders_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT '0',
  `goods_name` varchar(100) DEFAULT NULL,
  `goods_price` decimal(10,2) DEFAULT '0.00',
  `company_id` int(11) DEFAULT '0' COMMENT '所属公司',
  `subcompany_id` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '状态 0未做 1开始做 2完成准备派送 3 送达 4取消 5更换',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_orders_goods
-- ----------------------------

-- ----------------------------
-- Table structure for my_subcompany
-- ----------------------------
DROP TABLE IF EXISTS `my_subcompany`;
CREATE TABLE `my_subcompany` (
  `subcompany_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL COMMENT '总公司ID',
  `subcompany_name` varchar(100) DEFAULT NULL COMMENT '子公司店名',
  `subcompany_address` varchar(100) DEFAULT NULL,
  `subcompany_telphone` varchar(20) DEFAULT NULL,
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`subcompany_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_subcompany
-- ----------------------------
INSERT INTO `my_subcompany` VALUES ('1', '1', '浦东分公司', null, null, '0');
INSERT INTO `my_subcompany` VALUES ('2', '1', '浦西分公司', null, null, '0');

-- ----------------------------
-- Table structure for my_users
-- ----------------------------
DROP TABLE IF EXISTS `my_users`;
CREATE TABLE `my_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `truename` varchar(20) DEFAULT NULL,
  `islock` int(11) DEFAULT '0',
  `company_id` int(11) DEFAULT '0',
  `subcompany_id` int(11) DEFAULT '0',
  `groupid` int(11) DEFAULT '0' COMMENT '所属组ID',
  `
balance` double(11,2) DEFAULT '0.00' COMMENT '余额',
  `mobile` varchar(15) DEFAULT NULL COMMENT '电话号码',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_users
-- ----------------------------

-- ----------------------------
-- Table structure for my_usersgroup
-- ----------------------------
DROP TABLE IF EXISTS `my_usersgroup`;
CREATE TABLE `my_usersgroup` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(30) DEFAULT NULL COMMENT '会员组名',
  `company_id` int(11) DEFAULT '0' COMMENT '主公司',
  `subcompany_id` int(11) DEFAULT '0' COMMENT '子公司',
  `discount` double(11,2) DEFAULT '1.00' COMMENT '折扣',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of my_usersgroup
-- ----------------------------
