<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_system_logger extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
		$this->table_name = 'system_logger';
	}

	public function up()
	{
		$this->dbforge->add_field([
			'log_id' => ['type' => 'BIGINT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'errno' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE, 'comment' => ''],
			'errtype' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE, 'comment' => ''],
			'errstr' => ['type' => 'TEXT', 'constraint' => NULL, 'null' => TRUE, 'comment' => ''],
			'errfile' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE, 'comment' => ''],
			'errline' => ['type' => 'TEXT', 'constraint' => NULL, 'null' => TRUE, 'comment' => ''],
			'user_agent' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'comment' => ''],
			'ip_address' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'comment' => ''],
			'time' => ['type' => 'TIMESTAMP', 'constraint' => NULL, 'null' => TRUE, 'comment' => ''],
			'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		]);

		$this->dbforge->add_key('log_id', TRUE);
		if ($this->dbforge->create_table($this->table_name, FALSE, ['ENGINE' => 'InnoDB', 'COLLATE' => 'utf8mb4_general_ci'])) {
			if (!isAjax()) {
				$this->seeder();
				echo "<p> <span style='color: blue;'><b> {$this->table_name} </b></span> successfully created! </p>";
			} else {
				echo "<b> {$this->table_name} </b></span> created";
			}
		}
	}

	public function down()
	{
		$this->dbforge->drop_table($this->table_name, TRUE);
		if (isAjax()) {
			echo "<b> {$this->table_name} </b></span> drop";
		}
	}

	public function seeder()
	{
		// empty function
		if (isAjax()) {
			echo "<b> {$this->table_name} </b></span> seed";
		}
	}

	public function truncate()
	{
		if (isAjax()) {
			ci()->db->truncate($this->table_name);
			echo "<b> {$this->table_name} </b></span> truncate";
		}
	}
}
