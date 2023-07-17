/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL-LOCAL
 Source Server Type    : MySQL
 Source Server Version : 50733 (5.7.33)
 Source Host           : localhost:3306
 Source Schema         : stfi

 Target Server Type    : MySQL
 Target Server Version : 50733 (5.7.33)
 File Encoding         : 65001

 Date: 17/07/2023 21:16:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for data_mahasiswa
-- ----------------------------
DROP TABLE IF EXISTS `data_mahasiswa`;
CREATE TABLE `data_mahasiswa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `npm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `semester` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jurusan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_mahasiswa` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_perwalian` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `create_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `update_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of data_mahasiswa
-- ----------------------------
INSERT INTO `data_mahasiswa` VALUES (1, 'ewqeqwe', '312312312', '1', 'dqweqweqw', '1', '0', NULL, NULL, NULL, NULL);
INSERT INTO `data_mahasiswa` VALUES (2, '321312', '312312', '3', '3123123', '0', '1', '2023-07-17 00:21:23', '3', '2023-07-17 00:21:23', '3');

-- ----------------------------
-- Table structure for data_slider
-- ----------------------------
DROP TABLE IF EXISTS `data_slider`;
CREATE TABLE `data_slider`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `create_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `update_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `path` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of data_slider
-- ----------------------------
INSERT INTO `data_slider` VALUES (18, 'adadwaewqe', '1', '1', '2023-07-16 20:23:33', '1', '2023-07-16 20:23:33', './uploads/slider/18/slider-18-3800_2_06.jpg', 'slider');
INSERT INTO `data_slider` VALUES (19, '1231423534254354', '1', '1', '2023-07-16 20:43:07', '1', '2023-07-16 20:46:30', './uploads/slider/19/slider-19-38e94f00-7674-4b42-8e50-6d288f78abdf.jpg', 'slider');

-- ----------------------------
-- Table structure for m_role
-- ----------------------------
DROP TABLE IF EXISTS `m_role`;
CREATE TABLE `m_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of m_role
-- ----------------------------
INSERT INTO `m_role` VALUES (1, 'Super Administrator', 1);
INSERT INTO `m_role` VALUES (2, 'Administrator', 2);
INSERT INTO `m_role` VALUES (3, 'Staff Gedung Arsip', 3);
INSERT INTO `m_role` VALUES (4, 'Eksekutif', 4);
INSERT INTO `m_role` VALUES (5, 'Administrator Daerah', 5);

-- ----------------------------
-- Table structure for m_user
-- ----------------------------
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE `m_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_role` int(11) NOT NULL,
  `status` tinyint(4) NULL DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `create_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `update_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of m_user
-- ----------------------------
INSERT INTO `m_user` VALUES (1, 'super admin', 'beno.sons@gmail.com', 'superadmin', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, '2023-07-16 16:43:24', NULL, '1', '1');
INSERT INTO `m_user` VALUES (3, 'benosons', 'beno.sons@gmail.com', 'benosons', 'd1bb80a489b51ed9c862380fbcdf7ea3', 1, 1, '2023-07-16 21:21:20', '2023-07-16 21:21:20', '1', '1');

SET FOREIGN_KEY_CHECKS = 1;
