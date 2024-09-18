<?php
class CategoryData {
	public static $tablename = "category";


	public function __construct(){
		$this->title = "";
		$this->content = "";
		$this->image = "";
		$this->user_id = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,short_name,is_active) ";
		$sql .= "value (\"$this->name\",\"$this->short_name\",$this->is_active)";
		Executor::doit($sql);
	}

	public function add_sub(){
		$sql = "insert into ".self::$tablename." (category_id, name,short_name,is_active) ";
		$sql .= "value ($this->category_id, \"$this->name\",\"$this->short_name\",$this->is_active)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto CategoryData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",short_name=\"$this->short_name\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_sub(){
		$sql = "update ".self::$tablename." set category_id=\"$this->category_id\",name=\"$this->name\",short_name=\"$this->short_name\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryData());
	}

	public static function getByPreffix($id){
		$sql = "select * from ".self::$tablename." where short_name=\"$id\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getAllRoot(){
		$sql = "select * from ".self::$tablename." where category_id is NULL";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getAllSubs(){
		$sql = "select * from ".self::$tablename." where category_id is not NULL";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getAllByCatId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getPublics(){
		$sql = "select * from ".self::$tablename." where is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getPublicsRoot(){
		$sql = "select * from ".self::$tablename." where is_active=1 and category_id is NULL";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getPublicsByCatId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}
}

?>