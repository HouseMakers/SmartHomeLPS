CREATE DATABASE  IF NOT EXISTS `smarthome`;
USE `smarthome`;


--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `status` varchar(100) NULL,
    `description` varchar(200) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Table structure for table `sensors`
--

DROP TABLE IF EXISTS `sensors`;
CREATE TABLE IF NOT EXISTS `sensors` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`id_space` int(11) DEFAULT NULL,
	`status` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
	`type` varchar(200) NOT NULL,
    `description` varchar(200) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Table structure for table `spaces`
--

DROP TABLE IF EXISTS `spaces`;
CREATE TABLE IF NOT EXISTS `spaces` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `spaces_sensors`
--

DROP TABLE IF EXISTS `spaces_sensors`;
CREATE TABLE IF NOT EXISTS `spaces_sensors` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_space` int(11) NOT NULL,
	`id_sensor` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Table structure for table `alerts_template`
--

DROP TABLE IF EXISTS `alerts_template`;
CREATE TABLE IF NOT EXISTS `alerts_template` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
	`description` text NOT NULL,
	`message` text NOT NULL,
	`sensor` text NOT NULL,
	`condition` text NULL,
	`value` text NULL,
	`status` text NOT NULL,
	`space_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`date` int(8) NOT NULL,
	`alert_template_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------