<?php

declare(strict_types=1);

namespace App\Tests\Behat\User;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;

class UserCreationContext extends MinkContext implements Context
{
    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $payload): void
    {
        $this->getSession()->getDriver()->getClient()->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], $payload->getRaw());
    }

    /**
     * @When I request :arg1 with POST
     */
    public function iRequestWithPost($arg1): void
    {
        $this->getSession()->getDriver()->getClient()->request('POST', $arg1);
    }

//    /**
//     * @Then the response status code should be :arg1
//     */
//    public function theResponseStatusCodeShouldBe($arg1): void
//    {
//        $this->assertSession()->statusCodeEquals($arg1);
//    }

    /**
     * @Then the response should contain json:
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        $this->assertSession()->responseContains($jsonString->getRaw());
    }
}