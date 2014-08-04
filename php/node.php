<?php
  include(NEMEX_PATH.'auth.php');

class node {

  var $name;
  var $type;
  var $time;
  var $unixtime;
  var $project;
  var $content;


  function __construct( $nn, $pp ) {
   $this->name = basename($nn);
   $this->project = $pp;

   $this->extractTime();
   $this->type = $this->extractType();
   $this->content = file_get_contents(NEMEX_PATH.'projects/'.$this->project.'/'.$this->name);
  }




 function extractTime() {
  $this->unixtime = basename($this->name);
  $this->time = date("j. F Y",  basename($this->name));;
 }



function extractType() {
  $path_parts = pathinfo(NEMEX_PATH.'projects/'.$this->project.'/'.$this->name);
  if($path_parts['extension'] == 'jpg' || $path_parts['extension'] == 'jpeg' || $path_parts['extension'] == 'JPG'|| $path_parts['extension'] == 'png' || $path_parts['extension'] == 'gif')
   return 'img';
 else if($path_parts['extension'] == 'txt')
   return 'txt';
  else if($path_parts['extension'] == 'md')
   return 'md';
 }

function getDate() {
  return $this->time;
}

function getName() {
  return $this->name;
}

function getContent() {
  return $this->content;
}

function getType() {
  return $this->type;
}
}

?>