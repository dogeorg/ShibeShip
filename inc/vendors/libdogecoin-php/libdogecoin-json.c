#include "libdogecoin.h"
#include <stdio.h>
#include <string.h>

// Example of how to use libdogecoin API functions:
// gcc ./libdogecoin-php/libdogecoin-json.c -I./include -L./lib -ldogecoin -o ./libdogecoin-php/libdogecoin-json-php

int main(int argc, char *argv[]) {
  dogecoin_ecc_start();

  // CALLING ADDRESS FUNCTIONS
  // create variables
  size_t privkeywiflen = 53;
  size_t masterkeylen = 200;
  size_t pubkeylen = 35;
  char wif_privkey[privkeywiflen];
  char p2pkh_pubkey[pubkeylen];
  char wif_master_privkey[masterkeylen];
  char p2pkh_master_pubkey[pubkeylen];
  char p2pkh_child_pubkey[pubkeylen];

  // keypair generation
    //argv[1] = command

   if (strcmp(argv[1], "generatePrivPubKeypair") == 0){
        generatePrivPubKeypair(wif_privkey, p2pkh_pubkey, 0);
    printf("{\"private\":\"%s\",\"public\":\" %s\"}", wif_privkey, p2pkh_pubkey);
    };

    if (strcmp(argv[1], "generateHDMasterPubKeypair") == 0){
    	generateHDMasterPubKeypair(wif_master_privkey, p2pkh_master_pubkey, 0);
    printf("{\"private\":\"%s\",\"public\":\" %s\"}", wif_master_privkey, p2pkh_master_pubkey);
    }


    if (strcmp(argv[1], "generateDerivedHDPubkey") == 0){
    	generateDerivedHDPubkey((const char*)wif_master_privkey, (char*)p2pkh_child_pubkey);
    printf("{\"private\":\"%s\",\"public\":\" %s\"}", wif_master_privkey, p2pkh_child_pubkey);
    }

  // keypair verification

      //argv[1] = command
      //argv[2] = privat key
      //argv[3] = public key

    if (strcmp(argv[1], "verifyPrivPubKeypair") == 0){
    	if (verifyPrivPubKeypair(argv[2], argv[3], 0)) {
            printf("{\"valid\":\"true\"}");
    	}
    	else {
            printf("{\"valid\":\"false\"}");
    	}
    }

    if (strcmp(argv[1], "verifyHDMasterPubKeypair") == 0){
    	if (verifyHDMasterPubKeypair(argv[2], argv[3], 0)) {
            printf("{\"valid\":\"true\"}");
    	}
    	else {
            printf("{\"valid\":\"false\"}");
    	}
    }


  // address verification

    //argv[1] = command
    //argv[2] = doge address

    if (strcmp(argv[1], "verifyP2pkhAddress") == 0){
    	if (verifyP2pkhAddress(argv[2], strlen(argv[2]))) {
            printf("{\"valid\":\"true\"}");
    	}
    	else {
            printf("{\"valid\":\"false\"}");
    	}
    }

  // build transaction

    //argv[1] = command
    //argv[2] = hash_doge (tx hash with doge)
    //argv[3] = external_p2pkh_addr
    //argv[4] = 5.0 (doge to send)
    //argv[5] = 0.00226 (fees)
    //argv[6] = 12 (tx index)
    //argv[7] = mypubkey
    //argv[8] = myscriptpubkey
    //argv[9] = myprivkey

    if (strcmp(argv[1], "start_transaction") == 0){

    	int idx = start_transaction();
        add_utxo(idx, argv[2], 1);
        add_output(idx, argv[3], argv[4]);
        finalize_transaction(idx, argv[3], argv[5], argv[6], argv[7]);
        sign_transaction(idx, argv[8], argv[9]);
        printf("{\"rawtx\":\"%s\"}", get_raw_transaction(idx));
    }

  dogecoin_ecc_stop();
    return 0;
}
