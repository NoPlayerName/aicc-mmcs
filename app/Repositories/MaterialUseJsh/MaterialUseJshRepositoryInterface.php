<?php

namespace App\Repositories\MaterialUseJsh;

interface   MaterialUseJshRepositoryInterface
{
    public function getDetail($id, $anchor);
    public function getRawMat($id);
    public function getCharging($id);
    public function saveCharging($data);
    public function saveChargingHead($data);
    public function saveRawMat($data);
    public function saveAdditiveMat($data);
    public function saveKwh($data);
    public function saveTemptTapping($data);
}
