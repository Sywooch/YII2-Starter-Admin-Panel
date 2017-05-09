<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="<?=CREWLOGO?>" style="width:100%; max-width:300px;">
                        </td>

                        <td>
                            Date: <?=date('M d, Y')?><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <p>
                    Electronic Payment Advisory From CREWFACILITIES.COM ­ ACTION REQUIRED
                    From: CREWFACILITIES.COM<br>
                    ACCOUNTS PAYABLE<br>
                    2303 RR 620 SO, SUITE 135­418 AUSTIN, TX 78734<br>
                    P: 512­599­0022<br>
                    F: 800-273-9256<br>
                    BILLING@CREWFACILITIES.COM<br>
                </p>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <p>
                    The invoice(s) listed below have been authorized by CREWFACILITIES.COM on <?=date('M d, Y')?> to be charged to the following MasterCard number for the Dollar Amount of <?= Yii::$app->commonfunction->AuthrisedAmt($model);?>
                </p>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <p>
                    MASTERCARD#: <?= Yii::$app->commonfunction->cardNumber($model);?> EXP:<?= Yii::$app->commonfunction->expiry($model);?> Security Code:<?= Yii::$app->commonfunction->cvc($model);?>
                    XXXXXX represents the 6­digit MasterCard Number Prefix previously provided to you
                </p>
            </td>
        </tr>
        </tr>
        <tr class="item">
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td>    Invoice Date    </td>
                        <td>    Authorised Dollar Amount </td>
                        <td>    Invoice No </td>
                    </tr>
                    <tr>
                        <td>    <?=date('M d, Y')?>  </td>
                        <td>    <?= Yii::$app->commonfunction->AuthrisedAmt($model);?> </td>
                        <td>     </td>
                    </tr>
                    </tbody>

                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <p>
                    Please contact the Accounts Payable Department of CREWFACILITIES.COM at BILLING@CREWFACILITIES.COM or 512­599­
                    0022 if you have any questions regarding this payment.
                    Comments:
                    PLEASE PROCESS PAYMENT IMMEDIATELY. THIS CARD WILL BE BLOCKED TO FURTHER CHARGES
                    TWO DAYS FROM TODAY.
                    WE RECOMMEND THAT YOU PROCESS THE MASTERCARD NUMBER SHOWN ABOVE ONCE FOR THE
                    TOTAL NET AMOUNT PAID. THANK YOU
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
