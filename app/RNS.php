<?php
namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RNS
{
    protected $scope;
    protected $client;
    protected $clientId;
    protected $clientSecret;

    const TOKEN_URI = 'Account/AccessToken?clientid=';
    const UNIT_LIST_URI = 'Units';

    public function __construct()
    {
        $this->scope        = config('rns.scope');
        $this->clientId     = config('rns.id');
        $this->clientSecret = config('rns.secret');
        $this->client       = new Client(['base_uri' => 'https://core.rnshosted.com/api/v17/']);
    }

    public function getAccessToken()
    {
        $tokenId = Token::first() ? Token::first()->id : 0;
        $results = $this->client->post(self::TOKEN_URI . $this->clientId, [
            'json' => [
                'ClientId'     => $this->clientId,
                'ClientSecret' => $this->clientSecret,
                'Scope'        => $this->scope
            ]
        ]);
        $newToken = json_decode($results->getBody())->access_token;

        $token = Token::updateOrCreate(['id' => $tokenId], [
            'value' => $newToken
        ]);

        return $token;
    }

    public function getUnitList()
    {
        $units = $this->get(self::UNIT_LIST_URI);

        foreach ($units as $unit) {
            $newUnit = Unit::updateOrCreate(['rns_id' => $unit->UnitId], [
                'company_id' => $unit->CompanyId,
                'rns_id'     => $unit->UnitId,
                'number'     => $unit->UnitNo,
                'name'       => $unit->UnitName
            ]);
            // Our code is too fast for their API
            usleep(200000);
            //
            $this->addSearchCriteria($newUnit);
        }
    }

    public function rebuild()
    {
        $token = $this->getAccessToken();
        $units = $this->getUnitList();
        Image::forAllUnits();
        Amenity::forAllUnits();
        Availability::forAllUnits();
        Detail::forAllUnits();

        echo 'Check it';
    }

    private function addSearchCriteria($newUnit)
    {
        $searchCriteria    = $this->locationAndTypeForUnit($newUnit->rns_id);
        $newUnit->type     = $searchCriteria[1]->Name ?? null;
        $newUnit->location = $searchCriteria[0]->Name ?? null;
        $newUnit->save();
    }

    public function amenitiesForUnit($rnsId)
    {
        return $this->get("Units/{$rnsId}/Amenities?clientid={$this->clientId}");
    }

    public function availabilityForUnit($rnsId)
    {
        return $this->get("Units/{$rnsId}/Availibility?clientid={$this->clientId}");
    }

    public function imagesForUnit($rnsId)
    {
        return $this->get("Units/{$rnsId}/Images?clientid={$this->clientId}");
    }

    public function locationAndTypeForUnit($rnsId)
    {
        return $this->get("Units/{$rnsId}/SearchCriteria?clientid={$this->clientId}");
    }

    public function getImageChanges($lastUpdate)
    {
        return $this->get("Units/Images/Changes?LastUpdateDate={$lastUpdate}&clientId={$this->clientId}");
    }

    public function getAmenityChanges($lastUpdate)
    {
        return $this->get("Units/Amenities/Changes?LastUpdateDate={$lastUpdate}&clientId={$this->clientId}");
    }

    public function detailsForUnit($rnsId)
    {
        try {
            return $this->get("Units/{$rnsId}/PropertyDetail?clientid={$this->clientId}");
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
            return 'Sausage';
        }
    }

    private function get($uri)
    {
        $token = Token::first()->value;

        return json_decode($this->client->get($uri . '?clientid=' . $this->clientId, [
            'headers' => [
                'Authorization' => 'Bearer '. $token
            ]
        ])->getBody());
    }
}
