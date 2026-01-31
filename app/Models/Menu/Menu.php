<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tb_menus';
    public $timestamps = false;
    protected $fillable = [
        'urutan_menu',
        'menu_name',
        'url',
        'parent_id',
        'icon',
        'has_extra_permissions',
    ];
}
