<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrganisationRequest;
use App\Organisation;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationRequest $request,OrganisationService $service): JsonResponse
    {
        /** @var Organisation $organisation */
        $organisation = $service->createOrganisation($request->all());

        return $this->transformItem('organisation', $organisation, ['user'])->respond();
    }

    /**
     * get the list of all organisation
     * @param OrganisationService $service
     * @return JsonResponse
     * @throws \Exception
     */
    public function listAll(OrganisationService $service)
    {
        $filter = $this->request->query('filter') ?: false;
        $organisations = $service->getAllOrganisations($filter);

        return $this->transformCollection('organisation', $organisations,['user'])->respond();
    }
}
