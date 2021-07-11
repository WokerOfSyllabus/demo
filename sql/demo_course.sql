/*
 Navicat MySQL Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : demo

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 03/07/2021 23:41:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course`  (
  `course_id` int NOT NULL AUTO_INCREMENT COMMENT '课程id',
  `course_name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程名',
  `period` int NOT NULL COMMENT '上课时间',
  `start_week` int NOT NULL COMMENT '起始周',
  `end_week` int NOT NULL COMMENT '结束周',
  `week_day` int NOT NULL COMMENT '星期',
  `week_type` int NULL DEFAULT NULL COMMENT '1单周；2双周',
  `course_type` int NULL DEFAULT NULL COMMENT '1公共必修课；2专业必修课；3公共选修课；4自定义',
  `term_id` int NULL DEFAULT NULL COMMENT '学期关联',
  `create_time` int NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`course_id`) USING BTREE,
  INDEX `con_term`(`term_id`) USING BTREE,
  CONSTRAINT `con_term` FOREIGN KEY (`term_id`) REFERENCES `term` (`term_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES (1, '数据库设计', 1, 3, 8, 5, 1, 1, 1, 0, 0);
INSERT INTO `course` VALUES (2, 'ThinkPHP5', 2, 3, 18, 2, 2, 2, 2, 0, 0);
INSERT INTO `course` VALUES (3, '数据挖掘', 3, 9, 18, 3, 3, 3, 1, 0, 0);
INSERT INTO `course` VALUES (4, 'C语言', 4, 9, 18, 4, 3, 1, 1, 0, 0);
INSERT INTO `course` VALUES (5, '软件工程', 5, 1, 10, 5, 1, 2, 2, 0, 0);
INSERT INTO `course` VALUES (6, '人工智能', 3, 3, 8, 1, 2, 3, 2, 0, 0);
INSERT INTO `course` VALUES (7, '遗传算法', 4, 9, 19, 5, 2, 4, 2, 0, 0);
INSERT INTO `course` VALUES (8, '数据结构', 1, 3, 8, 3, 1, 1, 2, 0, 0);
INSERT INTO `course` VALUES (9, '操作系统', 2, 3, 8, 4, 3, 2, 2, 0, 0);
INSERT INTO `course` VALUES (10, '计算机组成与结构', 3, 3, 18, 5, 3, 3, 2, 0, 0);
INSERT INTO `course` VALUES (11, '测试课程1', 1, 1, 9, 0, 1, 1, 1, 1625067066, 1625067066);
INSERT INTO `course` VALUES (12, '测试课程2', 1, 1, 9, 0, 1, 1, 1, 1625104156, 1625104156);

-- ----------------------------
-- Table structure for dingding
-- ----------------------------
DROP TABLE IF EXISTS `dingding`;
CREATE TABLE `dingding`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `dingding_Url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地址',
  `send_time_hour` int NOT NULL COMMENT '时间(小时)',
  `send_time_minute` int NOT NULL COMMENT '时间(分钟)',
  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关键词',
  `check_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '校验码',
  `ip_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '锁定用的IP地址',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '钉钉群的一下信息',
  `is_use` bit(1) NULL DEFAULT NULL,
  `create_time` int NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of dingding
-- ----------------------------
INSERT INTO `dingding` VALUES (2, 'access_token=5815de602c4931aef48ec9d8a9e8853dbfb973c3470d5a58ef020b4c346f4092', 8, 2, '学生课表', '', '', '钉钉学生群', NULL, 1625307231, 1625307231);
INSERT INTO `dingding` VALUES (26, 'https://oapi.dingtalk.com/robot/send?access_token=5815de602c4931aef48ec9d8a9e8853dbfb973c3470d5a58ef020b4c346f4092', 8, 0, '学生课表', '', '', '钉钉学生群', NULL, 1625314765, 1625314765);
INSERT INTO `dingding` VALUES (27, 'https://oapi.dingtalk.com/robot/send?access_token=5815de602c4931aef48ec9d8a9e8853dbfb973c3470d5a58ef020b4c346f4092a', 8, 0, '学生课表', '', '', '钉钉学生群', NULL, 1625314943, 1625314943);

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `studentId` int NOT NULL AUTO_INCREMENT COMMENT '学生id',
  `studentName` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生姓名',
  `sex` tinyint NOT NULL COMMENT '1男生；0女生',
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '邮箱',
  `create_tiem` int NOT NULL COMMENT '创建时间',
  `update_time` int NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`studentId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES (1, '刘一', 1, 'liuyi@qq.com', 0, 0);
INSERT INTO `student` VALUES (2, '陈二', 0, 'chener@qq.com', 0, 0);
INSERT INTO `student` VALUES (3, '张三', 1, 'zhangsan@qq.com', 0, 0);
INSERT INTO `student` VALUES (4, '李四', 0, 'lisi@163.com', 0, 0);
INSERT INTO `student` VALUES (5, '王五', 1, 'wangwu@16.com', 0, 0);
INSERT INTO `student` VALUES (6, '赵留', 0, 'zhaoliu@163.', 0, 0);
INSERT INTO `student` VALUES (7, '孙七', 1, 'sunqi@126.com', 0, 0);
INSERT INTO `student` VALUES (8, '周八', 0, 'zhouba@126.com', 0, 0);

-- ----------------------------
-- Table structure for student_course
-- ----------------------------
DROP TABLE IF EXISTS `student_course`;
CREATE TABLE `student_course`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL COMMENT '学生id',
  `course_id` int NOT NULL COMMENT '课程id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_id`(`student_id`) USING BTREE,
  INDEX `course_id`(`course_id`) USING BTREE,
  CONSTRAINT `student_course_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`studentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of student_course
-- ----------------------------
INSERT INTO `student_course` VALUES (1, 1, 1);
INSERT INTO `student_course` VALUES (2, 1, 3);
INSERT INTO `student_course` VALUES (3, 1, 5);
INSERT INTO `student_course` VALUES (4, 1, 7);
INSERT INTO `student_course` VALUES (5, 1, 9);
INSERT INTO `student_course` VALUES (6, 2, 2);
INSERT INTO `student_course` VALUES (7, 2, 4);
INSERT INTO `student_course` VALUES (8, 2, 6);
INSERT INTO `student_course` VALUES (9, 2, 8);
INSERT INTO `student_course` VALUES (10, 2, 10);
INSERT INTO `student_course` VALUES (11, 3, 1);
INSERT INTO `student_course` VALUES (12, 3, 4);
INSERT INTO `student_course` VALUES (13, 3, 5);
INSERT INTO `student_course` VALUES (14, 3, 7);
INSERT INTO `student_course` VALUES (15, 3, 9);
INSERT INTO `student_course` VALUES (16, 3, 10);
INSERT INTO `student_course` VALUES (17, 4, 2);
INSERT INTO `student_course` VALUES (18, 4, 7);
INSERT INTO `student_course` VALUES (19, 4, 10);
INSERT INTO `student_course` VALUES (20, 5, 1);
INSERT INTO `student_course` VALUES (21, 5, 6);
INSERT INTO `student_course` VALUES (22, 5, 7);
INSERT INTO `student_course` VALUES (23, 5, 8);
INSERT INTO `student_course` VALUES (24, 5, 9);
INSERT INTO `student_course` VALUES (25, 6, 3);
INSERT INTO `student_course` VALUES (27, 6, 4);
INSERT INTO `student_course` VALUES (28, 6, 7);
INSERT INTO `student_course` VALUES (29, 6, 10);
INSERT INTO `student_course` VALUES (30, 7, 1);
INSERT INTO `student_course` VALUES (31, 7, 4);
INSERT INTO `student_course` VALUES (32, 7, 7);
INSERT INTO `student_course` VALUES (33, 8, 5);
INSERT INTO `student_course` VALUES (34, 8, 7);
INSERT INTO `student_course` VALUES (35, 8, 9);

-- ----------------------------
-- Table structure for term
-- ----------------------------
DROP TABLE IF EXISTS `term`;
CREATE TABLE `term`  (
  `term_id` int NOT NULL AUTO_INCREMENT,
  `term_year` int NULL DEFAULT NULL COMMENT '学年',
  `open_date` date NULL DEFAULT NULL COMMENT '开学时间',
  `term_num` int NULL DEFAULT NULL COMMENT '学期',
  `create_time` int NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`term_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of term
-- ----------------------------
INSERT INTO `term` VALUES (1, 2020, '2021-03-01', 2, 0, 0);
INSERT INTO `term` VALUES (2, 2021, '2021-09-01', 1, 0, 0);
INSERT INTO `term` VALUES (3, 2021, '2022-03-01', 2, 0, 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '姓名',
  `create_time` int NOT NULL COMMENT '创建时间',
  `update_time` int NOT NULL COMMENT '更新时间',
  `type` int NOT NULL COMMENT '管理员类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'admin', '123', '管理员', 0, 0, 1);

SET FOREIGN_KEY_CHECKS = 1;
