<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;

class DeviceToken extends Model
{
    use FillsColumns, SerializesDates;
}
