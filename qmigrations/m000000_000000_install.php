<?php
namespace quarsintex\quartronic\qmigrations;

use quarsintex\quartronic\qcore\QCrud;

class m000000_000000_install extends  \quarsintex\quartronic\qmodels\qmigration
{
	public function up()
	{
        echo "\n";
        echo "Preparing table `qmigration`...";
		self::$Q->db->exec('
			CREATE TABLE IF NOT EXISTS `qmigration` (
			  name VARCHAR,
			  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)
		');
		echo "\nSuccess!\n";

        QCrud::autostructDB();
	}

	public function down()
	{
	    foreach (QCrud::getAutoStructure() as $name => $sql) {
            echo "\n";
            echo 'Dropping table "q'.$name.'"...';
            self::$Q->db->exec('DROP TABLE IF EXISTS `q'.$name.'`');
            echo "\nSuccess!\n";
        }
	}
}
