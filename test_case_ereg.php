<?php
$test = "other";

switch(true){
  case go_reg("one\_", $test):{
    echo "good one";
	break;
  }
  case go_reg("two\_", $test):{
    echo "good two";
	break;
  }
  default:{
    echo "default";
	break;
  }
}
?>