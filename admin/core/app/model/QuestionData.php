<?php
class QuestionData {
	public static $tablename = "question";


	public function __construct(){
		$this->title = "";
		$this->content = "";
		$this->image = "";
		$this->user_id = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	public function getStatus(){ return StatusData::getById($this->status_id);}
	public function getClient(){ return ClientData::getById($this->client_id);}
	public function getProduct(){ return ProductData::getById($this->product_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (comment,product_id,client_id,created_at) ";
		$sql .= "value (\"$this->comment\",$this->product_id,\"$this->client_id\",$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_answer(){
		$sql = "insert into ".self::$tablename." (comment,product_id,user_id,question_id,status_id, created_at) ";
		$sql .= "value (\"$this->comment\",$this->product_id,\"$this->user_id\",$this->question_id, $this->status_id, $this->created_at)";
		return Executor::doit($sql);
	}


	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto QuestionData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		Executor::doit($sql);
	}

	public function cancel(){
		$sql = "update ".self::$tablename." set status_id=3 where id=$this->id";
		Executor::doit($sql);
	}


	public function update_status(){
		$sql = "update ".self::$tablename." set status_id=\"$this->status_id\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new QuestionData());
	}

	public static function getAnswerByQId($id){
		$sql = "select * from ".self::$tablename." where question_id=$id and status_id=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}


	public static function getAvg($id){
		$sql = "select avg(rating) as avg from ".self::$tablename." where status_id=1 and product_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new QuestionData());
	}

	public static function countByStatusId($id){
		$sql = "select count(*) as c from ".self::$tablename." where status_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new QuestionData());
	}


	public static function getByCP($c,$p){
		$sql = "select * from ".self::$tablename." where client_id=\"$c\" and product_id=$p";
		$query = Executor::doit($sql);
		return Model::one($query[0],new QuestionData());
	}

	public static function getByPreffix($id){
		$sql = "select * from ".self::$tablename." where short_name=\"$id\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new QuestionData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}

	public static function getAllByDate($date){
		$sql = "select * from ".self::$tablename." where date(created_at)=\"$date\"";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}

	public static function getByRange($start,$end){
		$sql = "select * from ".self::$tablename." where (created_at>=\"$start\" and created_at<=\"$end\") order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}


	public  function getTotal(){
		$products = BuyProductData::getAllByBuyId($this->id);
		$total=0;
		foreach ($products as $px) {
			$p = ProductData::getById($px->product_id);
			$total+=$p->price*$px->q;
		}
		return $total;
	}


	public static function getPublicsByProductId($id){
		$sql = "select * from ".self::$tablename." where product_id=$id and status_id=1 and question_id is NULL order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}


	public static function getPublics(){
		$sql = "select * from ".self::$tablename." where is_public=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new QuestionData());
	}

}

?>