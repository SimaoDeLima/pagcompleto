<?php

use GuzzleHttp\Client;

class PagCompleto {

   private static $baseUri = "https://api11.ecompleto.com.br/";
   private static $tokenAccess = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdG9yZUlkIjoiNCIsInVzZXJJZCI6IjkwNDAiLCJpYXQiOjE2NzQxNTQ2OTQsImV4cCI6MTY3NDg3NDc5OX0.2Y0BIi_UYJ5Vtt9LRghvB64zaR1JJHZ8LVy6_wv3cQ8";
   private $client;

   public function __construct($endpoint) {
      if (!$this->client) {
         $this->client = new GuzzleHttp\Client([
            'base_uri' => self::$baseUri . $endpoint . "?accessToken=" . self::$tokenAccess,
            'http_errors' => false
         ]);
      }
   }

   public function processTransaction ($order) {
      $body = json_encode([
         "external_order_id" => $order->id_pedido,
         "amount" => (double) $order->valor_total,
         "card_number" => $order->num_cartao,
         "card_cvv" => (string) $order->codigo_verificacao,
         "card_expiration_date" => Order::treatExpireDateCardFormat($order->vencimento),
         "card_holder_name" => $order->nome_portador,
         "customer" => [
            "external_id" => $order->id_cliente,
            "name" => $order->nome,
            "type" => ($order->tipo_pessoa == "F") ? "individual" : "corporation",
            "email" => $order->email,
            "documents" => [
               [
                  "type" => ($order->tipo_pessoa == "F") ? "cpf" : "cnpj",
                  "number" => $order->cpf_cnpj
               ]
            ],
            "birthday" => $order->data_nasc
         ]
      ]);
      $request = $this->client->request("POST", "", ["body" => $body]);
      return $request->getBody();
   }
}