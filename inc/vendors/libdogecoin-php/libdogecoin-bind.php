<?php
/**
*   File: Functions used on the Libdogecoin
*   Description: Bind Libdogecoin to PHP using c compiled that prints in JSON format
*
*   Available Commands:
*    generatePrivPubKeypair
*    generateHDMasterPubKeypair
*    generateDerivedHDPubkey
*    verifyPrivPubKeypair
*    verifyHDMasterPubKeypair
*    verifyP2pkhAddress
*    start_transaction
*/

// class DogeBridge to be able to interact beetwin DB and Dogecoin Core RCP

class LibDogecoin {

    private function command ($commands){
      exec(ROOTPATH.'/vendors/libdogecoin-php/libdogecoin-json-php '.implode(" ", $commands).' 2>&1', $output, $retval);
      return json_decode($output[0]);
    }

    // keypair generation
    public function generatePrivPubKeypair(){
      $commands[] = "generatePrivPubKeypair";
      return $this->command($commands);
    }

    // keypair generation
    public function generateHDMasterPubKeypair(){
      $commands[] = "generateHDMasterPubKeypair";
      return $this->command($commands);
    }

    // keypair generation
    public function generateDerivedHDPubkey(){
      $commands[] = "generateDerivedHDPubkey";
      return $this->command($commands);
    }

    // keypair verification
    public function verifyPrivPubKeypair($privateKey,$publicKey){
      $commands[] = "generateDerivedHDPubkey";
      $commands[] = $privateKey;
      $commands[] = $publicKey;
      return $this->command($commands);
    }

    // keypair verification
    public function verifyHDMasterPubKeypair($privateKey,$publicKey){
      $commands[] = "verifyHDMasterPubKeypair";
      $commands[] = $privateKey;
      $commands[] = $publicKey;
      return $this->command($commands);
    }

    // address verification
    public function verifyP2pkhAddress($publicKey){
      $commands[] = "verifyP2pkhAddress";
      $commands[] = $publicKey;
      return $this->command($commands);
    }

    // build transaction
    public function start_transaction($hash_doge,$external_p2pkh_addr,$doge_amount,$fees,$tx_index,$mypubkey,$myscriptpubkey,$myprivkey){
      $commands[] = "start_transaction";
      $commands[] = $hash_doge;
      $commands[] = $external_p2pkh_addr;
      $commands[] = $doge_amount;
      $commands[] = $fees;
      $commands[] = $tx_index;
      $commands[] = $mypubkey;
      $commands[] = $myscriptpubkey;
      $commands[] = $myprivkey;
      return $this->command($commands);
    }
}

    $LibDogecoin = new LibDogecoin();

?>
