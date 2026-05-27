<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;  // ← tambah ini
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController  // ← tambah extends
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}