<?php

namespace App\Repositories\Master\Material;


interface MaterialRepositoryInterface
{
    public function getAditive();
    public function getAditiveJsh();
    public function getInoculant();
    public function getRawMatAce();
    public function getRawMatJsh();
    public function getRawMatTrial();
    public function getAdditiveMatTrial();
}
