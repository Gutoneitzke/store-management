<?php
  
namespace App\Traits;

use App\Models\City;
use PHPUnit\Framework\Constraint\Count;

trait GetCountryStateCityTrait {

    /**
     * @return array
     */
    public function getLocales() {
        $cities = City::with('state.country')->get();

        $cityData = [];

        foreach ($cities as $city) {
            $countryId = $city->state->country->id;
            $countryName = $city->state->country->name;
            $stateId = $city->state->id;
            $stateName = $city->state->name;

            if (!isset($cityData[$countryId])) {
                $cityData[$countryId] = [
                    'id' => $countryId,
                    'name' => $countryName,
                    'states' => [],
                ];
            }

            if (!isset($cityData[$countryId]['states'][$stateId])) {
                $cityData[$countryId]['states'][$stateId] = [
                    'id' => $stateId,
                    'name' => $stateName,
                    'cities' => [],
                ];
            }

            $cityData[$countryId]['states'][$stateId]['cities'][] = [
                'id' => $city->id,
                'name' => $city->name,
            ];
        }

        return $cityData;
    }
  
}