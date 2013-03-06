-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `mdeletricidade`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipe`
--

CREATE TABLE IF NOT EXISTS `equipe` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` longtext NOT NULL,
  `email` varchar(255) NOT NULL,
  `imagem` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `imagem` (`imagem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `equipe`
--
ALTER TABLE `equipe`
  ADD CONSTRAINT `equipe_ibfk_1` FOREIGN KEY (`imagem`) REFERENCES `jqueryimage` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
