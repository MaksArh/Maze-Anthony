<?php 

class Cell {
  public $x;
  public $y;
  
  public function __construct($x = 0, $y = 0) {
    $this->x = $x;
    $this->y = $y;
  }
}

class Unit {
  public $curr;
  public $last;
  
  public function __construct(Cell $curr,Cell $last) {
    $this->curr = $curr;
    $this->last = $last;
  }
}


class Stack {
  public $main;
  public $len;
  public $curr;
  public $last;

  public function __construct(Cell $curr,Cell $last) {
    $this->main = [new Unit($curr, $last)];
    $this->len = 1;
    $this->curr = $curr;
    $this->last = $last;
    $this->reloadHand();
  }
  
  public function add(Cell $curr,Cell $last) {
    $this->main[] = new Unit($curr, $last);
    $this->curr = $curr;
    $this->last = $last;
    $this->len++;
  }
  
  public function reloadHand() {
    $this->curr = $this->main[$this->len - 1]->curr;
    $this->last = $this->main[$this->len - 1]->last;
  }
  
  public function remove() {
    array_pop($this->main);
    $this->len--;
    if ($this->len != 0) {
      $this->reloadHand();
    }
  }
  
  public function moveCell(Cell $next) {
    $this->main[$this->len - 1]->last = $this->main[$this->len - 1]->curr;
    $this->main[$this->len - 1]->curr = $next;
    $this->reloadHand();
  }

  public function getLength(){
    return $this->len;
  }
  public function out(){
    echo $len.'<br>';
    for ($i=0;$i<$this->len;$i++){
      echo $this->main[$i]->last->x."-".$this->main[$i]->last->y."<br>";
      echo $this->main[$i]->curr->x."-".$this->main[$i]->curr->y."<br><br>";
    }
  }
} 
 ?>