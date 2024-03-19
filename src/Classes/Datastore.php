<?php

namespace App\Classes;
use Iterator;

class Datastore implements Iterator{
    
   private string $filename;
   private array $data;
   private string $position;
   private array $keys;
   
   public function __construct(string $filename) {
      $this->filename = $filename;
      $this->data = $this->load();
      $this->keys = array_keys($this->data);
   }
   
   private function load()
   {
      $data = json_decode(file_get_contents($this->filename), associative: true);
      return $data;
   }
   
   public function save(){
      file_put_contents($this->filename, json_encode($this->data, JSON_PRETTY_PRINT));
   }

   public function set(string $attribute, mixed $value){
      $this->data[$attribute] = $value;
   }
   
   public function get(string $attribute){
      return $this->data[$attribute];
   }
   
   // Iterator implementations
   public function rewind() {
      $this->position = 0;
  }

  public function valid() {
      return isset($this->keys[$this->position]);
  }

  public function key() {
      return $this->keys[$this->position];
  }

  public function current() {
      return $this->data[$this->key()];
  }

  public function next() {
      $this->position++;
  }
}