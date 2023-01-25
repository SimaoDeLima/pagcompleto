<?php

class Order {

   private $orders;

   public $id_pedido;
   public $id_gateway;
   public $id_formapagto;
   public $valor_total;
   public $num_cartao;
   public $codigo_verificacao;
   public $vencimento;
   public $nome_portador;
   public $id_cliente;
   public $nome;
   public $tipo_pessoa;
   public $email;
   public $cpf_cnpj;
   public $data_nasc;
   public $id_situacao;

   public $situation;
   public $transactionResponse;

   public $orderRequire;

   private $creditCard = 3;
   private $waitingForPayment = 1;
   private $orderPaid = 2;
   private $PagCompleto = 1;

   public function __construct($order = null) {
   
      require_once "data/order.model.class.php";

      $this->orders = new ModelOrder();

      if (!empty($order)) {
         $this->orders->id = $order;
      }
   }

   public function __destruct() {
      $this->orders = null;
   }

   public function requireShopUsingPagcompleto() {
      if ($this->id_gateway != $this->PagCompleto) {
         $this->orderRequire .= "- Esta loja não utiliza integração com o PagCompleto. </br>";
      }
   }

   public function requireCreditCardPaid() {
      if ($this->id_formapagto != $this->creditCard) {
         $this->orderRequire .= "- O pedido não foi pago com cartão de crédito. </br>";
      }
   }

   public function requireWaitingForPayment() {
      
      if ($this->id_situacao == $this->orderPaid) {
         $this->orderRequire .= "- Este pedido já foi processado. </br>";
      }
      else if ($this->id_situacao != $this->waitingForPayment) {
         $this->orderRequire .= "- Para ser processado o pedido deve estar aguardando pagamento </br>";
      }
   }

   public function getOrder() : Order {

      $this->orders->method = $this->creditCard;
      $this->orders->status = $this->waitingForPayment;

      return $this->orders->loadOrder();
   }

   public function saveOrder() {
      
      $this->orders->id = $this->id_pedido;
      $this->orders->transactionResponse = $this->transactionResponse;
      $this->orders->dateOrderProcessed = date("Y-m-d");
      
      $this->orders->updateOrderTo($this->situation);
   }
   
   public static function treatExpireDateCardFormat($date) : string {
      
      if (empty($date)) {
         return "";
      }

      $newDate = explode("-", $date);
      $date = $newDate[1] . substr($newDate[0], -2);

      return $date;
   }


}