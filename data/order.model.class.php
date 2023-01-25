<?php

class ModelOrder {
   
   public $transactionResponse;
   public $dateProcessed;
   
   public $id;
   public $method;
   public $status;

   private $conn;

   private $inProcess = 1;
   private $orderPaid = 2;
   private $orderCancelled = 3;

   public function __construct() {
      
      require_once "database/conn/connection.class.php";

      $db = new Database();
      $this->conn = $db->connect();
   }

   public function __destruct() {
      $this->conn = null;
   }

   public function loadOrder() : Order {
      $sql = "SELECT * FROM pedidos 
      INNER JOIN clientes on pedidos.id_cliente = clientes.id
      INNER JOIN pedidos_pagamentos on pedidos_pagamentos.id_pedido = pedidos.id
      INNER JOIN lojas_gateway ON clientes.id_loja = lojas_gateway.id_loja
      WHERE pedidos.id = :order AND pedidos_pagamentos.id_formapagto = :paymentMethod 
      AND pedidos.id_situacao = :paymentStatus";

      $query = $this->conn->prepare($sql);
      $query->bindParam(':order', $this->id, PDO::PARAM_INT);
      $query->bindParam(':paymentMethod', $this->method, PDO::PARAM_INT);
      $query->bindParam(':paymentStatus', $this->status, PDO::PARAM_INT);
      $query->setFetchMode(PDO::FETCH_CLASS, "Order");
      $query->execute();

      return $query->rowCount() == 1 ? $query->fetch() : new Order();
   }

   public function updateOrderTo($situation) {
      $sql = "UPDATE pedidos_pagamentos SET retorno_intermediador = :transactionResponse, data_processamento = :date WHERE id_pedido = :order";
      $query = $this->conn->prepare($sql);
      $query->bindParam(":transactionResponse", $this->transactionResponse, PDO::PARAM_STR);
      $query->bindParam(":date", $this->dateOrderProcessed, PDO::PARAM_STR);
      $query->bindParam(":order", $this->id, PDO::PARAM_INT);
      $query->execute();
      $sql = "UPDATE pedidos SET id_situacao = :situation WHERE id = :order";
      $query = $this->conn->prepare($sql);
      $query->bindParam(":situation", $this->$situation, PDO::PARAM_INT);
      $query->bindParam(":order", $this->id, PDO::PARAM_INT);
      $query->execute();
   }
}