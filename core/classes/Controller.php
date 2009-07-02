<?php
class Controller {
	function __set($met, $val){
		DAO::add($val, $met);
	}
}
