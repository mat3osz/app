
-- Creates table for API Key information.  This includes information about the app itself, contact info related to the app,
-- and any rate-limiting rules.
--
DROP TABLE IF EXISTS apiGate_keys;
CREATE TABLE apiGate_keys (
	user_id INT(11) NOT NULL, -- foreign key to apiGate_users.id (which may be the id of whatever external system you are using for users)
	apiKey VARCHAR(255) NOT NULL, -- the actual API key. If generated by API Gate (as opposed to being imported from a legacy system) this will be a somewhat random hex string.
	nickName VARCHAR(255) DEFAULT NULL, -- nickname for the API key. If null, the app will use the apiKey as the visual name of the key.
	
	-- Contact info
	email TINYTEXT NOT NULL, -- required so that someone can be contacted in emergencies (their app has an obvious bug, has gone over the rate-limit, etc.)
	firstName VARCHAR(255) NOT NULL,
	lastName VARCHAR(255) NOT NULL
);

-- Creates table for users this can be tied directly
-- to the user id of another system if you just want to grant API keys to existing users.
-- TODO: DO WE NEED THIS TABLE FOR ANYTHING IF WE'RE USING ANOTHER SYSTEM FOR USER_IDs?
DROP TABLE IF EXISTS apiGate_users (
	id INT(11) NOT NULL AUTOINCREMENT, -- If you are using a separate system for the auth (and just trying API Gate to those accounts, then force-set this id.

	PRIMARY KEY(id),
);

----
-- STATS
-- These won't all be kept indefinitely, for example the hourly stats will probably be rolled up into daily numbers
-- and deleted every week or so.
----

DROP TABLE IF EXISTS apiGate_stats_hourly;
CREATE TABLE apiGate_stats_hourly (
	apiKey VARCHAR(255) NOT NULL,
	startOfPeriod DATETIME,
	hits BIGINT DEFAULT 0,

	UNIQUE KEY (apiKey, startOfPeriod)
);

DROP TABLE IF EXISTS apiGate_stats_daily;
CREATE TABLE apiGate_stats_daily (
	apiKey VARCHAR(255) NOT NULL,
	startOfPeriod DATETIME,
	hits BIGINT DEFAULT 0,

	UNIQUE KEY (apiKey, startOfPeriod)
);

DROP TABLE IF EXISTS apiGate_stats_weekly;
CREATE TABLE apiGate_stats_weekly (
	apiKey VARCHAR(255) NOT NULL,
	startOfPeriod DATETIME,
	hits BIGINT DEFAULT 0,
	
	UNIQUE KEY (apiKey, startOfPeriod)
);

DROP TABLE IF EXISTS apiGate_stats_monthly;
CREATE TABLE apiGate_stats_monthly (
	apiKey VARCHAR(255) NOT NULL,
	startOfPeriod DATETIME,
	hits BIGINT DEFAULT 0,
	
	UNIQUE KEY (apiKey, startOfPeriod)
);
