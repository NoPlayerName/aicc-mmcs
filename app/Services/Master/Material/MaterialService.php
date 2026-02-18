<?php

namespace App\Services\Master\Material;

use App\Repositories\Master\Material\MaterialRepositoryInterface;


class MaterialService
{
    protected $Material;

    public function __construct(MaterialRepositoryInterface $Material)
    {
        $this->Material = $Material;
    }

    public function getAdditive()
    {
        $data = $this->Material->getAditive();
        return $data;
    }

    public function getRawMat()
    {
        $data =  $this->Material->getRawMat();
        return $data;
    }
    public function getRawMatTrial()
    {
        $data =  $this->Material->getRawMatTrial();
        return $data;
    }
    public function getAdditiveMatTrial()
    {
        $data =  $this->Material->getAdditiveMatTrial();
        return $data;
    }
}
