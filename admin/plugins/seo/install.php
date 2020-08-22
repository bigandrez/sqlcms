<?php
global $core;

  $core->db->query("DROP TABLE IF EXISTS `nodeext_seo`");
  $core->db->query("
CREATE TABLE `nodeext_seo` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nid` INT(11) NULL DEFAULT NULL,
	`title` TEXT,
	`description` TEXT,
	`keywords` TEXT,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `nid` (`nid`),
	INDEX `id` (`id`)
);");
