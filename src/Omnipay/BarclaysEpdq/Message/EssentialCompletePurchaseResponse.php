<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * BarclaysEpdq Complete Purchase Response
 */
class EssentialCompletePurchaseResponse extends AbstractResponse
{

    protected $statusArray = array(
        0  => "Incomplete or invalid",

        1  => "Cancelled by client",

        2  => "Authorisation refused",

        4  => "Order stored",
        41 => "Waiting client payment",

        5  => "Authorised",
        51 => "Authorisation waiting",
        52 => "Authorisation not known",
        59 => "Author. to get manually",

        6  => "Authorised and canceled",
        61 => "Author. deletion waiting",
        62 => "Author. deletion uncertain",
        63 => "Author. deletion refused",

        7  => "Payment deleted",
        71 => "Payment deletion pending",
        72 => "Payment deletion uncertain",
        73 => "Payment deletion refused",
        74 => "Payment deleted (not accepted)",
        75 => "Deletion processed by merchant",

        8  => "Refund",
        81 => "Refund pending",
        82 => "Refund uncertain",
        83 => "Refund refused",
        84 => "Payment declined by the acquirer (will be debited)",
        85 => "Refund processed by merchant",

        9  => "Payment requested",
        91 => "Payment processing",
        92 => "Payment uncertain",
        93 => "Payment refused",
        94 => "Refund declined by the acquirer",
        95 => "Payment processed by merchant",
        97 => "Being processed (intermediate technical status)",
        98 => "Being processed (intermediate technical status)",
        99 => "Being processed (intermediate technical status)"
    );

    public function isSuccessful()
    {
        $successCodes = array(5, 9);

        return $this->getStatusCode() && in_array($this->getStatusCode(), $successCodes);
    }

    /**
     * Status of the payment.
     *
     * @return int|null
     */
    public function getStatusCode()
    {
        return isset($this->data['STATUS']) ? (int)$this->data['STATUS'] : null;
    }

    /**
     * System’s unique transaction reference.
     *
     * The PAYID currently consists of 9 digits, but it’s an increasing number.
     * In the test environment the PAYID currently holds 7 digits.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        return isset($this->data['PAYID']) ? $this->data['PAYID'] : null;
    }

    /**
     * Your order number (merchant reference).
     *
     * This reference is generally used by the merchant to look up his transaction in the back office.
     * For online, 3-tiers mode, the system also uses this reference to check if a payment is not requested
     * twice for the same order. On request of the merchant the period of this check can be shortened or
     * the check can be disabled.
     *
     * If the acquirer is technically able to process the transaction, and if there’s no special configuration
     * in the account, this orderID will be sent to the acquirer as reference (ref2) for the transaction.
     * In this case the merchant will receive this ref2 field on his account statements,
     * helping him reconcile his transactions.
     * Although our system can accept up to 30 characters, the norm for most acquirers is 10 or 12.
     * The exact accepted length and data validation format depend on the acquirer/bank.If the orderID does not
     * comply to the ref2 rules set by the acquirer, we’ll send our PAYID as ref2 to the acquirer instead.
     *
     * Avoid using spaces or special characters in the orderID.
     *
     * @return string|null
     */
    public function getTransactionId()
    {
        return isset($this->data['orderID']) ? $this->data['orderID'] : null;
    }

    /**
     * Error code.
     *
     * @return string|null
     */
    public function getNcError()
    {
        return isset($this->data['NCERROR']) ? $this->data['NCERROR'] : null;
    }

    /**
     * Error description of the NCERROR code.
     *
     * @return string|null
     */
    public function getNcErrorPlus()
    {
        return isset($this->data['NCERRORPLUS']) ? $this->data['NCERRORPLUS'] : null;
    }

    /**
     * Error status. In general this is the first digit of the NCERROR.
     *
     * @return string|null
     */
    public function getNcStatus()
    {
        return isset($this->data['NCSTATUS']) ? $this->data['NCSTATUS'] : null;
    }

    /**
     * Acquirer's acceptance (authorisation) code.
     *
     * The acquirer sends back this code to confirm the amount of the transaction
     * has been blocked on the card of the customer. The acceptance code is not unique.
     *
     * @return string|null
     */

    public function getAcceptance()
    {
        return isset($this->data['ACCEPTANCE']) ? $this->data['ACCEPTANCE'] : null;
    }

    /**
     * Currency of the transaction.
     *
     * @return string|null
     */
    public function getCurrency()
    {
        return isset($this->data['currency']) ? $this->data['currency'] : null;
    }

    /**
     * Amount of the transaction. In general, the acquirer accepts up to 2 decimals, depending on the currency.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return isset($this->data['amount']) ? $this->data['amount'] : null;
    }

    /**
     * Payment Method.
     *
     * @return string|null
     */
    public function getPaymentMethod()
    {
        return isset($this->data['PM']) ? $this->data['PM'] : null;
    }

    /**
     * Card number or account number.
     *
     * The rules on how our system has to mask credit card numbers - in any output, display or email - are set by PCI.
     *
     * For VISA, VISA PC, MASTERCARD, MASTERCARD PC and MASTERCARD PC CM CIC the 4 last digits will be visible.
     *
     * For all other brands/payment methods the part that is masked depends on the length of the card number or
     * account number:
     *
     * If the number is longer than 15 digits: the 6 first and 2 last digits are visible,
     * with xxxxxxxx (8x) in the middle.
     *
     * If the number is from 12 to 15 digits long: the first 4 and last 2 digits are visible,
     * with xxxxxx (6x) in the middle.
     *
     * If the number is from 8 to 11 digits long: the first 2 and last 2 digits are visible,
     * with xxxx (4x) in the middle.
     *
     * If the number is from 4 to 7 digits long: the first and last digit is visible,
     * with xx (2x) in the middle.
     *
     * If the number is less than 4 digits long, the whole number will be masked.
     *
     * The account number will never be visible for offline bank transfer and Payment on Delivery.
     *
     * The account number for Direct Debits transactions will be masked when the transaction is in status 4 – order
     * stored, if the buyer has to send a signed fax to confirm the payment.
     *
     * @return string|null
     */
    public function getCardNumber()
    {
        return isset($this->data['CARDNO']) ? $this->data['CARDNO'] : null;
    }

    /**
     * Expiry date. This date must not be in the past, compared to the date the transaction is processed in our system.
     * When there is no expiry date available (e.g. for bank transfers) the value 9999 is used.
     * @return string|null
     */
    public function getExpiryDate()
    {
        return isset($this->data['ED']) ? $this->data['ED'] : null;
    }

    /**
     * Card holder (customer) name.
     *
     * @return string|null
     */
    public function getCardHolder()
    {
        return isset($this->data['CN']) ? $this->data['CN'] : null;
    }

    /**
     * Transaction date in MM/DD/YY format.
     *
     * @return string|null
     */
    public function getTransactionDate()
    {
        return isset($this->data['TRXDATE']) ? $this->data['TRXDATE'] : null;
    }

    /**
     * Brand of a credit/debit/purchasing card.
     *
     * @return string|null
     */
    public function getCardBrand()
    {
        return isset($this->data['BRAND']) ? $this->data['BRAND'] : null;
    }

    /**
     * Originating country of the IP address in ISO 3166-1-alpha-2 code values
     * (http://www.iso.org/iso/country_codes/iso_3166_code_lists.htm).
     * If this parameter is not available, “99” will be returned in the response.
     *
     * There are 4 specific IP codes which refer to IP addresses for which the country of origin is uncertain:
     *
     * A1: Anonymous proxy. Anonymous proxies are Internet access providers that allow Internet users to hide
     * their IP address.
     * AP: Asian Pacific region
     * EU: European network
     * A2: Satellite providers
     *
     * @return string|null
     */
    public function getIPCountry()
    {
        return isset($this->data['IPCTY']) ? $this->data['IPCTY'] : null;
    }

    /**
     * Country where the card was issued, in ISO 3166-1-alpha-2 code values
     * (http://www.iso.org/iso/country_codes/iso_3166_code_lists.htm).
     * If this parameter is not available, “99” will be returned in the response.
     *
     * This credit card country check is based on externally provided listings, so there is a slight risk since
     * we rely on the correctness of this list. The check gives positive results in 94% of all cases.
     *
     * @return string|null
     */
    public function getCardCountry()
    {
        return isset($this->data['CCCTY']) ? $this->data['CCCTY'] : null;
    }

    /**
     * Electronic Commerce Indicator. The ECI indicates the security level at which the payment information is processed
     * between the cardholder and merchant.
     *
     * A default ECI value can be set in the Technical Information page. An ECI value sent along in the transaction,
     * will overwrite the default ECI value.
     *
     * It is the merchant's responsibility to give correct ECI values for the transactions. For e-Commerce, our system
     * sets ECI value 5, 6 or 7 depending on the 3-D Secure authentication result.
     *
     * Possible values:
     * 0 - Swiped
     * The merchant took the customer's credit card and swiped it through a machine that read the magnetic strip
     * data of the card.
     *
     * 1 - Manually keyed (MOTO) (card not present)
     * The merchant received the customer's financial details over the phone or via fax/mail, but does not have the
     * customer's card at hand.
     *
     * 2 - Recurring (from MOTO)
     * The customer's first transaction was a Mail Order / Telephone Order transaction, i.e. the customer gave his
     * financial details over the phone or via mail/fax to the merchant. The merchant either stored the details himself
     * or had these details stored in our system using an Alias and is performing another transaction for the same
     * customer (recurring transaction).
     *
     * 3 - Installment payments
     * Partial payment of goods/services that have already been delivered, but will be paid for in several
     * spread payments.
     *
     * 4 - Manually keyed, card present
     * The customer is physically present in front of the merchant. The merchant has the customer's card at hand.
     * The card details are manually entered, the card is not swiped through a machine.
     *
     * 5 - Cardholder identification successful
     * The cardholder's 3-D Secure identification was successful, i.e. there was a full authentication. (Full thumbs up)
     *
     * 6 - Merchant supports identification but not cardholder, The merchant has a 3-D Secure contract, but the
     * cardholder's card is not 3-D Secure or is 3-D Secure but the cardholder is not yet in possession of the
     * PIN (Half thumbs up). Conditional payment guarantee rules apply.
     *
     * 7 - E-commerce with SSL encryption
     * The merchant received the customer's financial details via a secure (SSL encrypted)
     * website (either the merchant's website or our secure platform).
     *
     * 9 - Recurring (from e-commerce)
     * The customer's first transaction was an e-Commerce transaction, i.e. the customer entered his financial details
     * himself on a secure website (either the merchant's website or our secure platform). The merchant either stored
     * the details himself or had these details stored in our system using an Alias and is now performing another
     * transaction for the same customer (recurring transaction), using the Alias details.
     *
     * @return integer|null
     */
    public function getECI()
    {
        return isset($this->data['ECI']) ? $this->data['ECI'] : null;
    }

    /**
     * Result of the card verification code check. Only a few acquirers return specific CVC check results.
     * For most acquirers, the CVC is assumed to be correct if the transaction is succesfully authorised.
     *
     * Possible values:
     * KO: The CVC has been sent but the acquirer has given a negative response to the CVC check, i.e. the CVC is wrong.
     * OK: The CVC has been sent and the acquirer has given a positive response to the CVC check, i.e.the CVC is correct
     * OR The acquirer sent an authorisation code, but did not return a specific result for the CVC check.
     * NO: All other cases. For instance, no CVC transmitted, the acquirer has replied that a CVC check was not
     * possible, the acquirer declined the authorisation but did not provide a specific result for the CVC check, …
     *
     * @return string|null
     */
    public function getCVCCheck()
    {
        return isset($this->data['CVCCheck']) ? $this->data['CVCCheck'] : null;
    }

    /**
     * Result of the automatic address verification. This verification is not supported by all credit card acquirers.
     *
     * Possible values:
     * KO: The address has been sent but the acquirer has given a negative response for the address check, i.e. the
     * address is wrong.
     * OK: The address has been sent and the acquirer has returned a positive response for the address check, i.e. the
     * address is correct OR The acquirer sent an authorisation code but did not return a specific response for the
     * address check.
     * NO: All other cases. For instance, no address transmitted; the acquirer has replied that an address check was not
     * possible; the acquirer declined the authorisation but did not provide a specific result for the address check
     *
     * @return string|null
     */
    public function getAAVCheck()
    {
        return isset($this->data['AAVCheck']) ? $this->data['AAVCheck'] : null;
    }

    /**
     * Virtual Card type. Virtual cards are in general virtual, single-use credit card numbers, which can only be
     * used on one predefined online shop.
     *
     * @return string|null
     */
    public function getVirtualCard()
    {
        return isset($this->data['VC']) ? $this->data['VC'] : null;
    }

    /**
     * IP address from which the payment was made.
     *
     * @return string|null
     */
    public function getIPAddress()
    {
        return isset($this->data['IP']) ? $this->data['IP'] : null;
    }

    public function getMessage()
    {
        if (isset($this->statusArray[$this->getStatusCode()])) {
            return $this->statusArray[$this->getStatusCode()];
        }

        return null;
    }
}
