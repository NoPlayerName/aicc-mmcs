<?php

namespace App\Repositories\MaterialUseAce;

interface MaterialUseAceReposirotyInterface
{
    public function getDetail($id, $anchor);
    public function getRawMat($data);
    public function getAdditiveMat($id);
    public function getKwh($id);
    public function getTempTapping($id);
    public function getCharging($id);
    public function saveCharging($data);
    public function saveChargingHead($data);
    public function saveRawMat($data);
    public function saveUpdateRawMat($data);
    public function saveAdditiveMat($data);
    public function saveUpdateAdditiveMat($data);
    public function saveKwh($data);
    public function UpdateKwh($data);
    public function saveTemptTapping($data);
    public function updateTemptTapping($data);
    public function saveLadle($data);
    public function saveLadleMat($data);
    public function updateLadleTransfer($id, $ladleHead, $ladleMat);
    public function getLadleTransfer($date, $shift);
}
