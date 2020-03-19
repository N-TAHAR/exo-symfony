<?php

namespace App\Service;

class GeoApi
{
    public function getCode($commune, $code_postal)
    {
      $url = 'https://geo.api.gouv.fr/communes?nom=' . $commune . '&codePostal=' . $code_postal;
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
      curl_close($curl);
      $json = json_decode( $response, true );
      if (empty($json)){
        return null;
      } 
      else {
        return $json[0]['code'];
      }
      
      // var_dump($json);
    }
}