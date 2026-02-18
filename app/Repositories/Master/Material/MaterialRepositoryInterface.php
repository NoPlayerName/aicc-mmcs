<?php

namespace App\Repositories\Master\Material;


interface MaterialRepositoryInterface
{
    public function getAditive();
    public function getRawMat();
    public function getRawMatTrial();
    public function getAdditiveMatTrial();
}
