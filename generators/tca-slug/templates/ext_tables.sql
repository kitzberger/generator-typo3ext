-- 
-- Table structure for table '<%- table %>'
-- 
CREATE TABLE <%- table %> (
  <%- slug_column %> varchar(2048) DEFAULT NULL,
  KEY `<%- slug_column %>` (`<%- slug_column %>`(127))
);
