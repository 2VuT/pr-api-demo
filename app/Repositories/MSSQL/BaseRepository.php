<?php

namespace App\Repositories\MSSQL;

use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    public $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv');
    }
}
