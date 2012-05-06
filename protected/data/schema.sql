-- Properties --
SET GLOBAL storage_engine = InnoDB;
SET GLOBAL character_set_client = utf8; 
SET GLOBAL character_set_connection = utf8;
SET GLOBAL character_set_database = utf8;
SET GLOBAL character_set_filesystem = binary;
SET GLOBAL character_set_results = utf8;
SET GLOBAL collation_connection = utf8_general_ci;
SET GLOBAL collation_database = utf8_general_ci;
SET GLOBAL collation_server = utf8_general_ci;


CREATE TABLE IF NOT EXISTS users
(
	id int(10) NOT NULL auto_increment,
	username varchar(155) NOT NULL default '',
	seoname varchar(250) NOT NULL default '',
	email varchar(155) NOT NULL default '',
	password varchar(40) NOT NULL default '',
	joined int(10) NOT NULL default '0',
	data TEXT NULL,
	role char(30) NOT NULL default '',
	ipaddress char(30) NOT NULL default '',
	PRIMARY KEY (id),
	KEY username (username),
	KEY email (email)
) ENGINE=InnoDB;

-- Site Based Related --

CREATE TABLE IF NOT EXISTS projects
(
	id int(10) NOT NULL auto_increment,
	title varchar(255) NOT NULL default '',
	alias varchar(250) NOT NULL default '',
	status tinyint(1) NOT NULL default '0',
	created int(10) NOT NULL default '0',
	userid int(10) NOT NULL default '0',
	description varchar(125) NOT NULL default '',
	PRIMARY KEY (id),
	KEY title (title),
	KEY userid (userid)
);

CREATE TABLE IF NOT EXISTS activity
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	type char(30) NOT NULL default '',
	userid int(10) NOT NULL default '0',
	created int(10) NOT NULL default '0',
	message TEXT NULL,
	params TEXT NULL,
	projectid int(10) NOT NUll default '0',
	PRIMARY KEY (id),
	KEY title (title),
	KEY userid (userid),
	KEY projectid (projectid),
	KEY type (type)
);

CREATE TABLE IF NOT EXISTS wiki_pages
(
	id int(10) NOT NULL auto_increment,
	title varchar(255) NOT NULL default '',
	alias varchar(250) NOT NULL default '',
	status tinyint(1) NOT NULL default '0',
	created int(10) NOT NULL default '0',
	userid int(10) NOT NULL default '0',
	description varchar(125) NOT NULL default '',
	projectid int(10) NOT NULL default '0',
	workingrevision int(10) NOT NULL default '0',
	isstartpage tinyint(1) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY title (title),
	KEY userid (userid),
	KEY workingrevision (workingrevision),
	KEY projectid (projectid),
	KEY isstartpage (isstartpage)
);

CREATE TABLE IF NOT EXISTS wiki_page_revision
(
	id int(10) NOT NULL auto_increment,
    pageid int(10) NOT NULL default '0',
    revisionid int(10) NOT NULL default '0',
	created int(10) NOT NULL default '0',
	userid int(10) NOT NULL default '0',
	content TEXT NULL,
	comment varchar(125) NOT NULL default '',
	PRIMARY KEY (id),
	KEY userid (userid),
	KEY pageid (pageid),
	KEY revisionid (revisionid)
);

CREATE TABLE IF NOT EXISTS tickets
(
	id int(10) NOT NULL auto_increment,
    title varchar(125) NOT NULL default '',
    alias varchar(125) NOT NULL default '',
    content TEXT NULL,
    posted int(10) NOT NULL default '0',
    ticketversion smallint(3) NULL,
    fixedin smallint(3) NULL,
    reportedbyid int(10) NOT NULL default '0',
    assignedtoid int(10) NOT NULL default '0',
    tickettype smallint(3) NULL,
    priority smallint(3) NULL,
    ticketstatus smallint(3) NULL,
    ticketcategory smallint(3) NULL,
    projectid smallint(3) NULL,
    milestone smallint(3) NULL,
    keywords varchar(125) NOT NULL default '',
    lastcommentid int(10) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY projectid (projectid),
	KEY reportedbyid (reportedbyid),
	KEY assignedtoid (assignedtoid),
	KEY ticketversion (ticketversion),
	KEY ticketstatus (ticketstatus),
	KEY keywords (keywords),
	KEY lastcommentid (lastcommentid)
);

CREATE TABLE IF NOT EXISTS tickets_comments
(
	id int(10) NOT NULL auto_increment,
    ticketid int(10) NOT NULL default '0',
    posted int(10) NOT NULL default '0',
    userid int(10) NOT NULL default '0',
    content TEXT NULL,
    firstcomment tinyint(1) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY ticketid (ticketid),
	KEY firstcomment (firstcomment),
	KEY userid (userid)
);

CREATE TABLE IF NOT EXISTS tickets_changes
(
	id int(10) NOT NULL auto_increment,
    ticketid int(10) NOT NULL default '0',
    commentid int(10) NOT NULL default '0',
    posted int(10) NOT NULL default '0',
    userid int(10) NOT NULL default '0',
    content TEXT NULL,
	PRIMARY KEY (id),
	KEY ticketid (ticketid),
	KEY commentid (commentid),
	KEY userid (userid)
);

CREATE TABLE IF NOT EXISTS tickets_priorities
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	backgroundcolor char(30) NOT NULL default '#ffffff',
	color char(30) NOT NULL default '#ffffff',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tickets_versions
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tickets_types
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tickets_statuses
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	backgroundcolor char(30) NOT NULL default '#ffffff',
	color char(30) NOT NULL default '#ffffff',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tickets_categories
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS milestones
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	alias varchar(125) NOT NULL default '',
	PRIMARY KEY (id)
);


-- Insert sample DATA

INSERT INTO tickets_statuses VALUES (NULL, 'New', '#93ce11', '#fff', 'new'), (NULL, 'Closed', '#dedede', '#fff', 'closed'), (NULL, 'Fixed', '#df6800', '#fff', 'fixed');

INSERT INTO tickets_priorities VALUES (NULL, 'Low', '#93ce11', '#fff', 'low'), (NULL, 'Medium', '#dedede', '#fff', 'medium'), (NULL, 'High', '#df6800', '#fff', 'high');

INSERT INTO tickets_categories VALUES (NULL, 'Category One', 'category-one'), (NULL, 'Category Two', 'category-two'), (NULL, 'Category Three', 'category-three');

INSERT INTO milestones VALUES (NULL, '1.0', '1-0'), (NULL, '2.0', '2-0'), (NULL, '3.0', '3-0'), (NULL, '4.0', '4-0');

INSERT INTO tickets_versions VALUES (NULL, '1.0', '1-0'), (NULL, '2.0', '2-0'), (NULL, '3.0', '3-0'), (NULL, '4.0', '4-0');

INSERT INTO tickets_types VALUES (NULL, 'Type One', 'type-one'), (NULL, 'Type Two', 'type-two'), (NULL, 'Type Three', 'type-three');