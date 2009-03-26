<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */


/**
 * Application core
 * It'll be executed in all requests
 * @package SampleApp
 */

Banco::getInstance()->inicializa("mysql.ferrari.eti.br", "exemplobd", "exemplobd", "exemplodb");
//Banco::getInstance('another_one')->inicializa("database2_ip", "database2_user", "database2_pass", "database2_name", BD_PGSQL);

Template::setVar("mensagem", Post::renderMsg());

//Template::setClean(); // Limpa codigo html antes de mandar para o navegador

