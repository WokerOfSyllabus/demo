/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : demo

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 07/07/2021 09:44:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '123');

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '课程id',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES (1, '数据库设计');
INSERT INTO `course` VALUES (2, 'ThinkPHP5');
INSERT INTO `course` VALUES (3, '数据挖掘');
INSERT INTO `course` VALUES (4, 'C语言');
INSERT INTO `course` VALUES (5, '软件工程');
INSERT INTO `course` VALUES (6, '人工智能');
INSERT INTO `course` VALUES (7, '遗传算法');
INSERT INTO `course` VALUES (8, '数据结构');
INSERT INTO `course` VALUES (9, '操作系统');
INSERT INTO `course` VALUES (10, '计算机组成与结构');

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '学生id',
  `number` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学号',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生姓名',
  `sex` tinyint NOT NULL COMMENT '1男生；0女生',
  `telephone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '联系方式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES (1, '0621191010', '刘一', 1, '18299863699');
INSERT INTO `student` VALUES (2, '0621191016', '陈二', 0, '15822289515');
INSERT INTO `student` VALUES (3, '0621191011', '张三', 1, '15569866385');
INSERT INTO `student` VALUES (4, '0621191013', '李四', 0, '15569866385');
INSERT INTO `student` VALUES (5, '0621191015', '王五', 1, '15533669810');
INSERT INTO `student` VALUES (6, '0621191003', '赵留', 0, '12255889966');
INSERT INTO `student` VALUES (7, '0621191114', '孙七', 1, '15588963863');
INSERT INTO `student` VALUES (8, '0621191020', '周八', 0, '13556988875');

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
  CONSTRAINT `student_course_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of student_course
-- ----------------------------
INSERT INTO `student_course` VALUES (1, 1, 1);
INSERT INTO `student_course` VALUES (2, 1, 3);
INSERT INTO `student_course` VALUES (3, 1, 5);
INSERT INTO `student_course` VALUES (4, 1, 7);
INSERT INTO `student_course` VALUES (5, 1, 9);
INSERT INTO `student_course` VALUES (6, 2, 2);
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
INSERT INTO `student_course` VALUES (33, 8, 5);
INSERT INTO `student_course` VALUES (34, 8, 7);

-- ----------------------------
-- Table structure for term
-- ----------------------------
DROP TABLE IF EXISTS `term`;
CREATE TABLE `term`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学期名称',
  `start_time` date NOT NULL COMMENT '起始时间',
  `end_time` date NULL DEFAULT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of term
-- ----------------------------
INSERT INTO `term` VALUES (1, '第一学期', '2021-03-14', '2021-07-09');
INSERT INTO `term` VALUES (2, '第二学期', '2021-08-28', '2022-01-08');

-- ----------------------------
-- Table structure for term_course
-- ----------------------------
DROP TABLE IF EXISTS `term_course`;
CREATE TABLE `term_course`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL COMMENT '课程ID',
  `term_id` int NOT NULL COMMENT '学期ID',
  `week` int NOT NULL COMMENT '周次',
  `week_day` int NOT NULL COMMENT '星期',
  `period` int NOT NULL COMMENT '上课时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of term_course
-- ----------------------------
INSERT INTO `term_course` VALUES (1, 1, 1, 1, 1, 1);
INSERT INTO `term_course` VALUES (2, 1, 1, 1, 3, 3);

SET FOREIGN_KEY_CHECKS = 1;
