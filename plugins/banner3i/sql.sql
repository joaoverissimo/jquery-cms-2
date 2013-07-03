--
-- Estrutura da tabela `banner3i`
--

CREATE TABLE IF NOT EXISTS `banner3i` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` int(11) NOT NULL,
  `titulopt` varchar(255) NOT NULL,
  `tituloes` varchar(255) NOT NULL,
  `tituloen` varchar(255) NOT NULL,
  `descricaopt` varchar(255) NOT NULL,
  `descricaoes` varchar(255) NOT NULL,
  `descricaoen` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `exibir` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `imagem` (`imagem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `banner3i`
--
ALTER TABLE `banner3i`
  ADD CONSTRAINT `banner3i_ibfk_1` FOREIGN KEY (`imagem`) REFERENCES `jqueryimage` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;
