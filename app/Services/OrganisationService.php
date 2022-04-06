<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\SendMail;
use App\Organisation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Collection;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        $organisation = new Organisation();

        $organisation->name = $attributes['name'];
        $organisation->owner_user_id = auth('api')->user()->id;
        $organisation->trial_end = Carbon::now()->addDays(30);
        $organisation->save();

        /**
         * send mail to the current user
         */
        Mail::to(auth('api')->user()->email)->send(new SendMail($organisation));
        return $organisation;
    }

    /**
     * @param $filter
     *
     * @return object
     */
    public function getAllOrganisations($filter) : object
    {
        $organisations = new Organisation();

        if ($filter === 'all' || !$filter) {
            $organisations = $organisations->all();
        } else {
            if ($filter === 'trial') {
                $subscribed = 0;
            } elseif ($filter === 'subbed') {
                $subscribed = 1;
            } else {
                throw new \Exception('Invalid filter parameter.');
            }

            $organisations = $organisations->where('subscribed', '=', $subscribed)->get();
        }
        return $organisations;
    }
}
