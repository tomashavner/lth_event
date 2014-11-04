#
# Table structure for table 'tx_lthevents_event'
#
CREATE TABLE tx_lthevents_event (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
        username varchar(25) DEFAULT '' NOT NULL,
	event varchar(255) DEFAULT '' NOT NULL,
	start int(11) DEFAULT '0' NOT NULL,
	end int(11) DEFAULT '0' NOT NULL,
	description text,
	place varchar(255) DEFAULT '' NOT NULL,
	organizer varchar(255) DEFAULT '' NOT NULL,
	signup tinyint(3) DEFAULT '0' NOT NULL,
	allday tinyint(3) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;