<?php

class Router {

   private static $requestTypeAllowed = ["page", "operation"];
   private static $request = [];
   private static $location = [
      "operation" => [
         "processOrderPayment" => [
            "load" => "processOrderPayment.php"
         ]
      ]
   ];
   private $requestType = "";

   public function __construct($request) {

      if ($request) {

         $this->setRequest($request);

         if (!$this->requestAllowed()) {
            die("Página não encontrada.");
         }

         $this->execute();

         return;
      }

      die("Requisição não fornecida.");
   }

   public static function get($param) {
      if (!empty(self::$request[$param]))  {
         return self::$request[$param];
      }
   }

   private function requestAllowed() {
      if (in_array($this->getRequestType(), self::$requestTypeAllowed)) {
         return true;
      }
   }

   private function setRequest($request) : void {
      self::$request = $request;
   }

   private function getRequestKeys() : array {
      return array_keys(self::$request);
   }

   private function getRequestType() : string {
      //posição 0 será sempre a solicitação de requisição da página
      return $this->getRequestKeys()[0];
   }

   private function getRequisition() : string {
      return self::$request[$this->getRequestType()];
   }

   private function getLocation() : string {
      
      $href = "";

      if (!empty(self::$location[$this->getRequestType()][$this->getRequisition()])) {
         $requisition = self::$location[$this->getRequestType()][$this->getRequisition()];
         $href = $requisition["load"];
      }

      return $href;
   }

   private function execute($preference = null) : void {
      
      if ($this->getLocation()) {
         
         require "request/" . $this->getRequestType() . "/" . $this->getLocation();
         return;
      }

      die("Não é possível processar esta requisição.");
   }
}