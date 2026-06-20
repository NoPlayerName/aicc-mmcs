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
    public function getAditiveJsh()
    {
        $data = $this->Material->getAditiveJsh();
        return $data;
    }
    public function getInoculant()
    {
        $data = $this->Material->getInoculant();
        return $data;
    }

    public function getRawMatAce()
    {
        $data =  $this->Material->getRawMatAce();
        return $data;
    }
    public function getRawMatJsh()
    {
        $data =  $this->Material->getRawMatJsh();
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
