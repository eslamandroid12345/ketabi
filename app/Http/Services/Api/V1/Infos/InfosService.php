<?php

namespace App\Http\Services\Api\V1\Infos;

use App\Http\Resources\V1\Infos\InfosResource;
use App\Repository\InfoRepositoryInterface;
use function App\responseSuccess;

class InfosService
{
    public function __construct(private InfoRepositoryInterface $repository)
    {

    }

    public function __invoke()
    {
        $data['logo'] = url($this->repository->getValue(['logo']))??null;
        $data['fav_icon'] = url($this->repository->getValue(['fav_icon']))??null;
        $data['contact_phone'] = $this->repository->getValue(['contact_phone'])??null;
        $data['email'] = $this->repository->getValue(['email'])??null;
        return responseSuccess(data: $data);
    }
}
