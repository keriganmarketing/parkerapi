<?php
namespace App;

use GuzzleHttp\Client;

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

        Token::updateOrCreate(['id' => $tokenId], [
            'value' => $newToken
        ]);
    }

    public function getUnitList()
    {
        $units = $this->get(self::UNIT_LIST_URI);

        foreach ($units as $unit) {
            Unit::updateOrCreate(['rns_id' => $unit->UnitId], [
                'company_id' => $unit->CompanyId,
                'rns_id'     => $unit->UnitId,
                'number'     => $unit->UnitNo,
                'name'       => $unit->UnitName
            ]);
        }
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
