<?php

require "api/pagcompleto/pagcompleto.class.php";
require "classe/order.class.php";

$orders = new Order(Router::get("order"));

$order = $orders->getOrder();

if (!$order->id_pedido) {
   
   $order = null;
   $orders = null;

   die("Pedido não encontrado.");
}
   
$order->requireWaitingForPayment();
$order->requireShopUsingPagcompleto();
$order->requireCreditCardPaid();

if ($order->orderRequire) {
   
   echo "Não foi possível processar o pedido Nº " . Router::get("order") . " </br> Motivo(s) : </br> $order->orderRequire";

   $orders = null;
   $order = null;

   die;
}

$pagcompleto = new PagCompleto("exams/processTransaction");
$response = $pagcompleto->processTransaction($order);

$transaction = json_decode($response);

if ($transaction->Error == false) {
   
   if ($transaction->Transaction_code == "00") {
      $order->situation = "orderPaid";
   }
   else if ($transaction->Transaction_code == "01") {
      $order->situation = "inProcess";
   }
   else if ($transaction->Transaction_code == "03" || $transaction->Transaction_code == "04") {
      $order->situation = "orderCancelled";
   }  
}

$order->transactionResponse = json_encode($transaction);

if ($order->transactionResponse) {

   $order->saveOrder();
   print("Pedido " . $order->id_pedido . " processado.");   
}


$order = null;
$orders = null;