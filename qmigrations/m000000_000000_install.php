<?php
namespace quarsintex\quartronic\qmigrations;

class m000000_000000_install extends  \quarsintex\quartronic\qmodels\qmigration
{
	public function up()
	{
        echo "Creating table `qmigration`...";
		self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS `qmigration` (
			  name VARCHAR,
			  apply_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)
		');
		echo " Success!\n";

        echo "Creating table `quser`...";
        self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS `quser` (
			  id INTEGER PRIMARY KEY,
			  username VARCHAR,
			  passhash VARCHAR
			)
		');
        echo "      Success!\n";

        echo "Creating table `qrole`...";
        self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS `qrole` (
			  id integer PRIMARY KEY AUTOINCREMENT,
			  name varchar
			)
		');
        echo "      Success!\n";

        echo "Creating table `qgroup`...";
        self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS `qgroup` (
			  id integer PRIMARY KEY AUTOINCREMENT,
			  name varchar
			)
		');
        echo "     Success!\n";
	}

	public function down()
	{
        echo "Dropping table `qgroup`...";
        self::$Q->db->exec('DROP TABLE IF EXISTS `qgroup`');
        echo " Success!\n";

        echo "Dropping table `qrole`...";
        self::$Q->db->exec('DROP TABLE IF EXISTS `qrole`');
        echo "  Success!\n";

        echo "Dropping table `quser`...";
        self::$Q->db->exec('DROP TABLE IF EXISTS `quser`');
        echo "  Success!\n";
	}
}
