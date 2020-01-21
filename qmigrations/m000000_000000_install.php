<?php
namespace quarsintex\quartronic\qmigrations;

class m000000_000000_install extends  \quarsintex\quartronic\qmodels\qmigration
{
	public function up()
	{
		self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS qmigration(
			  name VARCHAR,
			  apply_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)
		');

        self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS quser(
			  id INTEGER PRIMARY KEY,
			  username VARCHAR,
			  passhash VARCHAR
			)
		');
	}

	public function down()
	{
        self::$Q->db->exec('DROP TABLE IF EXISTS quser');
	}
}
