<?php

class ErrorController extends Controller {

	public function handler(){
		echo "<p>Este é o controller de erro</p>";
		echo "<pre>" . p('exception') . "</pre>";
	}

}
