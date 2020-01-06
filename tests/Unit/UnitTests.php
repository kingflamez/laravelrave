<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use ReflectionClass;
use ReflectionProperty;
use KingFlamez\Rave\Rave;
use Tests\Stubs\PaymentEventHandler;
use Tests\Concerns\ExtractProperties;

class UnitTests extends TestCase {

    use ExtractProperties;

    /**
     * Tests if app returns \KingFlamez\Rave\Rave if called with ailas.
     *
     * @test
     * @return \KingFlamez\Rave\Rave
     */
    function initiateRaveFromApp () {

        $rave = $this->app->make("laravelrave");

        $this->assertTrue($rave instanceof Rave);

        return $rave;
    }

    /**
     * Test Rave initiallizes with default values;.
     *
     * @test
     *
     * @depends initiateRaveFromApp
     * @param \KingFlamez\Rave\Rave $rave
     * @return void
     * @throws \ReflectionException
     */
    function initializeWithDefaultValues(Rave $rave) {

        $reflector = new ReflectionClass($rave);
        $currentEnv = $this->app->config->get("rave.env");

        $methods = $reflector->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach($methods as $method) {
            if ($method->getName() == 'env') $env = $method;
            if ($method->getName() == 'urls') $urls = $method;
            if ($method->getName() == 'baseUrl') $baseUrl = $method;
            if ($method->getName() == 'secretKey') $secretKey = $method;
            if ($method->getName() == 'publicKey') $publicKey = $method;
            if ($method->getName() == 'customLogo') $customLogo = $method;
            if ($method->getName() == 'customTitle') $customTitle = $method;
            if ($method->getName() == 'transactionPrefix') $transactionPrefix = $method;
            if ($method->getName() == 'overrideTransactionReference') $overrideTransactionReference = $method;
        };

        $env->setAccessible(true);
        $urls->setAccessible(true);
        $baseUrl->setAccessible(true);
        $publicKey->setAccessible(true);
        $secretKey->setAccessible(true);
        $customLogo->setAccessible(true);
        $customTitle->setAccessible(true);
        $transactionPrefix->setAccessible(true);
        $overrideTransactionReference->setAccessible(true);

        $this->assertFalse($overrideTransactionReference->getValue($rave));
        $this->assertEquals($this->app->config->get("rave.env"), $env->getValue($rave));
        $this->assertEquals($this->app->config->get("rave.logo"), $customLogo->getValue($rave));
        $this->assertEquals($this->app->config->get("rave.title"), $customTitle->getValue($rave));
        $this->assertEquals($this->app->config->get("rave.secretKey"), $secretKey->getValue($rave));
        $this->assertEquals($this->app->config->get("rave.publicKey"), $publicKey->getValue($rave));
        $this->assertEquals(
            $urls->getValue($rave)[($currentEnv === "live" ? "live" : "others")], $baseUrl->getValue($rave)
        );
        $this->assertEquals($this->app->config->get("rave.prefix")."_", $transactionPrefix->getValue($rave));
    }

    /**
     * Tests if transaction reference is generated.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function generatesTransactionReference (Rave $rave) {

        $ref = $rave->getReferenceNumber();

        $prefix = $this->app->config->get("rave.prefix");

        $this->assertRegExp("/^{$prefix}_\w{13}$/", $ref);
    }

    /**
     * Testing if environment is modified using setEnvironment.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingUpEnvironment(Rave $rave) {

        $newEnv = "live";
        $this->setProperty($rave, 'env', $newEnv);
        $env = $this->extractProperty($rave, "env");

        $this->assertEquals($newEnv, $env["value"]);
    }

    /**
     * Testing if prefix is modified using setPrefix.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function alterPrefix(Rave $rave) {

        $newPrefix = "org";
        $txRef = $rave->setPrefix($newPrefix, true)->createReferenceNumber()->getReferenceNumber();
        $transactionPrefix = $this->extractProperty($rave, "transactionPrefix");
        $overrideTransactionReference = $this->extractProperty($rave, "overrideTransactionReference");

        $this->assertStringStartsWith("org", $txRef);

        $this->assertTrue($overrideTransactionReference["value"]);
        $this->assertEquals("org", $transactionPrefix["value"]);
    }

    /**
     * Testing if amount is modified using setAmount.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingAmount(Rave $rave) {

        $newAmountInt = 30000;
        $newAmountDouble = 30000.50;

        $rave->setAmount($newAmountInt);
        $amountGetterInt = $rave->getAmount();
        $rave->setAmount($newAmountDouble);
        $amountGetterDouble = $rave->getAmount();

        $this->assertTrue(is_int( $amountGetterInt));
        $this->assertEquals($newAmountInt, $amountGetterInt);
        $this->assertTrue(is_double( $amountGetterDouble));
        $this->assertEquals($newAmountDouble, $amountGetterDouble);
    }

    /**
     * Testing if keys are modified using setkeys.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     * @throws \ReflectionException
     */
    function settingKeys(Rave $rave) {

        $newPublicKey = "public_key";
        $newSecretKey = "secret_key";
        $rave->setKeys($newPublicKey, $newSecretKey);
        $reflector = new ReflectionClass($rave);
        $reflector = $reflector->getProperties(ReflectionProperty::IS_PROTECTED);

        $keys = array_map(function($value) use ($rave, $newPublicKey, $newSecretKey) {
            $name = $value->getName();
            if ($name === "publicKey" || $name === "secretKey") {
                $value->setAccessible(true);
                $key = $value->getValue($rave);
                $this->assertEquals(${"new".ucfirst($name)}, $key);
            }
        }, $reflector);

    }

    /**
     * Testing if payment method is modified using setMethod.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingPaymentMethod(Rave $rave) {

        $newPaymentMethod = "card";
        $rave->setPaymentMethod($newPaymentMethod);
        $paymentMethodGetter = $rave->getPaymentMethod();

        $this->assertTrue(is_string($paymentMethodGetter));
        $this->assertEquals($newPaymentMethod, $paymentMethodGetter);
    }

    /**
     * Testing if description is modified using setDescription.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingDescription(Rave $rave) {

        $newDescription = "A new description";
        $rave->setDescription($newDescription);
        $descriptionGetter = $rave->getDescription();

        $this->assertTrue(is_string($descriptionGetter));
        $this->assertEquals($newDescription, $descriptionGetter);
    }

    /**
     * Testing if logo is modified using setLogo.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingLogo(Rave $rave) {

        $newLogo = "http://a.yu8.us/banner_rexegg3.png";
        $rave->setLogo($newLogo);
        $logoGetter = $rave->getLogo();

        $this->assertTrue(is_string( $logoGetter));
        $this->assertEquals($newLogo, $logoGetter);
    }

    /**
     * Testing if title is modified using setTitle.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingTitle(Rave $rave) {

        $newTitle = "http://a.yu8.us/banner_rexegg3.png";
        $rave->setTitle($newTitle);
        $titleGetter = $rave->getTitle();

        $this->assertTrue(is_string($titleGetter));
        $this->assertEquals($newTitle, $titleGetter);
    }

    /**
     * Testing if country is modified using setCountry.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingCountry(Rave $rave) {

        $newCountry = "Nigeria";
        $rave->setCountry($newCountry);
        $countryGetter = $rave->getCountry();

        $this->assertTrue(is_string($countryGetter));
        $this->assertEquals($newCountry, $countryGetter);
    }

    /**
     * Testing if currency is modified using setCurrency.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingCurrency(Rave $rave) {

        $newCurrency = "naira";
        $rave->setCurrency($newCurrency);
        $currencyGetter = $rave->getCurrency();

        $this->assertTrue(is_string( $currencyGetter));
        $this->assertEquals($newCurrency, $currencyGetter);
    }

    /**
     * Testing if email is modified using setEmail.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingEmail(Rave $rave) {

        $newEmail = "email@example.com";
        $rave->setEmail($newEmail);
        $emailGetter = $rave->getEmail();

        $this->assertTrue(is_string($emailGetter));
        $this->assertEquals($newEmail, $emailGetter);
    }

    /**
     * Testing if firstname is modified using setFirstname.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingFirstname(Rave $rave) {

        $newFirstname = "First";
        $rave->setFirstname($newFirstname);
        $firstnameGetter = $rave->getFirstname();

        $this->assertTrue(is_string( $firstnameGetter));
        $this->assertEquals($newFirstname, $firstnameGetter);
    }

    /**
     * Testing if lastname is modified using setLastname.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingLastname(Rave $rave) {

        $newLastname = "Last";
        $rave->setLastname($newLastname);
        $lastnameGetter = $rave->getLastname();

        $this->assertTrue(is_string($lastnameGetter));
        $this->assertEquals($newLastname, $lastnameGetter);
    }

    /**
     * Testing if phoneNumber is modified using setPhoneNumber.
     *
     * @test
     * @depends initiateRaveFromApp
     * @return void
     */
    function settingPhoneNumber(Rave $rave) {

        $newPhoneNumber = "08012345678";
        $rave->setPhoneNumber($newPhoneNumber);
        $phoneNumberGetter = $rave->getPhoneNumber();

        $this->assertTrue(is_string($phoneNumberGetter));
        $this->assertEquals($newPhoneNumber, $phoneNumberGetter);
    }

    /**
     * Testing if payButtonText is modified using setPayButtonText.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingPayButtonText(Rave $rave) {

        $newPayButtonText = "Pay Now";
        $rave->setPayButtonText($newPayButtonText);
        $payButtonTextGetter = $rave->getPayButtonText();

        $this->assertTrue(is_string($payButtonTextGetter));
        $this->assertEquals($newPayButtonText, $payButtonTextGetter);
    }

    /**
     * Testing if redirectUrl is modified using setRedirectUrl.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingRedirectUrl(Rave $rave) {

        $newRedirectUrl = "http://localhost/ipayed";
        $rave->setRedirectUrl($newRedirectUrl);
        $redirectUrlGetter = $rave->getRedirectUrl();

        $this->assertTrue(is_string( $redirectUrlGetter));
        $this->assertEquals($newRedirectUrl, $redirectUrlGetter);
    }

    /**
     * Testing if event handler is set using eventHandler.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     * @throws \ReflectionException
     */
    function settingEventHandler(Rave $rave) {

        $newEventHandler = new PaymentEventHandler;
        $rave->eventHandler($newEventHandler);
        $handler = $this->extractProperty($rave, "handler");

        $this->assertInstanceOf("Tests\Stubs\PaymentEventHandler", $handler["value"]);
    }

    /**
     * Testing if meta data is set using setMetaData.
     *
     * @test
     * @depends initiateRaveFromApp
     * @param Rave $rave
     * @return void
     */
    function settingMetaData(Rave $rave) {

        $newMetaData = ["date" => $date = Carbon::now()];
        $rave->setMetaData($newMetaData);
        $metaData = $rave->getMetaData();

        $this->assertEquals($metaData['date'], $date);
    }
}
