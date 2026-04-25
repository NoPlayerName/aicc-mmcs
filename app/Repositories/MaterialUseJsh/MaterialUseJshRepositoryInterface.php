<?php

namespace App\Repositories\MaterialUseJsh;

interface   MaterialUseJshRepositoryInterface
{
    public function getDetail($id, $anchor);
    public function getRawMat($id);
    public function getAdditiveMat($id);
    public function getKwh($id);
    public function getTempTapping($id);
    public function getCharging($id);
    public function saveCharging($data);
    public function saveDesc($data);
    public function saveChargingHead($data);
    public function updateChargingHead($data);
    public function saveRawMat($data);
    public function saveUpdateRawMat($data);
    public function saveAdditiveMat($data);
    public function saveUpdateAdditiveMat($data);
    public function saveKwh($data);
    public function UpdateKwh($data);
    public function saveTemptTapping($data);
    public function updateTemptTapping($data);
}
