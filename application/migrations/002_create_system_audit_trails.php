<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_system_audit_trails extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
		$this->table_name = 'system_audit_trails';
	}

	public function up()
	{
		$this->dbforge->add_field([
			'audit_id' => ['type' => 'BIGINT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'user_id' => ['type' => 'BIGINT', 'null' => TRUE, 'unsigned' => TRUE],
			'role_id' => ['type' => 'BIGINT', 'null' => TRUE, 'unsigned' => TRUE],
			'user_fullname' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE, 'comment' => ''],
			'event' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => TRUE, 'comment' => ''],
			'table_name' => ['type' => 'VARCHAR', 'constraint' => '80', 'null' => TRUE, 'comment' => ''],
			'old_values' => ['type' => 'LONGTEXT', 'null' => TRUE, 'comment' => ''],
			'new_values' => ['type' => 'LONGTEXT', 'null' => TRUE, 'comment' => ''],
			'url' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => TRUE, 'comment' => ''],
			'ip_address' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => TRUE, 'comment' => ''],
			'user_agent' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => TRUE, 'comment' => ''],
			'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		]);

		$this->dbforge->add_key('audit_id', TRUE);
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
