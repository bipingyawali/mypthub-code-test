<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Organisation;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class OrganisationTransformer
 * @package App\Transformers
 */
class OrganisationTransformer extends TransformerAbstract
{
    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function transform(Organisation $organisation): array
    {
        $owner = $this->includeUser($organisation);
        return [
            'id' => (int) $organisation->id,
            'name' => (string) $organisation->name,
            'trial_end' => $organisation->trial_end ? (int) Carbon::parse($organisation->trial_end)->timestamp : null,
            'subscribed' => (bool) $organisation->subscribed,
            'owner' => $owner
        ];
    }

    /**
     * @param Organisation $organisation
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Organisation $organisation)
    {
        $userTransformer = new UserTransformer();
        return $userTransformer->transform($organisation->owner);
    }
}
