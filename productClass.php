<?php
class ProductClass{
  var $name;
  var $category;
  var $desription;
  var $buyingPrice;
  var $sellingPrice;
  var $company;
  var $image;
  function ProductClass(){
  $this->name="";
  $this->category="";
  $this->description="";
  $this->buyingPrice=0.00;
  $this->sellingPrice=0.00;
  $this->company="";
  $this->image="";
  }
  function getName(){
    return $this->name;
  }
  function setName($name){
    $this->name=$name;
  }
  function getCategory(){
    return $this->category;
  }
  function setCategory($cat){
    $this->category=$cat;
  }
  function getDescription(){
    return $this->description;
  }
  function setDescription($desc){
    $this->description=$desc;
  }
  function getBuyingPrice(){
    return $this->buyingPrice;
  }
  function setBuyingPrice($bp){
    $this->buyingPrice=$bp;
  }
  function getSellingPrice(){
    return $this->sellingPrice;
  }
  function setSellingPrice($sp){
    $this->sellingPrice=$sp;
  }
  function getCompany(){
    return $this->company;
  }
  function setCompany($comp){
    $this->company=$comp;
  }
  function getImage(){
    return $this->image;
  }
  function setImage($image){
    $this->image=$image;
  }
}
?>
