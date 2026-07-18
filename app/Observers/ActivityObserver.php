<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ActivityObserver
{
    protected function saveLog(Model $model, string $action, ?array $before = null, ?array $after = null)
    {
        // Hanya catat jika ada user yang login (misal via web/api)
        // Jika dijalankan via seeder/console, Auth::id() akan bernilai null
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'activity'   => "{$action}_" . strtolower(class_basename($model)),
            'model_type' => get_class($model),
            'model_id'   => $model->getKey(),
            'before'     => $before,
            'after'      => $after,
            'ip_address' => Request::ip(),
            'user_agent' => Str::limit((string) Request::userAgent(), 100, ''),
        ]);
    }

    public function created(Model $model): void
    {
        // Untuk data baru, kita rekam semua isi datanya di kolom 'after'
        $this->saveLog($model, 'created', null, $model->toArray());
    }

    public function updated(Model $model): void
    {
        // Mengambil data lama sebelum diubah dan data baru yang berubah saja
        $after = $model->getChanges();

        // Ambil data asli (sebelum diubah) berdasarkan key yang berubah saja
        $before = array_intersect_key($model->getOriginal(), $after);

        // Jangan catat jika yang berubah hanya timestamps updated_at
        unset($before['updated_at'], $after['updated_at']);

        if (!empty($after)) {
            $this->saveLog($model, 'updated', $before, $after);
        }
    }

    public function deleted(Model $model): void
    {
        // Untuk data yang dihapus, simpan data terakhirnya di kolom 'before'
        $this->saveLog($model, 'deleted', $model->toArray(), null);
    }
}
