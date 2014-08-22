<?php
namespace Nayjest\Grids;

use BaseController;
use Carbon\Carbon;
use DB;
use Input;
use Symfony\Component\HttpKernel\Exception\HttpException;
use View;

class Controller extends BaseController
{

    public function getIndex()
    {
        throw new HttpException("404");
    }
} 