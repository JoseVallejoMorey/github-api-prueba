<?php

use Http\Adapter\Guzzle6\Client as GuzzleClient;

class Controller{

    private $client;

    public function __construct(){
        $this->client = new \Github\Client();
    }

    public function getByOrganization($organization, $number = null){
        try{

            $repos = $this->client->api('organization')->repositories($organization);

            $iterador = new RecursiveArrayIterator($repos);
            $iterador->uasort(function( $a, $b ) {
                return $b['size'] - $a['size'];
            });

            if($number){
                $iterador->seek($number);
                $response = array(
                    'repo' => $iterador->current()
                );
            }else{
                $response = array(
                    'number' => count($repos),
                    'biggest_repo' => $iterador->current()
                );
            }

        }catch(Exception $e){

            http_response_code(404);
            $response = array(
                'message' => 'Organization not found.'
            );
        }

        return json_encode($response);
    }

    public function getByNumber($number){
        try{

            $repos = $this->client->api('search')->repositories('size:>=50000000');

            $iterator = new RecursiveArrayIterator($repos['items']);
            $iterator->uasort(function( $a, $b ) {
                return $b['size'] - $a['size'];
            });

            $number = $number < 2 ? 0 : $number-1;
            $iterator->seek($number);
            $response = $iterator->current();

        }catch(Exception $e){
            http_response_code(404);
            $response = array(
                'message' => 'not found'
            );
        }


        return json_encode($response);

    }









}