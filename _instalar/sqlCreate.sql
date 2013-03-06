-- --------------------------------------------------------
-- jqueryimage
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryimage` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `valor` varchar(255) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `jqueryimage` (`cod`, `valor`) VALUES
(0, 'root.png');

UPDATE  `jqueryimage` SET  `cod` =  '0' WHERE  `jqueryimage`.`cod` =1;

-- --------------------------------------------------------
-- jqueryadmingrupo
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryadmingrupo` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `jqueryadmingrupo` (`cod`, `titulo`) VALUES
(1, 'Jquery Cms'),
(2, 'Administradores');



-- --------------------------------------------------------
-- jqueryadmingrupo
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryadminmenu` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `codmenu` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `patch` varchar(255) NOT NULL,
  `icon` int(11) NOT NULL,
  `addhtml` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `codmenu` (`codmenu`),
  KEY `icon` (`icon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `jqueryadminmenu` (`cod`, `codmenu`, `titulo`, `patch`, `icon`, `addhtml`, `ordem`) VALUES
(0, 0, 'Root', '/', 0, '', 0);

UPDATE  `jqueryadminmenu` SET  `cod` =  '0' WHERE  `jqueryadminmenu`.`cod` =1;

-- --------------------------------------------------------
-- jqueryadmingrupo
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryadmingrupo2menu` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `jqueryadmingrupo` int(11) NOT NULL,
  `jqueryadminmenu` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `jqueryadmingrupo` (`jqueryadmingrupo`),
  KEY `jqueryadminmenu` (`jqueryadminmenu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



-- --------------------------------------------------------
-- jqueryadminuser
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryadminuser` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `grupo` (`grupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `jqueryadminuser` (`cod`, `nome`, `mail`, `senha`, `grupo`) VALUES
(1, 'João', 'joao@areadigital.com.br', '12345', 1),
(2, 'Eduardo', 'eduardo@areadigital.com.br', 'eduardo', 2);


-- --------------------------------------------------------
-- jqueryimagelist
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryimagelist` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `info` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- jqueryimagelistitem
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jqueryimagelistitem` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `jqueryimagelist` int(11) NOT NULL,
  `jqueryimage` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL,
  `principal` tinyint(1) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `portalimagelist` (`jqueryimagelist`),
  KEY `portalimage` (`jqueryimage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- Restrições 
-- --------------------------------------------------------
ALTER TABLE `jqueryadmingrupo2menu`
  ADD CONSTRAINT `jqueryadmingrupo2menu_ibfk_1` FOREIGN KEY (`jqueryadmingrupo`) REFERENCES `jqueryadmingrupo` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `jqueryadmingrupo2menu_ibfk_2` FOREIGN KEY (`jqueryadminmenu`) REFERENCES `jqueryadminmenu` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `jqueryadminmenu`
  ADD CONSTRAINT `jqueryadminmenu_ibfk_1` FOREIGN KEY (`codmenu`) REFERENCES `jqueryadminmenu` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `jqueryadminmenu_ibfk_2` FOREIGN KEY (`icon`) REFERENCES `jqueryimage` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `jqueryadminuser`
  ADD CONSTRAINT `jqueryadminuser_ibfk_1` FOREIGN KEY (`grupo`) REFERENCES `jqueryadmingrupo` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE `jqueryimagelistitem`
  ADD CONSTRAINT `jqueryimagelistitem_ibfk_2` FOREIGN KEY (`jqueryimage`) REFERENCES `jqueryimage` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `jqueryimagelistitem_ibfk_1` FOREIGN KEY (`jqueryimagelist`) REFERENCES `jqueryimagelist` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;