/*
SQLyog Ultimate v12.08 (64 bit)
MySQL - 8.0.41-0ubuntu0.24.04.1 : Database - webman
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `sys_config` */

CREATE TABLE `sys_config` (
  `config_id` int NOT NULL AUTO_INCREMENT COMMENT '参数主键',
  `config_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '参数名称',
  `config_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '参数键名',
  `config_value` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '参数键值',
  `config_type` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N' COMMENT '系统内置（Y是 N否）',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='参数配置表';

/*Data for the table `sys_config` */

insert  into `sys_config`(`config_id`,`config_name`,`config_key`,`config_value`,`config_type`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'主框架页-默认皮肤样式名称','sys.index.skinName','skin-blue','Y',1,'2025-03-15 16:07:06',1,'2025-04-23 12:09:06','蓝色 skin-blue、绿色 skin-green、紫色 skin-purple、红色 skin-red、黄色 skin-yellow'),(3,'主框架页-侧边栏主题','sys.index.sideTheme','theme-dark','Y',1,'2025-03-15 16:07:06',105,'2025-04-18 13:51:59','深色主题theme-dark，浅色主题theme-light'),(108,'用户管理-账号初始密码','sys.user.initPassword','123456','Y',1,'2025-04-15 15:38:32',1,'2025-04-15 15:38:37',NULL),(109,'账号自助-验证码开关','sys.account.captchaEnabled','true','Y',1,'2025-04-15 15:43:11',105,'2025-04-18 13:51:54','是否开启验证码功能（true开启，false关闭）'),(110,'演示模式','sys.demo.mode','false','Y',1,'2025-04-16 13:54:16',1,'2025-04-23 16:01:34','是否开启演示模式（true开启，false关闭）');

/*Table structure for table `sys_dept` */

CREATE TABLE `sys_dept` (
  `dept_id` bigint NOT NULL AUTO_INCREMENT COMMENT '部门id',
  `parent_id` bigint DEFAULT '0' COMMENT '父部门id',
  `ancestors` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '祖级列表',
  `dept_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '部门名称',
  `order_num` int DEFAULT '0' COMMENT '显示顺序',
  `leader` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '负责人',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '联系电话',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '部门状态（1正常 0停用）',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部门表';

/*Data for the table `sys_dept` */

insert  into `sys_dept`(`dept_id`,`parent_id`,`ancestors`,`dept_name`,`order_num`,`leader`,`phone`,`email`,`status`,`delete_time`,`create_by`,`create_time`,`update_by`,`update_time`) values (100,0,'0','若依科技',0,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',1,'2025-04-16 23:53:53'),(101,100,'0,100','深圳总公司',1,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',1,'2025-04-16 23:53:52'),(102,100,'0,100','长沙分公司',2,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-16 23:53:53'),(103,101,'0,100,101','研发部门',1,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',1,'2025-04-22 22:11:17'),(104,101,'0,100,101','市场部门',2,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',105,'2025-04-21 08:50:21'),(105,101,'0,100,101','测试部门',3,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-16 23:53:53'),(106,101,'0,100,101','财务部门',4,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',105,'2025-04-20 20:46:28'),(107,101,'0,100,101','运维部门',5,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-16 23:53:53'),(108,102,'0,100,102','市场部门',1,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-16 23:53:53'),(109,102,'0,100,102','财务部门',2,'若依','15888888888','ry@qq.com','1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-16 23:53:53');

/*Table structure for table `sys_dict_data` */

CREATE TABLE `sys_dict_data` (
  `dict_code` bigint NOT NULL AUTO_INCREMENT COMMENT '字典编码',
  `dict_sort` int DEFAULT '0' COMMENT '字典排序',
  `dict_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '字典标签',
  `dict_value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '字典键值',
  `dict_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '字典类型',
  `css_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '样式属性（其他样式扩展）',
  `list_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '表格回显样式',
  `is_default` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N' COMMENT '是否默认（Y是 N否）',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '状态（1正常 0停用）',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`dict_code`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='字典数据表';

/*Data for the table `sys_dict_data` */

insert  into `sys_dict_data`(`dict_code`,`dict_sort`,`dict_label`,`dict_value`,`dict_type`,`css_class`,`list_class`,`is_default`,`status`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (2,2,'女','1','sys_user_sex','','','N','1',1,'2025-03-15 16:07:06',0,NULL,'性别女'),(3,3,'未知','2','sys_user_sex','','','N','1',1,'2025-03-15 16:07:06',0,NULL,'性别未知'),(4,1,'显示','1','sys_show_hide','','primary','Y','1',1,'2025-03-15 16:07:06',0,NULL,'显示菜单'),(5,2,'隐藏','0','sys_show_hide','','danger','N','1',1,'2025-03-15 16:07:06',0,NULL,'隐藏菜单'),(6,1,'正常','1','sys_normal_disable','','primary','Y','1',1,'2025-03-15 16:07:06',0,NULL,'正常状态'),(7,2,'停用','0','sys_normal_disable','','danger','N','1',1,'2025-03-15 16:07:06',0,NULL,'停用状态'),(12,1,'是','Y','sys_yes_no','','primary','Y','1',1,'2025-03-15 16:07:06',0,NULL,'系统默认是'),(13,2,'否','N','sys_yes_no','','danger','N','1',1,'2025-03-15 16:07:06',0,NULL,'系统默认否'),(28,1,'成功','1','sys_common_status','','primary','N','1',1,'2025-03-15 16:07:06',0,NULL,'正常状态'),(29,2,'失败','0','sys_common_status','','danger','N','1',1,'2025-03-15 16:07:06',0,NULL,'停用状态'),(108,1,'男','0','sys_user_sex',NULL,'default','N','1',1,'2025-03-19 09:56:26',1,'2025-03-19 10:01:06','性别男'),(110,0,'是','1','product_recommend',NULL,'success','N','1',1,'2025-03-30 14:41:03',0,'2025-03-30 14:41:03',NULL),(111,1,'否','0','product_recommend',NULL,'info','N','1',1,'2025-03-30 14:41:21',1,'2025-03-30 14:41:35',NULL),(117,1,'正常','0','sys_notice_status',NULL,'primary','N','1',1,'2025-04-16 10:45:22',0,'2025-04-16 10:45:22','正常状态'),(118,2,'关闭','1','sys_notice_status',NULL,'danger','N','1',1,'2025-04-16 10:45:48',0,'2025-04-16 10:45:48','关闭状态'),(119,1,'通知','1','sys_notice_type',NULL,'warning','N','1',1,'2025-04-16 10:47:05',1,'2025-04-16 10:47:41','通知'),(120,2,'公告','2','sys_notice_type',NULL,'success','N','1',1,'2025-04-16 10:47:24',1,'2025-04-16 10:47:38','公告');

/*Table structure for table `sys_dict_type` */

CREATE TABLE `sys_dict_type` (
  `dict_id` bigint NOT NULL AUTO_INCREMENT COMMENT '字典主键',
  `dict_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '字典名称',
  `dict_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '字典类型',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '状态（1正常 0停用）',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`dict_id`),
  UNIQUE KEY `dict_type` (`dict_type`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='字典类型表';

/*Data for the table `sys_dict_type` */

insert  into `sys_dict_type`(`dict_id`,`dict_name`,`dict_type`,`status`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'用户性别','sys_user_sex','1',1,'2025-03-15 16:07:06',1,'2025-03-22 09:58:30','用户性别列表'),(2,'菜单状态','sys_show_hide','1',1,'2025-03-15 16:07:06',0,NULL,'菜单状态列表'),(3,'系统开关','sys_normal_disable','1',1,'2025-03-15 16:07:06',0,NULL,'系统开关列表'),(6,'系统是否','sys_yes_no','1',1,'2025-03-15 16:07:06',1,'2025-03-23 14:46:50','系统是否列表'),(10,'系统状态','sys_common_status','1',1,'2025-03-15 16:07:06',0,NULL,'登录状态列表'),(104,'推荐状态','product_recommend','1',1,'2025-03-30 14:40:16',1,'2025-03-30 14:40:39','推荐状态列表'),(107,'公告状态','sys_notice_status','1',1,'2025-04-16 10:43:56',0,'2025-04-16 10:43:56',NULL),(108,'通知类型','sys_notice_type','1',1,'2025-04-16 10:46:42',0,'2025-04-16 10:46:42','通知类型列表');

/*Table structure for table `sys_logininfor` */

CREATE TABLE `sys_logininfor` (
  `info_id` bigint NOT NULL AUTO_INCREMENT COMMENT '访问ID',
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户账号',
  `ipaddr` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录IP地址',
  `login_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录地点',
  `browser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '浏览器类型',
  `os` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作系统',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '登录状态（1成功 0失败）',
  `msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '提示消息',
  `login_time` datetime DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`info_id`),
  KEY `idx_sys_logininfor_s` (`status`),
  KEY `idx_sys_logininfor_lt` (`login_time`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统访问记录';

/*Data for the table `sys_logininfor` */

/*Table structure for table `sys_menu` */

CREATE TABLE `sys_menu` (
  `menu_id` bigint NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `menu_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单名称',
  `parent_id` bigint DEFAULT '0' COMMENT '父菜单ID',
  `order_num` int DEFAULT '0' COMMENT '显示顺序',
  `path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路由地址',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '组件路径',
  `query` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路由参数',
  `route_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路由名称',
  `is_frame` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '是否为外链（1是 0否）',
  `is_cache` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '是否缓存（1缓存 0不缓存）',
  `menu_type` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '菜单类型（M目录 C菜单 F按钮）',
  `visible` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '菜单状态（1显示 0隐藏）',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '菜单状态（1正常 0停用）',
  `perms` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '权限标识',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#' COMMENT '菜单图标',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2058 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单权限表';

/*Data for the table `sys_menu` */

insert  into `sys_menu`(`menu_id`,`menu_name`,`parent_id`,`order_num`,`path`,`component`,`query`,`route_name`,`is_frame`,`is_cache`,`menu_type`,`visible`,`status`,`perms`,`icon`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'系统管理',0,1,'system',NULL,'','','0','1','M','1','1','','system',1,'2025-03-15 16:07:05',1,'2025-03-22 09:58:24','系统管理目录'),(2,'系统监控',0,2,'monitor',NULL,'','','0','1','M','1','1','','monitor',1,'2025-03-15 16:07:05',1,'2025-04-17 00:09:36','系统监控目录'),(100,'用户管理',1,1,'user','system/user/index','','','0','1','C','1','1','system:user:list','user',1,'2025-03-15 16:07:05',1,'2025-04-26 10:17:18','用户管理菜单'),(101,'角色管理',1,2,'role','system/role/index','','','0','1','C','1','1','system:role:list','peoples',1,'2025-03-15 16:07:05',1,'2025-04-23 09:00:45','角色管理菜单'),(102,'菜单管理',1,3,'menu','system/menu/index','','','0','1','C','1','1','system:menu:list','tree-table',1,'2025-03-15 16:07:05',1,'2025-04-23 09:00:54','菜单管理菜单'),(103,'部门管理',1,4,'dept','system/dept/index','','','0','1','C','1','1','system:dept:list','tree',1,'2025-03-15 16:07:05',1,'2025-04-23 09:01:02','部门管理菜单'),(104,'岗位管理',1,5,'post','system/post/index','','','0','1','C','1','1','system:post:list','post',1,'2025-03-15 16:07:05',1,'2025-04-22 20:25:49','岗位管理菜单'),(105,'字典管理',1,6,'dictType','system/dict/index','','','0','1','C','1','1','system:dictType:list','dict',1,'2025-03-15 16:07:05',1,'2025-04-22 20:25:57','字典管理菜单'),(106,'参数设置',1,7,'config','system/config/index','','','0','1','C','1','1','system:config:list','edit',1,'2025-03-15 16:07:05',1,'2025-04-22 20:26:04','参数设置菜单'),(107,'通知公告',1,8,'notice','system/notice/index','','','0','1','C','1','1','system:notice:list','message',1,'2025-03-15 16:07:05',0,NULL,'通知公告菜单'),(108,'日志管理',2,9,'log','','','','0','1','M','1','1','','log',1,'2025-03-15 16:07:05',1,'2025-04-16 22:20:51','日志管理菜单'),(109,'在线用户',2,1,'online','monitor/online/index','','','0','1','C','1','1','monitor:online:list','online',1,'2025-03-15 16:07:05',1,'2025-04-24 22:25:26','在线用户菜单'),(114,'缓存列表',2,6,'cacheList','monitor/cache/list','','','0','1','C','1','1','monitor:cache:list','redis-list',1,'2025-03-15 16:07:05',1,'2025-04-23 12:14:28','缓存列表菜单'),(500,'操作日志',108,1,'operlog','monitor/operlog/index','','','0','1','C','1','1','monitor:operlog:list','form',1,'2025-03-15 16:07:05',1,'2025-04-16 22:21:18','操作日志菜单'),(501,'登录日志',108,2,'logininfor','monitor/logininfor/index','','','0','1','C','1','1','monitor:logininfor:list','logininfor',1,'2025-03-15 16:07:05',1,'2025-04-16 22:21:24','登录日志菜单'),(1000,'用户查询',100,1,'','','','','0','1','F','1','1','system:user:info','#',1,'2025-03-15 16:07:05',1,'2025-04-18 14:32:31',''),(1001,'用户新增',100,2,'','','','','0','1','F','1','1','system:user:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1002,'用户修改',100,3,'','','','','0','1','F','1','1','system:user:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1003,'用户删除',100,4,'','','','','0','1','F','1','1','system:user:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1006,'重置密码',100,7,'','','','','0','1','F','1','1','system:user:pwdEdit','#',1,'2025-03-15 16:07:05',1,'2025-04-19 09:26:55',''),(1007,'角色查询',101,1,'','','','','0','1','F','1','1','system:role:info','#',1,'2025-03-15 16:07:05',1,'2025-04-19 09:59:52',''),(1008,'角色新增',101,2,'','','','','0','1','F','1','1','system:role:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1009,'角色修改',101,3,'','','','','0','1','F','1','1','system:role:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1010,'角色删除',101,4,'','','','','0','1','F','1','1','system:role:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1012,'菜单查询',102,1,'','','','','0','1','F','1','1','system:menu:info','#',1,'2025-03-15 16:07:05',105,'2025-04-22 17:15:17',''),(1013,'菜单新增',102,2,'','','','','0','1','F','1','1','system:menu:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1014,'菜单修改',102,3,'','','','','0','1','F','1','1','system:menu:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1015,'菜单删除',102,4,'','','','','0','1','F','1','1','system:menu:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1016,'部门查询',103,1,'','','','','0','1','F','1','1','system:dept:info','#',1,'2025-03-15 16:07:05',1,'2025-04-18 14:26:43',''),(1017,'部门新增',103,2,'','','','','0','1','F','1','1','system:dept:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1018,'部门修改',103,3,'','','','','0','1','F','1','1','system:dept:edit','#',1,'2025-03-15 16:07:05',1,'2025-04-26 10:17:36',''),(1019,'部门删除',103,4,'','','','','0','1','F','1','1','system:dept:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1020,'岗位查询',104,1,'','','','','0','1','F','1','1','system:post:info','#',1,'2025-03-15 16:07:05',1,'2025-04-18 14:27:06',''),(1021,'岗位新增',104,2,'','','','','0','1','F','1','1','system:post:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1022,'岗位修改',104,3,'','','','','0','1','F','1','1','system:post:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1023,'岗位删除',104,4,'','','','','0','1','F','1','1','system:post:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1025,'字典查询',105,1,'','','','','0','1','F','1','1','system:dictType:info','#',1,'2025-03-15 16:07:05',1,'2025-04-22 20:22:22',''),(1026,'字典新增',105,2,'','','','','0','1','F','1','1','system:dictType:add','#',1,'2025-03-15 16:07:05',1,'2025-03-18 12:48:41',''),(1027,'字典修改',105,3,'','','','','0','1','F','1','1','system:dictType:edit','#',1,'2025-03-15 16:07:05',1,'2025-03-18 12:48:45',''),(1028,'字典删除',105,4,'','','','','0','1','F','1','1','system:dictType:remove','#',1,'2025-03-15 16:07:05',1,'2025-03-18 12:48:51',''),(1030,'参数查询',106,1,'','','','','0','1','F','1','1','system:config:info','#',1,'2025-03-15 16:07:05',1,'2025-04-18 13:51:16',''),(1031,'参数新增',106,2,'','','','','0','1','F','1','1','system:config:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1032,'参数修改',106,3,'','','','','0','1','F','1','1','system:config:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1033,'参数删除',106,4,'','','','','0','1','F','1','1','system:config:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1035,'公告查询',107,1,'','','','','0','1','F','1','1','system:notice:info','#',1,'2025-03-15 16:07:05',1,'2025-04-18 14:58:05',''),(1036,'公告新增',107,2,'','','','','0','1','F','1','1','system:notice:add','#',1,'2025-03-15 16:07:05',0,NULL,''),(1037,'公告修改',107,3,'','','','','0','1','F','1','1','system:notice:edit','#',1,'2025-03-15 16:07:05',0,NULL,''),(1038,'公告删除',107,4,'','','','','0','1','F','1','1','system:notice:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1040,'操作日志删除',500,2,'','','','','0','1','F','1','1','monitor:operlog:remove','#',1,'2025-03-15 16:07:05',1,'2025-04-18 09:56:13',''),(1041,'操作日志清空',500,3,'','','','','0','1','F','1','1','monitor:operlog:allRemove','#',1,'2025-03-15 16:07:05',1,'2025-04-18 09:56:27',''),(1043,'登录删除',501,2,'','','','','0','1','F','1','1','monitor:logininfor:remove','#',1,'2025-03-15 16:07:05',0,NULL,''),(1044,'日志清空',501,3,'','','','','0','1','F','1','1','monitor:logininfor:allRemove','#',1,'2025-03-15 16:07:05',1,'2025-04-18 16:13:46',''),(1045,'账户解锁',501,4,'','','','','0','1','F','1','1','monitor:logininfor:lockRemove','#',1,'2025-03-15 16:07:05',1,'2025-04-18 20:47:34',''),(1048,'登录状态强退',109,3,'','','','','0','1','F','1','1','monitor:online:loginStatusRemove','#',1,'2025-03-15 16:07:05',1,'2025-04-19 14:51:27',''),(2043,'修改数据权限',101,6,'',NULL,NULL,'','0','0','F','1','1','system:role:dataScopeEdit','#',1,'2025-04-17 11:54:37',0,'2025-04-17 11:54:37',''),(2045,'字典数据管理',105,6,'',NULL,NULL,'','0','0','F','1','1','system:dictData:manage','#',1,'2025-04-18 13:33:44',0,'2025-04-18 13:33:44',''),(2048,'公共权限管理',1,9,'common',NULL,NULL,'Common','0','0','C','0','1','system:common:list','swagger',1,'2025-04-18 15:06:08',1,'2025-04-21 08:28:52',''),(2049,'上传权限',2048,1,'',NULL,NULL,'','0','0','F','1','1','system:common:upload','#',1,'2025-04-18 15:07:15',0,'2025-04-18 15:07:15',''),(2055,'缓存管理',114,2,'',NULL,NULL,'','0','0','F','1','1','monitor:cache:manage','#',1,'2025-04-23 12:15:27',0,'2025-04-23 12:15:27',''),(2057,'公共查询权限',2048,2,'',NULL,NULL,'','0','0','F','1','1','system:common:query','#',1,'2025-04-23 12:44:36',0,'2025-04-23 12:44:36','');

/*Table structure for table `sys_notice` */

CREATE TABLE `sys_notice` (
  `notice_id` int NOT NULL AUTO_INCREMENT COMMENT '公告ID',
  `notice_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公告标题',
  `notice_type` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公告类型（1通知 2公告）',
  `notice_content` longblob COMMENT '公告内容',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '公告状态（1正常 0关闭）',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通知公告表';

/*Data for the table `sys_notice` */

insert  into `sys_notice`(`notice_id`,`notice_title`,`notice_type`,`notice_content`,`status`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'温馨提醒：2018-07-01 若依新版本发布啦','2','<p>新版本内容<img src=\"/upload/2025/04/18/eb732c72-030a-3570-a459-1312bc417f80.png\" alt=\"\" width=\"400\" height=\"240\"></p>','1',1,'2025-03-15 16:07:06',1,'2025-04-23 12:09:01','管理员'),(2,'维护通知：2018-07-01 若依系统凌晨维护','1','<p>维护内容1<img src=\"/upload/2025/04/16/1205748b-29cc-3175-972c-7786a76988ee.jpeg\" alt=\"\" width=\"200\" height=\"356\"></p>','1',1,'2025-03-15 16:07:06',1,'2025-04-16 10:57:11','管理员');

/*Table structure for table `sys_oper_log` */

CREATE TABLE `sys_oper_log` (
  `oper_id` bigint NOT NULL AUTO_INCREMENT COMMENT '日志主键',
  `module_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '功能名称',
  `action_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作名称',
  `accept_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作方法',
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '请求方式',
  `operator_type` int DEFAULT '0' COMMENT '操作类别（0其它 1后台用户 2手机端用户）',
  `user_id` bigint DEFAULT '0' COMMENT '操作人员',
  `oper_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '请求URL',
  `oper_ip` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '主机地址',
  `oper_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作地点',
  `oper_param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '请求参数',
  `json_result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '返回参数',
  `status` int DEFAULT '1' COMMENT '操作状态（1正常 0异常）',
  `error_msg` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '错误消息',
  `oper_time` datetime DEFAULT NULL COMMENT '操作时间',
  `cost_time` bigint DEFAULT '0' COMMENT '消耗时间',
  PRIMARY KEY (`oper_id`),
  KEY `idx_sys_oper_log_s` (`status`),
  KEY `idx_sys_oper_log_ot` (`oper_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志记录';

/*Data for the table `sys_oper_log` */

insert  into `sys_oper_log`(`oper_id`,`module_name`,`controller_name`,`action_name`,`accept_action`,`method`,`operator_type`,`user_id`,`oper_url`,`oper_ip`,`oper_location`,`oper_param`,`json_result`,`status`,`error_msg`,`oper_time`,`cost_time`) values (1149,'','操作日志管理','操作日志清空','plugin\\jmadmin\\app\\controller\\monitor\\OperLogController@allRemove','POST',1,1,'/app/jmadmin/monitor/operlog/allRemove','192.168.31.99','','{\"__timestamp\":1745813527664}','{\"code\":200,\"msg\":\"操作成功\",\"data\":[]}',1,'操作成功','2025-04-28 12:12:08',182);

/*Table structure for table `sys_post` */

CREATE TABLE `sys_post` (
  `post_id` bigint NOT NULL AUTO_INCREMENT COMMENT '岗位ID',
  `post_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '岗位编码',
  `post_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '岗位名称',
  `post_sort` int NOT NULL COMMENT '显示顺序',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '状态（1正常 0停用）',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='岗位信息表';

/*Data for the table `sys_post` */

insert  into `sys_post`(`post_id`,`post_code`,`post_name`,`post_sort`,`status`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'ceo','董事长',1,'1',1,'2025-03-15 16:07:05',1,'2025-04-23 20:52:26',''),(2,'se','项目经理',2,'1',1,'2025-03-15 16:07:05',1,'2025-03-22 13:52:07',''),(3,'hr','人力资源',3,'1',1,'2025-03-15 16:07:05',0,NULL,''),(4,'user','普通员工',4,'1',1,'2025-03-15 16:07:05',1,'2025-04-18 08:46:10','');

/*Table structure for table `sys_role` */

CREATE TABLE `sys_role` (
  `role_id` bigint NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `role_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色权限字符串',
  `role_sort` int NOT NULL COMMENT '显示顺序',
  `data_scope` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '数据范围（1：全部数据权限 2：自定数据权限 3：本部门数据权限 4：本部门及以下数据权限）',
  `menu_check_strictly` tinyint(1) DEFAULT '1' COMMENT '菜单树选择项是否关联显示',
  `dept_check_strictly` tinyint(1) DEFAULT '1' COMMENT '部门树选择项是否关联显示',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '角色状态（1正常 0停用）',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色信息表';

/*Data for the table `sys_role` */

insert  into `sys_role`(`role_id`,`role_name`,`role_key`,`role_sort`,`data_scope`,`menu_check_strictly`,`dept_check_strictly`,`status`,`delete_time`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,'超级管理员','admin',1,'1',1,1,'1',NULL,1,'2025-03-15 16:07:05',0,'2025-04-21 21:21:36','超级管理员'),(2,'普通角色','common',2,'2',1,1,'1',NULL,1,'2025-03-15 16:07:05',1,'2025-04-28 12:10:18','普通角色'),(100,'测试角色','test',2,'3',1,0,'1',NULL,1,'2025-04-15 09:19:38',1,'2025-04-28 12:10:25','测试角色1');

/*Table structure for table `sys_role_dept` */

CREATE TABLE `sys_role_dept` (
  `role_id` bigint NOT NULL COMMENT '角色ID',
  `dept_id` bigint NOT NULL COMMENT '部门ID',
  PRIMARY KEY (`role_id`,`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色和部门关联表';

/*Data for the table `sys_role_dept` */

insert  into `sys_role_dept`(`role_id`,`dept_id`) values (2,100),(2,101),(2,105),(101,100),(101,101),(101,103),(101,202),(101,203),(101,204),(101,205),(101,206),(102,100),(102,101),(102,103),(102,104),(102,105),(102,106),(102,107),(102,201);

/*Table structure for table `sys_role_menu` */

CREATE TABLE `sys_role_menu` (
  `role_id` bigint NOT NULL COMMENT '角色ID',
  `menu_id` bigint NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色和菜单关联表';

/*Data for the table `sys_role_menu` */

insert  into `sys_role_menu`(`role_id`,`menu_id`) values (2,1),(2,2),(2,100),(2,101),(2,102),(2,103),(2,104),(2,105),(2,106),(2,107),(2,108),(2,109),(2,114),(2,500),(2,501),(2,1000),(2,1001),(2,1002),(2,1003),(2,1006),(2,1007),(2,1008),(2,1009),(2,1010),(2,1012),(2,1013),(2,1014),(2,1015),(2,1020),(2,1021),(2,1022),(2,1023),(2,1025),(2,1026),(2,1027),(2,1028),(2,1030),(2,1031),(2,1032),(2,1033),(2,1035),(2,1036),(2,1037),(2,1038),(2,1040),(2,1041),(2,1043),(2,1044),(2,1045),(2,1048),(100,1),(100,2),(100,100),(100,101),(100,102),(100,103),(100,104),(100,105),(100,107),(100,108),(100,109),(100,114),(100,500),(100,501),(100,1000),(100,1001),(100,1002),(100,1003),(100,1006),(100,1007),(100,1008),(100,1009),(100,1010),(100,1012),(100,1013),(100,1014),(100,1016),(100,1017),(100,1018),(100,1019),(100,1020),(100,1021),(100,1022),(100,1023),(100,1025),(100,1026),(100,1027),(100,1028),(100,1035),(100,1040),(100,1043),(100,1044),(100,1045),(100,1048),(100,2043),(100,2045),(100,2048),(100,2049),(100,2057),(101,1),(101,2),(101,100),(101,101),(101,102),(101,109),(101,114),(101,1000),(101,1001),(101,1002),(101,1003),(101,1004),(101,1005),(101,1006),(101,1007),(101,1008),(101,1009),(101,1010),(101,1011),(101,1012),(101,1013),(101,1014),(101,1015),(101,1046),(101,1047),(101,1048),(102,1),(102,101),(102,102),(102,103),(102,104),(102,105),(102,106),(102,1007),(102,1008),(102,1009),(102,1010),(102,1012),(102,1013),(102,1014),(102,1015),(102,1016),(102,1017),(102,1018),(102,1019),(102,1020),(102,1021),(102,1022),(102,1023),(102,1025),(102,1026),(102,1027),(102,1028),(102,1030),(102,1031),(102,1032),(102,1033),(102,2043),(102,2045),(102,2048),(102,2049),(103,1),(103,2),(103,100),(103,102),(103,103),(103,109),(103,1000),(103,1001),(103,1002),(103,1003),(103,1006),(103,1012),(103,1013),(103,1014),(103,1015),(103,1016),(103,1017),(103,1018),(103,1019),(103,1046),(103,1048),(103,2046),(103,2047),(103,2048),(103,2049),(103,2051),(103,2052);

/*Table structure for table `sys_user` */

CREATE TABLE `sys_user` (
  `user_id` bigint NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `dept_id` bigint DEFAULT NULL COMMENT '部门ID',
  `user_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户账号',
  `nick_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户昵称',
  `user_type` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '00' COMMENT '用户类型（00系统用户）',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户邮箱',
  `phonenumber` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号码',
  `sex` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '用户性别（0男 1女 2未知）',
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像地址',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '帐号状态（1正常 0停用）',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  `login_ip` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '最后登录IP',
  `login_date` datetime DEFAULT NULL COMMENT '最后登录时间',
  `create_by` bigint DEFAULT '0' COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_by` bigint DEFAULT '0' COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户信息表';

/*Data for the table `sys_user` */

insert  into `sys_user`(`user_id`,`dept_id`,`user_name`,`nick_name`,`user_type`,`email`,`phonenumber`,`sex`,`avatar`,`password`,`status`,`delete_time`,`login_ip`,`login_date`,`create_by`,`create_time`,`update_by`,`update_time`,`remark`) values (1,103,'admin','若依21','00','ry@163.com','15888888881','1','/2025/04/19/dc89bd8b-6306-3c96-8aa0-826a63fb4d60.jpeg','$2y$10$JR2DoTJhNrpHfi4zMlp6DO6nFOwHlflaHNyyb8cxQg5b0mT.ivafS','1',NULL,'192.168.31.99','2025-04-28 11:29:52',1,'2025-03-15 16:07:05',1,'2025-04-28 11:29:52','管理员'),(2,101,'ry','若依','00','ry@qq.com','15666666666','1','','$2a$10$7JB720yubVSZvUI0rEqK/.VqGOZTH.ulu33dHOiBE8ByOhJIrdAu2','1',NULL,'127.0.0.1','2025-03-15 16:07:05',1,'2025-03-15 16:07:05',1,'2025-04-26 13:43:32','测试员'),(105,101,'test01','test01','00','123@123.com','15000001234','0','/2025/04/19/c2440680-cbdc-3fbf-b0d2-9a29e637f1b7.jpeg','$2y$10$79owPmKxjnwj.pY4fJBf4ebWYz/343nF2.tM6EKwvA1hAj5TIab2W','1',NULL,'192.168.31.99','2025-04-26 13:43:11',1,'2025-04-15 23:23:35',1,'2025-04-26 13:43:11','test01');

/*Table structure for table `sys_user_post` */

CREATE TABLE `sys_user_post` (
  `user_id` bigint NOT NULL COMMENT '用户ID',
  `post_id` bigint NOT NULL COMMENT '岗位ID',
  PRIMARY KEY (`user_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户与岗位关联表';

/*Data for the table `sys_user_post` */

insert  into `sys_user_post`(`user_id`,`post_id`) values (1,1),(2,2),(105,1),(105,2),(105,3);

/*Table structure for table `sys_user_role` */

CREATE TABLE `sys_user_role` (
  `user_id` bigint NOT NULL COMMENT '用户ID',
  `role_id` bigint NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户和角色关联表';

/*Data for the table `sys_user_role` */

insert  into `sys_user_role`(`user_id`,`role_id`) values (1,1),(2,2),(2,100),(105,100);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
