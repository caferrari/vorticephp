<?php

class Link{

	public function __construct($link='', $pars=''){
		
		$pars = str_replace('&', '/', $pars);
		$pars = str_replace('=', ':', $pars);
		
		$tmp = virtualroot . $link . '/' . $pars . '/';
		$tmp = preg_replace('@/+@', '/', $tmp);
		
		$this->link = $tmp;
		
	}
	
	public function __toString(){
		return $this->link;
	}


}
