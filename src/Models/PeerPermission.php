<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PeerPermission extends Pivot
{
    protected $table = 'permission_peer';
}
