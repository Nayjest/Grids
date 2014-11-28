<?php
namespace Nayjest\Grids;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{

    public function getIndex()
    {
        throw new HttpException("404");
    }
}