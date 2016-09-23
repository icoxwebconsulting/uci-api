<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Yaml\Yaml;

/**
 * Defines application features from the specific context.
 *
 * Class FeatureContext
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * string mongo id regex
     */
    const MONGO_ID_REGEX = '/[\a-z|\d]+/';

    /**
     * string acces token regex
     */
    const ACCESS_TOKEN_REGEX = '/[\a-z|\d]+/';

    /**
     * string refresh token regex
     */
    const REFRESH_TOKEN_REGEX = '/[\a-z|\d]+/';

    /**
     * @var string base request url
     */
    private $baseUrl;

    /**
     * @var MongoDB\Client
     */
    private $dbClient;

    /**
     * @var MongoDB
     */
    private $db;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var int
     */
    private $responseCode;

    /**
     * @var string
     */
    private $responseType;

    /**
     * @var array
     */
    private $responseBody;

    /**
     * FeatureContext constructor.
     *
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     * @param $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->configDatabase();
    }

    /**
     * Create database client and connect to database
     */
    private function configDatabase()
    {
        $config = Yaml::parse(file_get_contents('app/config/parameters.yml'));
        $dbURI = $config["parameters"]["mongodb_server"];
        $dbName = $config["parameters"]["mongodb_database"];
        $this->dbClient = new MongoDB\Client($dbURI);
        $this->db = $this->dbClient->$dbName;
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $this->clean();
    }

    /**
     * Clean
     */
    private function clean()
    {
        $this->cleanDatabase();
        $this->resetVariables();
    }

    /**
     * Clean the database
     */
    private function cleanDatabase()
    {
        $collections = $this->db->listCollections();
        foreach ($collections as $collection) {
            $collectionName = $collection->getName();
            $this->db->$collectionName->deleteMany([]);
        }
    }

    /**
     * Reset variables;
     */
    private function resetVariables()
    {
        $this->headers = "{}";
        $this->payload = "{}";
        $this->responseCode = null;
        $this->responseType = null;
        $this->responseBody = null;
    }

    /**
     * @AfterScenario
     * @param AfterScenarioScope $scope
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->clean();
    }

    /**
     * @Given I have the following headers
     * @param PyStringNode $headers
     */
    public function iHaveTheFollowingHeaders(PyStringNode $headers)
    {
        $this->headers = $headers->getRaw();
    }

    /**
     * @Given I have the following payload
     * @param PyStringNode $payload
     */
    public function iHaveTheFollowingPayload(PyStringNode $payload)
    {
        $this->payload = $payload->getRaw();
    }

    /**
     * @When I make a GET request to :URL
     * @param string $URL
     */
    public function iMakeAGetRequest($URL)
    {
        $this->makeRequest('GET', $URL);
    }

    /**
     * Make a Guzzle PSR7 Async Request
     *
     * @param string $method
     * @param string $url
     * @throws GuzzleHttp\Exception\ClientException
     */
    private function makeRequest(string $method, string $url)
    {
        $client = new GuzzleHttp\Client();
        $url = sprintf("%s%s", $this->baseUrl, $url);
        $headers = json_decode($this->headers, true);
        $payload = json_decode($this->payload, true);
        $request = new \GuzzleHttp\Psr7\Request($method, $url, $headers);
        $options = array();
        if ($payload !== null) {
            switch ($method) {
                case 'GET': {
                    $options['query'] = $payload;
                    break;
                }
                case 'POST':
                case 'PUT': {
                    $options['json'] = $payload;
                    break;
                }
            }
        }
        $promise = $client->sendAsync($request, $options)
            ->then(
                function (\Psr\Http\Message\ResponseInterface $response) {
                    $this->responseCode = $response->getStatusCode();
                    $this->responseType = $response->getHeaderLine('content-type');
                    $this->responseBody = $response->getBody();
                },
                function (\GuzzleHttp\Exception\RequestException $reason) {
                    $response = $reason->getResponse();
                    $this->responseCode = $response->getStatusCode();
                    $this->responseType = $response->getHeaderLine('content-type');
                    $this->responseBody = $response->getBody();
                }
            );
        $promise->wait();
    }

    /**
     * @When I make a POST request to :URL
     * @param string $URL
     */
    public function iMakeAPostRequest($URL)
    {
        $this->makeRequest('POST', $URL);
    }

    /**
     * @When I make a PUT request to :URL
     * @param string $URL
     */
    public function iMakeAPutRequest($URL)
    {
        $this->makeRequest('PUT', $URL);
    }

    /**
     * @When I make a DELETE request to :URL
     * @param string $URL
     */
    public function iMakeADeleteRequest($URL)
    {
        $this->makeRequest('DELETE', $URL);
    }

    /**
     * @Then I get a successful response
     * @throws Exception
     */
    public function iGetASuccessfulResponse()
    {
        $this->validateResponseCode(200);
    }

    /**
     * @param $code
     * @throws Exception
     */
    private function validateResponseCode($code)
    {
        if ($this->responseCode !== $code) {
            throw new Exception(
                sprintf("Response status code does not match expected, current is %s", $this->responseCode)
            );
        }
    }

    /**
     * @Then I get an API error response
     * @throws Exception
     */
    public function iGetAnAPIErrorResponse()
    {
        $this->validateResponseCode(500);
    }

    /**
     * @Then I get a CLIENT error response
     * @throws Exception
     */
    public function iGetACLIENTErrorResponse()
    {
        $this->validateResponseCode(400);
    }

    /**
     * @Then I get a FORBIDDEN error response
     * @throws Exception
     */
    public function iGetAFORBIDDENErrorResponse()
    {
        $this->validateResponseCode(403);
    }

    /**
     * @Then I get an UNAUTHORIZED error response
     * @throws Exception
     */
    public function iGetAnUNAUTHORIZEDErrorResponse()
    {
        $this->validateResponseCode(401);
    }

    /**
     * @Then I validate is JSON response
     * @throws Exception
     */
    public function iValidateIsJSONResponse()
    {
        if ($this->responseType !== "application/json" &&
            $this->responseType !== "application/json; charset=UTF-8"
        ) {
            throw new Exception(sprintf("Response type does not match expected, current is %s", $this->responseType));
        }
    }

    /**
     * @Then I validate the response is
     * @param PyStringNode $responseData
     * @throws Exception
     */
    public function iValidateTheResponseIs(PyStringNode $responseData)
    {
        $expectedResponseData = json_decode($responseData->getRaw(), true);
        $actualResponseData = json_decode($this->responseBody, true);
        if (!is_array($expectedResponseData) ||
            !is_array($actualResponseData) ||
            !$this->validateDeepArray($expectedResponseData, $actualResponseData)
        ) {
            throw new Exception(sprintf("Response body does not match expected, current is %s", $this->responseBody));
        }
    }

    /**
     * Validate recursively data in provided arrays
     *
     * @param array $expected
     * @param array $actual
     * @return bool
     */
    private function validateDeepArray(array $expected, array $actual):bool
    {
        foreach ($expected as $key => $expectedValue) {
            if (array_key_exists($key, $actual)) {
                $actualValue = $actual[$key];

                $actualValue = $this->formatActualValue($actualValue);

                if (is_array($actualValue) &&
                    is_array($expectedValue) &&
                    $this->validateDeepArray($expectedValue, $actualValue)
                ) {
                    continue;
                } elseif ($actualValue === $expectedValue ||
                    ($expectedValue === "MONGO_ID()" && preg_match(self::MONGO_ID_REGEX, $actualValue) === 1) ||
                    ($expectedValue === "ACCESS_TOKEN()" && preg_match(self::ACCESS_TOKEN_REGEX, $actualValue) === 1) ||
                    ($expectedValue === "REFRESH_TOKEN()" && preg_match(self::REFRESH_TOKEN_REGEX, $actualValue) === 1)
                ) {
                    continue;
                } elseif (!is_array($expectedValue) && strpos($expectedValue, 'REGEX(') !== false) {
                    $regex = substr($expectedValue, 6, strlen($expectedValue) - 7);
                    $regex = str_replace('/', '\\/', $regex);
                    $regex = str_replace('.', '\\.', $regex);
                    $regex = str_replace('WORD', '[\\w-]+', $regex);
                    $regex = sprintf('/%s/', $regex);
                    if (preg_match($regex, $actualValue) === 1) {
                        continue;
                    }
                } elseif ($key === 'href' && $this->compareHREF($actualValue, $expectedValue)) {
                    continue;
                }

                return false;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $actualValue
     * @return mixed
     */
    private function formatActualValue($actualValue)
    {
        if ($actualValue instanceof \MongoDB\Model\BSONDocument ||
            $actualValue instanceof \MongoDB\Model\BSONArray
        ) {
            $actualValue = $actualValue->getArrayCopy();
        }

        if ($actualValue instanceof \MongoDB\BSON\ObjectID ||
            $actualValue instanceof \MongoDB\BSON\UTCDatetime
        ) {
            $actualValue = (string)$actualValue;
        }

        return $actualValue;
    }

    /**
     * Compare two href
     *
     * @param string $actualValue
     * @param string $expectedValue
     * @return bool
     */
    private function compareHREF(string $actualValue, string $expectedValue):bool
    {
        // clean env entry point if there is any, workaround for dev env
        $actualValue = str_replace("/app_dev.php", "", $actualValue);
        $expectedValue = str_replace("/app_dev.php", "", $expectedValue);

        // if there is any MONGO_ID present
        if (strpos($expectedValue, 'MONGO_ID') !== false) {
            $parts = explode('MONGO_ID()', $expectedValue);
            $actualValueFormatted = $actualValue;
            foreach ($parts as $part) {
                if ($part !== '') {
                    $actualValueFormatted = str_replace($part, '@#@', $actualValueFormatted);
                }
            }

            $parts = explode('@#@', $actualValueFormatted);

            foreach ($parts as $part) {
                if ($part !== '') {
                    $expectedValue = str_replace('MONGO_ID()', $part, $expectedValue);
                }
            }
        }

        if (strcmp($actualValue, $expectedValue) === 0) {
            return true;
        }

        return false;
    }

    /**
     * @Given I have the following data on collection :collectionName
     * @param string $collectionName
     * @param PyStringNode $collectionData
     * @throws Exception
     */
    public function iHaveTheFollowingDataOnCollection($collectionName, PyStringNode $collectionData)
    {
        $data = json_decode($collectionData->getRaw(), true);

        foreach ($data as $index => $element) {
            foreach ($element as $key => $value) {
                if ($key === '_id') {
                    $data[$index][$key] = new \MongoDB\BSON\ObjectID($value);
                }

                if (strpos($key, "@") !== false) {
                    $key = explode("@", $key)[1];
                    $data[$index][$key] = array(
                        "\$ref" => $value["\$ref"],
                        "\$id" => new \MongoDB\BSON\ObjectID($value["\$id"]),
                        "\$db" => $value["\$db"],
                    );
                }

                if ($value === "UNIX_TIME()") {
                    $data[$index][$key] = time();
                }
            }
        }

        $collection = $this->db->$collectionName;

        foreach ($data as $document) {
            $collection->insertOne($document);
        }
    }

    /**
     * @Then I validate the following data exists on collection :collectionName
     * @param string $collectionName
     * @param PyStringNode $collectionData
     * @throws Exception
     */
    public function iValidateTheFollowingDataExistsOnCollection($collectionName, PyStringNode $collectionData)
    {
        $expectedCollectionData = json_decode($collectionData->getRaw(), true);

        foreach ($expectedCollectionData as $item) {
            $found = true;
            $documents = $this->db->$collectionName->find();

            foreach ($documents as $document) {
                $found = $this->validateDeepArray($item, $document->getArrayCopy());

                if ($found) {
                    break;
                }
            }

            if (!$found) {
                throw new Exception("Data does not exist on db");
            }
        }
    }

    /**
     * @Then I validate only the following data exists on collection :collectionName
     * @param string $collectionName
     * @param PyStringNode $collectionData
     * @throws Exception
     */
    public function iValidateOnlyTheFollowingDataExistsOnCollection($collectionName, PyStringNode $collectionData)
    {
        $expectedCollectionData = json_decode($collectionData->getRaw(), true);
        $documents = $this->db->$collectionName->find()->toArray();

        if (count($expectedCollectionData) != count($documents)) {
            throw new Exception("Data is not the only thing on db");
        }

        foreach ($expectedCollectionData as $item) {
            $found = true;

            foreach ($documents as $document) {
                $found = $this->validateDeepArray($item, $document->getArrayCopy());
            }

            if (!$found) {
                throw new Exception("Data does not exist on db");
            }
        }
    }
}
