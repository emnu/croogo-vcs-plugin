SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `vcs_caches`
--

CREATE TABLE IF NOT EXISTS `vcs_caches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(63) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `operation` char(15) DEFAULT NULL COMMENT 'add, update, delete',
  `commit` tinyint(4) DEFAULT NULL,
  `checksum` varchar(63) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vcs_revisions`
--

CREATE TABLE IF NOT EXISTS `vcs_revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(63) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `diff` mediumtext,
  `data` text,
  `remark` varchar(1023) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `commit_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
