<?php
// GigaWallet ShibeShip Internal debugging anf initial tests

// we verify if its authenticated as admin
if (!isset($_SESSION["admin"])){
        header('Location: https://'.$_SERVER['HTTP_HOST']);
};

// GigaWallet Server
$config["GigaWalletServer"] = "localhost";

// GigaWallet Server port
$config["GigaWalletServerPort"] = 69420;

// GigaWallet Bridge
class GigaWalletBridge {

    private $config;     // include GigaWallet Configurations
    public function __construct($config) {
        $this->config = $config;
    }

    // Creates/Gets a GigaWallet Account
    public function account($foreign_id,$payout_address = NULL,$payout_threshold = 0,$payout_frequency = 0 ) {

        // Builds the Gigawallet Command
        //$command = "/account/" . $foreign_id . "/" . $payout_address . "/" . $payout_threshold . "/" . $payout_frequency;
        $command = "/account/" . $foreign_id;

        // Sends the GigaWallet Command
        return $this->sendGigaCommand($this->config["GigaWalletServer"] . ":" . $this->config["GigaWalletServerPort"] . $command, 'GET');
    }


    // Creates a GigaWallet Invoice
    public function invoice($foreign_id,$items) {

        // Builds the Gigawallet Command
        $command = "/account/" . $foreign_id . "/invoice/";

        // Sends the GigaWallet Command
        return $this->sendGigaCommand($this->config["GigaWalletServer"] . ":" . $this->config["GigaWalletServerPort"] . $command, 'POST', $items);
    } 

    // Gets one GigaWallet Invoice
    public function GetInvoice($invoice_id) {

        // Builds the Gigawallet Command
        $command = "/invoice/" . $invoice_id . "/";

        // Sends the GigaWallet Command
        return $this->sendGigaCommand($this->config["GigaWalletServer"] . ":" . $this->config["GigaWalletServerPort"] . $command, 'GET', NULL);
    }      

    // Gets all GigaWallet Invoices from that shibe
    public function GetInvoices($foreign_id) {

        // Builds the Gigawallet Command
        $command = "/account/" . $foreign_id . "/invoices/";

        // Sends the GigaWallet Command
        return $this->sendGigaCommand($this->config["GigaWalletServer"] . ":" . $this->config["GigaWalletServerPort"] . $command, 'GET', NULL);
    }      

    // Gets a GigaWallet QR code Invoice
    public function qr($foreign_id,$invoice) {

        // Builds the Gigawallet Command
        $command = "/account/" . $foreign_id . "/invoice/" . $invoice ."/qr.png";

        // Sends the GigaWallet Command
        return $this->sendGigaCommand($this->config["GigaWalletServer"] . ":" . $this->config["GigaWalletServerPort"] . $command, 'GET');
    }   

    // Sends commands to the GigaWallet Server
    public function sendGigaCommand($url, $method = 'GET', $data = array()) {
        $ch = curl_init();
    
        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);
    
        // Set the request method
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            // Set the Content-Type header to specify JSON data
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
        // Set the option to return the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Execute the request
        $response = curl_exec($ch);
    
        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("GigaWallet Error: $error");
        }
    print_r($response);
        // Close the curl handle
        curl_close($ch);
    
        // Return the response
        return $response;
    }    

}

$G = new GigaWalletBridge($config);

// Gets a GigaWallet Account
$GigaAccount = json_decode($G->account("inevitable360","DTqAFgNNUgiPEfGmc4HZUkqJ4sz5vADd1n",0,0));
//echo $GigaAccount->id; // HD Address
//echo $GigaAccount->foreign_id; // External Shibe reference
//echo $GigaAccount->payout_address; // Payment Address
//echo $GigaAccount->payout_threshold; // Minimum Ammount to send payments
//echo $GigaAccount->payout_frequency; // Days to send Payments

// Creates a GigaWallet Invoice
$items["vendor"] = "inevitable360"; // External invoice reference
$i = 0; // item number
$items["items"][$i]["name"] = "Test meme"; // item name
$items["items"][$i]["price"] = 1.00; // item value
$items["items"][$i]["quantity"] = 1; // item quantity
$items["items"][$i]["image_link"] = "https://shibeship.com/fl/shibeShip_6480e2384ebaf.png"; // item image URL
$GigaInvoice = json_decode($G->invoice($GigaAccount->foreign_id,$items));

// Gets a Invoice QR
$GigaQR = base64_encode($G->qr($GigaAccount->foreign_id,$GigaInvoice->id));
//echo $G->qr($GigaAccount->foreign_id,$GigaInvoice->id);
?>
<div style="width:100%">
    <div style="text-align: center;">
<?php
 // Prints the Invoice QR
if (isset($GigaQR)){
?>
        <img src="data:image/png;base64, <?php echo $GigaQR; ?>" alt="Much pay" />
        <div><?php echo $GigaInvoice->id; ?></div>
<?php
};

// gets one GigaWallet Invoice
if (isset($_GET["invoice"])){
    $GigaInvoice = json_decode($G->GetInvoice($GigaInvoice->id));
    print_r($GigaInvoice);
};

// gets all GigaWallet Invoices from that shibe
if (isset($_GET["invoices"])){
    $GigaInvoices = json_decode($G->GetInvoices("inevitable360"));
    print_r($GigaInvoices);
};
?>
    </div>
</div>