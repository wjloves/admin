

-- ----------------------------
-- Table structure for mayc_course_type
-- ----------------------------
DROP TABLE IF EXISTS `mayc_course_type`;
CREATE TABLE `mayc_course_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL COMMENT '全称',
  `alias_name` varchar(255) DEFAULT NULL COMMENT '别名，简称',
  `admin_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `status` tinyint(3) DEFAULT '8' COMMENT '状态  0  删除   8 正常',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='课程分类表';

-- ----------------------------
-- Records of mayc_course_type
-- ----------------------------
INSERT INTO `mayc_course_type` VALUES ('1', 'jazz funk', 'jazz', '1', '8', '2017-08-18 03:43:25', '2017-08-18 03:43:25');
INSERT INTO `mayc_course_type` VALUES ('2', '基础课程', '基础', '1', '8', '2017-08-18 03:43:26', '2017-08-18 03:43:26');
INSERT INTO `mayc_course_type` VALUES ('3', 'hihop', 'pop', '1', '8', '2017-08-18 03:43:27', '2017-08-18 03:43:27');
INSERT INTO `mayc_course_type` VALUES ('4', '啊啊啊', '啊啊啊啊', '1', '8', '2017-08-17 20:43:38', '2017-08-17 20:43:38');
INSERT INTO `mayc_course_type` VALUES ('5', '啊啊啊', '333', '1', '0', '2017-08-18 05:14:13', '2017-08-17 21:14:13');

-- ----------------------------
-- Table structure for mayc_courses
-- ----------------------------
DROP TABLE IF EXISTS `mayc_courses`;
CREATE TABLE `mayc_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '老师ID',
  `course_id` int(11) DEFAULT NULL COMMENT '课程类型ID',
  `course_time` date NOT NULL COMMENT '课程日期',
  `start_time` datetime NOT NULL COMMENT '开始时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `status` tinyint(3) DEFAULT '8' COMMENT '0 删除  7 取消 8 正常',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='课程表';

-- ----------------------------
-- Records of mayc_courses
-- ----------------------------
INSERT INTO `mayc_courses` VALUES ('6', '4', '1', '2017-08-04', '2017-08-04 01:15:00', '2017-08-04 02:15:00', '8', '2017-08-14 23:50:47', '2017-08-17 16:31:42');
INSERT INTO `mayc_courses` VALUES ('7', '4', '1', '2017-08-16', '2017-08-16 16:30:00', '2017-08-16 17:30:00', '8', '2017-08-14 23:54:00', '2017-08-14 23:58:54');
INSERT INTO `mayc_courses` VALUES ('8', '4', '1', '2017-08-24', '2017-08-24 16:30:00', '2017-08-24 17:30:00', '8', '2017-08-14 23:54:54', '2017-08-17 16:03:17');
INSERT INTO `mayc_courses` VALUES ('9', '4', '2', '2017-08-11', '2017-08-11 16:30:00', '2017-08-11 17:30:00', '8', '2017-08-14 23:55:08', '2017-08-14 23:55:08');

-- ----------------------------
-- Table structure for mayc_menus
-- ----------------------------
DROP TABLE IF EXISTS `mayc_menus`;
CREATE TABLE `mayc_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '组别名称',
  `sort` smallint(6) NOT NULL DEFAULT '255' COMMENT '排序',
  `perm_id` int(11) NOT NULL DEFAULT '0' COMMENT '路由ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';

-- ----------------------------
-- Records of mayc_menus
-- ----------------------------
INSERT INTO `mayc_menus` VALUES ('1', '0', '首页', 'home', '1', '0', null, null);
INSERT INTO `mayc_menus` VALUES ('2', '0', '课程管理', 'course', '3', '0', null, null);
INSERT INTO `mayc_menus` VALUES ('3', '0', '内容管理', 'content', '4', '0', null, null);
INSERT INTO `mayc_menus` VALUES ('4', '0', '系统管理', 'config', '5', '0', null, null);
INSERT INTO `mayc_menus` VALUES ('5', '0', '用户管理', 'user', '2', '0', null, null);
INSERT INTO `mayc_menus` VALUES ('8', '1', '统计', null, '255', '20', null, null);
INSERT INTO `mayc_menus` VALUES ('9', '4', '菜单列表', null, '255', '31', null, null);
INSERT INTO `mayc_menus` VALUES ('10', '2', '课程类型', null, '255', '26', null, null);
INSERT INTO `mayc_menus` VALUES ('11', '5', '会员列表', null, '255', '8', null, null);
INSERT INTO `mayc_menus` VALUES ('12', '2', '课程列表', null, '255', '12', null, null);
INSERT INTO `mayc_menus` VALUES ('13', '5', 'Vip列表', null, '255', '39', '2017-08-17 23:02:37', '2017-08-17 23:02:37');
INSERT INTO `mayc_menus` VALUES ('14', '3', '消息列表', null, '255', '41', '2017-08-18 01:36:52', '2017-08-18 01:36:52');

-- ----------------------------
-- Table structure for mayc_messages
-- ----------------------------
DROP TABLE IF EXISTS `mayc_messages`;
CREATE TABLE `mayc_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `reply` text COMMENT '回复内容',
  `admin_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `status` tinyint(3) DEFAULT '8' COMMENT '状态 0 删除  7 禁用 8 正常',
  `created_at` timestamp NULL DEFAULT NULL  COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='自动回复表';

-- ----------------------------
-- Records of mayc_messages
-- ----------------------------
INSERT INTO `mayc_messages` VALUES ('1', '你好', '你好，有什么可以帮你', '1', '8', '2017-08-18 12:28:58', '2017-08-18 04:28:58');
INSERT INTO `mayc_messages` VALUES ('2', '1312312312', null, '1', '8', '2017-08-18 10:40:43', '2017-08-18 10:40:43');
INSERT INTO `mayc_messages` VALUES ('3', '22222', '<h3><a href=\"https://www.google.com.hk/url?sa=t&amp;rct=j&amp;q=&amp;esrc=s&amp;source=web&amp;cd=1&amp;ved=0ahUKEwiUyJX_t-DVAhVFu7wKHbKBCNwQFggnMAA&amp;url=https%3A%2F%2Fgithub.com%2Fjhollingworth%2Fbootstrap-wysihtml5%2Fissues%2F268&amp;usg=AFQjCNEI8hLnV_wZk2nCLyxI7f_Y_7yg-A\" target=\"\" rel=\"\">WYSIHTML5 doesn\'t work in a bootstrap modal · Issue #268 ... - GitHub</a></h3><div><div><a href=\"https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268\" title=\"Link: https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268\">https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268</a><div><a href=\"https://www.google.com.hk/search?q=bootstrap-wysihtml5+ifarm+css+display+none&amp;oq=bootstrap-wysihtml5++ifarm+css++display+none&amp;aqs=chrome..69i57.13407j0j7&amp;sourceid=chrome&amp;ie=UTF-8#\" target=\"\" rel=\"\"></a><div><ol><li><a href=\"https://webcache.googleusercontent.com/search?q=cache:90_KA2TkdSMJ:https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268+&amp;cd=1&amp;hl=zh-CN&amp;ct=clnk&amp;gl=hk\" target=\"\" rel=\"\"></a></li><li><a href=\"https://www.google.com.hk/search?safe=strict&amp;q=related:https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268+bootstrap-wysihtml5+ifarm+css+display+none&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwiUyJX_t-DVAhVFu7wKHbKBCNwQHwgrMAA\" target=\"\" rel=\"\"></a></li></ol></div></div><a href=\"https://translate.google.com.hk/translate?hl=zh-CN&amp;sl=en&amp;u=https://github.com/jhollingworth/bootstrap-wysihtml5/issues/268&amp;prev=search\" target=\"\" rel=\"\">翻译此页</a></div><span><span>2013年6月21日 -&nbsp;</span>Contribute to&nbsp;bootstrap-wysihtml5&nbsp;development by creating an account on GitHub. ... Assignees. No one assigned. Labels.&nbsp;None&nbsp;yet. Projects.&nbsp;None&nbsp;yet ... Content/css/wysiwyg-color.css\"] }); } return { //main function to initiate the ... and re-show&nbsp;the textarea so that on each subsequent modaldisplay, the&nbsp;...</span></div>', '1', '8', '2017-08-18 10:40:44', '2017-08-18 10:40:44');
INSERT INTO `mayc_messages` VALUES ('4', '12312', '312312<br>3213123<br>231231231', '1', '8', '2017-08-18 04:28:43', '2017-08-18 04:28:43');

-- ----------------------------
-- Table structure for mayc_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `mayc_permission_role`;
CREATE TABLE `mayc_permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of mayc_permission_role
-- ----------------------------
INSERT INTO `mayc_permission_role` VALUES ('9', '2');

-- ----------------------------
-- Table structure for mayc_permissions
-- ----------------------------
DROP TABLE IF EXISTS `mayc_permissions`;
CREATE TABLE `mayc_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `desc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '描述',
  `method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求方式',
  `uri` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '路由',
  `is_delete` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='路由表';

-- ----------------------------
-- Records of mayc_permissions
-- ----------------------------
INSERT INTO `mayc_permissions` VALUES ('1', 'admin', 'desc', 'GET', 'admin', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('2', 'admin/login', '登录（展示）', 'GET', 'admin/login', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('3', 'admin/login', '登录（提交）', 'POST', 'admin/login', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('4', 'admin/logout', '退出登录', 'GET', 'admin/logout', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('5', 'admin/register', '注册（展示）', 'GET', 'admin/register', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('6', 'admin/register', '注册（提交）', 'POST', 'admin/register', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('7', 'admin/home', '首页', 'GET', 'admin/home', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('8', 'admin/user', '用户列表', 'GET', 'admin/user', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('9', 'admin/user/store', '添加用户', 'GET', 'admin/user/store', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('10', 'admin/user/update/{id}', '更新用户', 'GET', 'admin/user/update/{id}', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('11', 'admin/user/lock/{id}/{state}', '删除锁定用户', 'GET', 'admin/user/lock/{id}/{state}', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('12', 'admin/course', '课程列表', 'GET', 'admin/course', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('13', 'admin/course/store', '添加课程', 'GET', 'admin/course/store', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('14', 'admin/course/update/{id}', '修改课程', 'GET', 'admin/course/update/{id}', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('15', 'admin/course/lock/{id}/{state}', '删除锁定课程', 'GET', 'admin/course/lock/{id}/{state}', '0', '2017-08-15 03:47:45', '2017-08-15 03:47:45');
INSERT INTO `mayc_permissions` VALUES ('20', 'admin/home', '首页', 'GET', 'admin/home', '0', null, null);
INSERT INTO `mayc_permissions` VALUES ('26', 'admin/course/type', '课程分类列表', 'GET', 'admin/course/type', '0', null, null);
INSERT INTO `mayc_permissions` VALUES ('27', 'admin/course/type/store', '添加课程分类', 'GET', 'admin/course/type/store', '0', '2017-08-17 19:48:25', '2017-08-17 19:48:25');
INSERT INTO `mayc_permissions` VALUES ('28', 'admin/course/type/update/{id}', '修改课程分类', 'GET', 'admin/course/type/update/{id}', '0', '2017-08-17 19:48:25', '2017-08-17 19:48:25');
INSERT INTO `mayc_permissions` VALUES ('29', 'admin/course/type/del/{id}', '删除课程分类', 'GET', 'admin/course/type/del/{id}', '0', '2017-08-17 19:48:25', '2017-08-17 19:48:25');
INSERT INTO `mayc_permissions` VALUES ('31', 'admin/config/menu/list', '菜单列表', 'GET', 'admin/config/menu/list', '0', '2017-08-17 22:55:14', '2017-08-17 22:55:14');
INSERT INTO `mayc_permissions` VALUES ('32', 'admin/config/menu/store', '添加菜单', 'GET', 'admin/config/menu/store', '0', '2017-08-17 22:55:14', '2017-08-17 22:55:14');
INSERT INTO `mayc_permissions` VALUES ('33', 'admin/config/menu/update/{id}', '修改菜单', 'GET', 'admin/config/menu/update/{id}', '0', '2017-08-17 22:55:14', '2017-08-17 22:55:14');
INSERT INTO `mayc_permissions` VALUES ('34', 'admin/config/menu/del/{id}', '删除菜单', 'POST', 'admin/config/menu/del/{id}', '0', '2017-08-17 22:55:14', '2017-08-17 22:55:14');
INSERT INTO `mayc_permissions` VALUES ('35', 'admin/user/vip/list', 'desc', 'GET', 'admin/user/vip/list', '0', '2017-08-17 23:02:02', '2017-08-17 23:02:02');
INSERT INTO `mayc_permissions` VALUES ('36', 'admin/user/vip/store', 'desc', 'GET', 'admin/user/vip/store', '0', '2017-08-17 23:02:02', '2017-08-17 23:02:02');
INSERT INTO `mayc_permissions` VALUES ('37', 'admin/user/vip/update/{id}', 'desc', 'GET', 'admin/user/vip/update/{id}', '0', '2017-08-17 23:02:02', '2017-08-17 23:02:02');
INSERT INTO `mayc_permissions` VALUES ('38', 'admin/user/vip/del/{id}', 'desc', 'POST', 'admin/user/vip/del/{id}', '0', '2017-08-17 23:02:02', '2017-08-17 23:02:02');
INSERT INTO `mayc_permissions` VALUES ('39', 'admin/user/vip/list', 'Vip列表', 'GET', 'admin/user/vip/list', '0', null, null);
INSERT INTO `mayc_permissions` VALUES ('40', 'admin/user/vip/del/{id}', 'desc', 'GET', 'admin/user/vip/del/{id}', '0', '2017-08-18 01:30:17', '2017-08-18 01:30:17');
INSERT INTO `mayc_permissions` VALUES ('41', 'admin/content/autoreply', '消息列表', 'GET', 'admin/content/autoreply', '0', null, null);
INSERT INTO `mayc_permissions` VALUES ('42', 'admin/content', 'desc', 'GET', 'admin/content', '0', '2017-08-18 01:56:06', '2017-08-18 01:56:06');
INSERT INTO `mayc_permissions` VALUES ('43', 'admin/content/store', 'desc', 'GET', 'admin/content/store', '0', '2017-08-18 01:56:06', '2017-08-18 01:56:06');
INSERT INTO `mayc_permissions` VALUES ('44', 'admin/content/update/{id}', 'desc', 'GET', 'admin/content/update/{id}', '0', '2017-08-18 01:56:06', '2017-08-18 01:56:06');
INSERT INTO `mayc_permissions` VALUES ('45', 'admin/content/del/{id}', 'desc', 'GET', 'admin/content/del/{id}', '0', '2017-08-18 01:56:06', '2017-08-18 01:56:06');
INSERT INTO `mayc_permissions` VALUES ('46', 'admin/content/lock/{id}', 'desc', 'GET', 'admin/content/lock/{id}', '0', '2017-08-18 01:57:50', '2017-08-18 01:57:50');
INSERT INTO `mayc_permissions` VALUES ('47', 'admin/content/lock/{id}/{status?}', 'desc', 'GET', 'admin/content/lock/{id}/{status?}', '0', '2017-08-18 01:58:46', '2017-08-18 01:58:46');

-- ----------------------------
-- Table structure for mayc_roles
-- ----------------------------
DROP TABLE IF EXISTS `mayc_roles`;
CREATE TABLE `mayc_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_display_name_unique` (`display_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

-- ----------------------------
-- Records of mayc_roles
-- ----------------------------
INSERT INTO `mayc_roles` VALUES ('1', 'admin', '超级管理员', '2017-05-08 07:19:25', '2017-05-08 07:19:25');

-- ----------------------------
-- Table structure for mayc_user_course
-- ----------------------------
DROP TABLE IF EXISTS `mayc_user_course`;
CREATE TABLE `mayc_user_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户课程关联表';

-- ----------------------------
-- Records of mayc_user_course
-- ----------------------------
INSERT INTO `mayc_user_course` VALUES ('1', '4', '6', null, null);
INSERT INTO `mayc_user_course` VALUES ('2', '4', '7', null, null);
INSERT INTO `mayc_user_course` VALUES ('3', '4', '8', null, null);
INSERT INTO `mayc_user_course` VALUES ('4', '4', '9', null, null);

-- ----------------------------
-- Table structure for mayc_user_groups
-- ----------------------------
DROP TABLE IF EXISTS `mayc_user_groups`;
CREATE TABLE `mayc_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL COMMENT '用户组名称',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Records of mayc_user_groups
-- ----------------------------
INSERT INTO `mayc_user_groups` VALUES ('1', '普通会员', null, null);
INSERT INTO `mayc_user_groups` VALUES ('2', '导师', null, null);
INSERT INTO `mayc_user_groups` VALUES ('3', '管理员', null, null);

-- ----------------------------
-- Table structure for mayc_users_wechat
-- ----------------------------
DROP TABLE IF EXISTS `mayc_users_wechat`;
CREATE TABLE `mayc_users_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `nick_name` varchar(255) DEFAULT NULL COMMENT '昵称',
  `wechat` varchar(255) DEFAULT NULL COMMENT '微信关联ID',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信唯一标示',
  `vip_id` tinyint(4) DEFAULT NULL COMMENT '会员等级表',
  `times` int(11) DEFAULT '0' COMMENT '剩余次数',
  `from_user` varchar(255) DEFAULT NULL COMMENT '微信唯一ID',
  `group_id` tinyint(3) DEFAULT '1' COMMENT '用户组ID',
  `ex_time` datetime DEFAULT NULL COMMENT '会员过期时间',
  `status` tinyint(3) NOT NULL DEFAULT '8' COMMENT '状态：0 删除 7 禁用 8 正常',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表（微信关联表）';

-- ----------------------------
-- Records of mayc_users_wechat
-- ----------------------------
INSERT INTO `mayc_users_wechat` VALUES ('4', 'aaa', '羊拉屎', '46454', '465456456456', null, '44', null, '2', null, '8', null, '2017-08-18 01:32:40');

-- ----------------------------
-- Table structure for mayc_vip
-- ----------------------------
DROP TABLE IF EXISTS `mayc_vip`;
CREATE TABLE `mayc_vip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='会员等级表';

-- ----------------------------
-- Records of mayc_vip
-- ----------------------------
INSERT INTO `mayc_vip` VALUES ('5', '月卡', null, null);
INSERT INTO `mayc_vip` VALUES ('6', '季卡', null, null);
INSERT INTO `mayc_vip` VALUES ('7', '年卡', null, null);
INSERT INTO `mayc_vip` VALUES ('10', '普通会员', '2017-08-18 01:32:20', '2017-08-18 01:32:20');
