-- BEGIN_TABLE
--
-- Table structure for table '<%- table %>'
--
CREATE TABLE <%- table %> (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL
);
-- END_TABLE

-- BEGIN_FIELDS_VERSIONING
    t3ver_oid int(11) DEFAULT '0' NOT NULL,
    t3ver_id int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid int(11) DEFAULT '0' NOT NULL,
    t3ver_label varchar(30) DEFAULT '' NOT NULL,
    t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_count int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id int(11) DEFAULT '0' NOT NULL,
    t3_origuid int(11) DEFAULT '0' NOT NULL
-- BEGIN_FIELDS_VERSIONING

-- BEGIN_FIELD_INT
    <%- field %> int(11) unsigned DEFAULT '0' NOT NULL
-- END_FIELD_INT

-- BEGIN_FIELD_TEXT
    <%- field %> text
-- END_FIELD_TEXT

-- BEGIN_FIELD_VARCHAR
    <%- field %> varchar(255) DEFAULT '' NOT NULL
-- END_FIELD_VARCHAR

-- BEGIN_FIELD_BOOLEAN
    <%- field %> tinyint(4) DEFAULT '0' NOT NULL
-- END_FIELD_BOOLEAN

-- BEGIN_TABLE_MM
--
-- Table structure for table '<%- table %>'
--
CREATE TABLE <%- table %> (
    uid_local int(11) DEFAULT '0' NOT NULL,
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    sorting int(11) DEFAULT '0' NOT NULL,
    sorting_foreign int(11) DEFAULT '0' NOT NULL,
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
-- END_TABLE_MM
