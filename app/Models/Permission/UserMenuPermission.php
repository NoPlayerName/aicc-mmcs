<?php

namespace App\Models\Permission;

use App\Models\Menu\Menu;
use Illuminate\Database\Eloquent\Model;

class UserMenuPermission extends Model
{
    protected $table = 'tb_user_permissions';
    public $timestamps = false;

    protected $casts = [
        'can_access'   => 'boolean',
        'can_read'     => 'boolean',
        'can_edit'     => 'boolean',
        'can_delete'   => 'boolean',
        'can_add'      => 'boolean',
        'can_approve1' => 'boolean',
        'can_approve2' => 'boolean',
        'can_approve3' => 'boolean',
        'can_approve4' => 'boolean',
    ];
    protected $fillable = [
        'user_id',
        'menu_id',
        'can_access',
        'can_read',
        'can_edit',
        'can_delete',
        'can_add',

    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
