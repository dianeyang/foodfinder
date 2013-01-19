--
-- Database: `project`
--
 
CREATE DATABASE IF NOT EXISTS  `project` ;
 
 
--
-- Table structure for table `users`
--
 
CREATE TABLE IF NOT EXISTS `project`.`users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;
 
--
-- Dumping data for table `project`.`users`
--
 
INSERT IGNORE INTO `project`.`users` VALUES(1, 'caesar', '$1$50$GHABNWBNE/o4VL7QjmQ6x0');
INSERT IGNORE INTO `project`.`users` VALUES(2, 'cs50', '$1$50$ceNa7BV5AoVQqilACNLuC1');
INSERT IGNORE INTO `project`.`users` VALUES(3, 'jharvard', '$1$50$RX3wnAMNrGIbgzbRYrxM1/');
INSERT IGNORE INTO `project`.`users` VALUES(4, 'malan', '$1$HA$azTGIMVlmPi9W9Y12cYSj/');
INSERT IGNORE INTO `project`.`users` VALUES(5, 'nate', '$1$50$sUyTaTbiSKVPZCpjJckan0');
INSERT IGNORE INTO `project`.`users` VALUES(6, 'rbowden', '$1$50$lJS9HiGK6sphej8c4bnbX.');
INSERT IGNORE INTO `project`.`users` VALUES(7, 'skroob', '$1$50$euBi4ugiJmbpIbvTTfmfI.');
INSERT IGNORE INTO `project`.`users` VALUES(8, 'tmacwilliam', '$1$50$91ya4AroFPepdLpiX.bdP1');
INSERT IGNORE INTO `project`.`users` VALUES(9, 'zamyla', '$1$50$Suq.MOtQj51maavfKvFsW1');
