CREATE DATABASE  IF NOT EXISTS `smarthome`;
USE `smarthome`;

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
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_space` int(11) DEFAULT NULL,
    `category` varchar(100) NOT NULL,
    `type` varchar(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `status` varchar(100) NOT NULL,
    `description` varchar(200) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alerts_template`
--

DROP TABLE IF EXISTS `alerts_template`;
CREATE TABLE IF NOT EXISTS `alerts_template` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `space_id` int(11) NOT NULL,
    `device_id` int(11) NOT NULL,
    `title` varchar(100) NOT NULL,
	`description` text NOT NULL,
	`message` text NOT NULL,
	`status` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alerts_template_expression`
--

DROP TABLE IF EXISTS `alerts_template_expression`;
CREATE TABLE IF NOT EXISTS `alerts_template_expression` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `alert_template_id` int(11) NOT NULL,
	`condition` varchar(100) NOT NULL,
	`value` varchar(100) NOT NULL,
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