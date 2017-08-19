/*
Navicat MySQL Data Transfer

Source Server         : 本地CentOS7
Source Server Version : 50718
Source Host           : 192.168.199.217:3306
Source Database       : packagesTest

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2017-08-19 17:16:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auth_action`
-- ----------------------------
DROP TABLE IF EXISTS `auth_action`;
CREATE TABLE `auth_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作名称',
  `namespace` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '命名空间',
  `controller` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '控制器',
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '请求路由',
  `action` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作方法',
  `pid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `left_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否在左侧菜单栏显示：1不显示，2显示',
  `del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '回收站功能：0未删除，1已删除，默认0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='操作表';

-- ----------------------------
-- Records of auth_action
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_admin`
-- ----------------------------
DROP TABLE IF EXISTS `auth_admin`;
CREATE TABLE `auth_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员名称',
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员表';

-- ----------------------------
-- Records of auth_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_role`
-- ----------------------------
DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

-- ----------------------------
-- Records of auth_role
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_role_action`
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_action`;
CREATE TABLE `auth_role_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `action` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '操作ID',
  `del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '回收站：0未删除，1删除',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `del_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色、操作关联表';

-- ----------------------------
-- Records of auth_role_action
-- ----------------------------
