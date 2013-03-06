CREATE TABLE IF NOT EXISTS `locmapaponto` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `pitch` varchar(255) NOT NULL,
  `zoom` varchar(255) NOT NULL,
  `comportamento` int(11) NOT NULL,
  `suportaview` tinyint(1) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;