<?php

namespace App\Service;

class EtablissementPublicApi
{
    public function getEstablishments($code)
    {
      $apec = $this->getApec($code);
      $adil = $this->getAdil($code);
      $data = [
        'apec' => $apec,
        'adil' => $adil
      ];
      return $data;
    }

    public function getApec($code){
      return $this->getData('/apec', $code);
    }

    public function getAdil($code){
      return $this->getData('/adil', $code);
    }

    public function getData($type, $code)
    {
      $url = 'https://etablissements-publics.api.gouv.fr/v3/communes/' . $code . $type;
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
      curl_close($curl);
      $json = json_decode( $response, true );
      $data = [];
      $features = $json['features'];
      foreach ($features as $feature) {
        $result['nom'] = $feature['properties']['nom'];
        $result['code_postal'] = $feature['properties']['adresses'][0]['codePostal'];
        $result['commune'] = $feature['properties']['adresses'][0]['commune'];
        $result['email'] = $feature['properties']['email'];
        $result['telephone'] = $feature['properties']['telephone'];
        $result['type'] = $feature['properties']['pivotLocal'];
        array_push($data, $result);
      }
      return $data;
    }
}