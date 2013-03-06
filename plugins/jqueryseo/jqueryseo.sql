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

--
-- Banco de Dados: `jquerycms2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `jqueryseo`
--

CREATE TABLE IF NOT EXISTS `jqueryseo` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jqueryseopalavra`
--

CREATE TABLE IF NOT EXISTS `jqueryseopalavra` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `palavra` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jqueryseorel`
--

CREATE TABLE IF NOT EXISTS `jqueryseorel` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `seo` int(11) NOT NULL,
  `palavra` int(11) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `seo` (`seo`),
  KEY `palavra` (`palavra`),
  KEY `palavra_2` (`palavra`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jqueryseotabela`
--

CREATE TABLE IF NOT EXISTS `jqueryseotabela` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `tabela` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `jqueryseorel`
--
ALTER TABLE `jqueryseorel`
  ADD CONSTRAINT `jqueryseorel_ibfk_1` FOREIGN KEY (`seo`) REFERENCES `jqueryseo` (`cod`),
  ADD CONSTRAINT `jqueryseorel_ibfk_2` FOREIGN KEY (`palavra`) REFERENCES `jqueryseopalavra` (`cod`);
