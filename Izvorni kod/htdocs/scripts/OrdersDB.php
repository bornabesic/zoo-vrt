<?php

	require_once (__DIR__ . "/config.php");

	class OrdersDB{

		private $db;

		public function __construct($database){
			$this->db=$database;
		}

		function add_order($name, $parent_class_id){
			//ADD ORDER
			$add_order_query = "INSERT INTO " . DB_NAME . ".orders (`name`, `parent_class_id`) VALUES (?,?);";
			$add_order_statement = $this->db->prepare($add_order_query);
			if($add_order_statement){
				$add_order_statement->bind_param("si", $name, $parent_class_id);
		     	$add_order_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			// GET ORDER ID
			$order_id_query = "SELECT * FROM " . DB_NAME . ".orders WHERE name=?;";
			$order_id_statement = $this->db->prepare($order_id_query);
			if($order_id_statement){
				$order_id_statement->bind_param("s", $name);
		     	$order_id_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$order_id_result = $order_id_statement->get_result();
			$row = $order_id_result->fetch_assoc();

			return json_encode(array(
				"order_id" => $row['order_id'],
				"name" => $name,
				"parent_class_id" => $row['parent_class_id']
			));
		}

		function remove_order($order_id){
			//remove order with given id
			$delete_query = "DELETE FROM " . DB_NAME . ".orders WHERE order_id=?;";
			$delete_statement = $this->db->prepare($delete_query);
			if($delete_statement){
				$delete_statement->bind_param("i", $order_id);
				$delete_statement->execute();
			}else{
				return json_encode(
					array( "error" => $this->db->error )
				);
			}

			if($this->db->errno != 0){
				return json_encode(array(
					"error" => $this->db->error
				));
			}

			switch ($delete_statement->affected_rows) {
				case -1:
					return json_encode(array(
						"error" => $this->db->error
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Order with the given ID could not be deleted."
					));
					break;
			};
			return json_encode(array(
				"order_id" => $order_id
			));
		}

		function get_orders($parent_class_id){
			if($parent_class_id>=0) $condition=" WHERE parent_class_id=?";
			else $condition="";

			$orders_query = "SELECT * FROM " . DB_NAME . ".orders" . $condition . ";";
			$orders_statement = $this->db->prepare($orders_query);
			if($orders_statement){
				if($parent_class_id>=0) $orders_statement->bind_param("i", $parent_class_id);
		     	$orders_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			$orders_result = $orders_statement->get_result();

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			$orders = array();
			while ($row = $orders_result->fetch_assoc()) {
				array_push($orders, array(
					"order_id" => $row["order_id"],
					"name" => $row["name"],
					"parent_class_id" => $row["parent_class_id"]
				));
    		}

			return json_encode($orders);
		}

		function update_order($order_id, $name, $parent_class_id){
			$update_query="UPDATE ". DB_NAME . ".orders SET `name`=?, `parent_class_id`=? WHERE `order_id`=?;";
			$update_statement=$this->db->prepare($update_query);
			if($update_statement){
				$update_statement->bind_param("sii", $name, $parent_class_id, $order_id);
		     	$update_statement->execute();
			}
			else {
				return json_encode(
							array( "error" => $this->db->error )
						);
			}

			if($this->db->errno!=0){
				return json_encode(array(
					"error" => $this->db->error 
				));
			}

			switch($update_statement->affected_rows){
				case -1:
					return json_encode(array(
						"error" => $this->db->error 
					));
					break;
				case 0:
					return json_encode(array(
						"error" => "Order with the given ID could not be updated." 
					));
					break;
			};

			return json_encode(array(
				"order_id" => $order_id
			));
		}

	}

?>