<?php

namespace Vortice\Interfaces;

use Vortice\Request;

interface Controllable{

    public function __construct(Request $r);

}