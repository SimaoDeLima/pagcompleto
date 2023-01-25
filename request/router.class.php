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
      }
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
      $requisition = self::$location[$this->getRequestType()][$this->getRequisition()];
      return $requisition["load"];
   }

   private function execute($preference = null) : void {
      require "request/" . $this->getRequestType() . "/" . $this->getLocation();
   }
}