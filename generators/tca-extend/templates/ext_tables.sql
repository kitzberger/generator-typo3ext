-- 
-- Table structure for table '<%- table %>'
-- 
CREATE TABLE <%- table %> (
  new_checkbox_field tinyint(1) unsigned DEFAULT '0' NOT NULL,
  new_date_field int(11) unsigned DEFAULT '0' NOT NULL,
  new_rte_field text,
  new_image_field varchar(255) DEFAULT '' NOT NULL,
  new_link_field varchar(255) DEFAULT '' NOT NULL
);
