<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;

class Client extends Model
{
    use FillsColumns, SerializesDates;
}
