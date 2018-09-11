<?php

namespace Core;

use Core\Database\DatabaseModel as DB;


class Model {

	protected $db;

	// Table name
	protected $table = '';

	// ID field used in conditions. E.g. WHERE id = ...
	protected $id_field = 'id';

	// Fields in the SELECT query
	protected $select_fields = '*';

	// List of fields that can be updated in the Model/Database
	protected $fields = [];

	// Set created_at/updated_at values whith create/update method calls
	protected $update_timestamps = true;


	public function __construct($id = NULL)
	{
		$this->db = DB::getInstance();

		if($this->table === '')
		{
			$this->table = $this->className();
		}

		if($id)
		{
			$this->findById($id);
		}
	}

	protected function className()
	{
		 $namespace = static::class;
		 $class = end(explode('\\', $namespace));
		 return strtolower($class);
	}

	public function find($id = NULL)
	{
		$q = "SELECT $this->select_fields FROM $this->table";
		if($id)
		{
			$q .= " WHERE $this->id_field = '$id'";
		}

		$rows = $this->db->getRows($q);

		return $rows;
	}

	public function count()
	{
		$q = "SELECT COUNT($this->id_field) AS count FROM $this->table";
		if($this->id)
		{
			$q .= " WHERE $this->id_field = '$this->id'";
		}

		$rows = $this->db->getRows($q);

		if(count($rows))
		{
			return (int)$rows[0]->count;
		}

		return 0;
	}

	public function findById($id)
	{
		$rows = $this->find($id);

		if(count($rows))
		{
			$row = end($rows);

			foreach ($row as $key => $value) {
				$this->$key = $value;
			}

			return $this;
		} else {
			return NULL;
		}
	}

	public function create()
	{
		$values = [];
		foreach ($this->fields as $field) {
			$values[$field] = $this->$field;
		}

		if($this->update_timestamps) {
			$values['created_at'] = date('Y-m-d H:i:s');
		}

		$inserted_id = $this->db->insert($values, $this->table);

		if($inserted_id) {
			return $this->findById($inserted_id);
		} else {
			return false;
		}
	}

	public function createRaw()
	{
		$values = [];
		foreach ($this->fields as $field) {
			$values[] = "'".$this->$field."'";
		}

		$valuesStr = implode(', ', $values);
		$fieldsStr = implode(', ', $this->fields);

		$q = "INSERT INTO $this->table ($fieldsStr) VALUES ($valuesStr)";

		$r = $this->db->query($q);

		if($r) {
			$id = $this->db->last_id();

			return $this->findById($id);
		} else {
			return false;
		}
	}

	public function update()
	{
		$values = [];
		foreach($this->fields as $field) {
			$values[$field] = $this->$field;
		}
		
		$r = $this->db->update($this->id, $values, $this->table, $this->id_field);

		if($r) {
			return $this;
		} else {
			return false;
		}
	}

	public function updateRaw()
	{
		$values = [];
		foreach($this->fields as $field) {
			$values[] = $field." = '".$this->$field."'";
		}
		
		$valuesStr = implode(', ', $values);

		$q = "UPDATE $this->table SET $valuesStr WHERE $this->id_field = '$this->id'";

		return $this->db->query($q);
	}

	public function delete($id = NULL)
	{
		if($id === NULL)
		{
			$id = $this->id;
		}

		$q = "DELETE FROM $this->table WHERE $this->id_field = '$id'";

		return $this->db->query($q);
	}

	public function deleteIfExists($id = NULL)
	{
		if($id)
		{
			$this->id = $id;
		}

		if($this->count())
		{
			$q 	= "DELETE FROM $this->table WHERE $this->id_field = '$this->id'";

			return $this->db->query($q);
		}

		return false;
	}

}