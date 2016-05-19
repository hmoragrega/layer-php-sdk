# WORK IN PROGRESS

# Layer PHP SDK

[![Build Status](https://travis-ci.org/hmoragrega/layer-php-sdk.svg?branch=master)](https://travis-ci.org/hmoragrega/layer-php-sdk)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4aa58def-cfd9-4a0c-9d5a-2ee57ec25b42/mini.png)](https://insight.sensiolabs.com/projects/4aa58def-cfd9-4a0c-9d5a-2ee57ec25b42)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/hmoragrega/layer-php-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/hmoragrega/layer-php-sdk/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/hmoragrega/layer-php-sdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/hmoragrega/layer-php-sdk/build-status/master)

A PHP library to ease the use of Layer's HTTP REST API.

## Disclaimer
This SDK is not endorsed by Layer (Layer, Inc.). 
Visit Layer at https://layer.com.

## Usage
The library is designed to have a single point of access, the Layer API client. 
Example:
```php
/** @var \UglyGremlin\Layer\Api\Client $layerClient */
$conversations = $layerClient->getUserConversations('123');
````

### Buiding the client
**TLDR** You can use the provided factory to easily create the client with one of the built in adapters. See _[Build the client trough the factory](#factory)_

To allow multiple php versions and libraries the creation of the client may look cumbersome, it requires three dependencies
```php
/** @var \UglyGremlin\Layer\Api\Client $layerClient */
$layerClient = \UglyGremlin\Layer\Api\Client($config, $httpClient, $uuidGenerator);
````
##### 1) Config
This object represents the API configuration values:
* Application id: The layer application id. You can find it on the _keys_ page for the desired layer project.
* Application token: The layer application token. Generated on the _integrations_ page for the desired layer project.
* Endpoint: The API URL endpoint, defaults to _https://api.layer.com_. You may want to change it for testing purpouses.
##### 2) HTTP client
This object is the one that performs the HTTP requests. They must implement the UglyGremlin\Layer\Http\ClientInterface.
There are three built in apapters:
* GuzzleHttpAdapter: Uses guzzle class (\Guzzle\Http\Client) to perform requests. Requires library _guzzlehttp/guzzle_. Recommended for projects with PHP >=5.4.0
* GuzzleAdapter: Uses guzzle class (\Guzzle\Http\Client) to perform requests. Requires library _guzzle/guzzle_. Recommended for projects with PHP <5.4.0
* CurlAdapter: Uses raw CURL requests. Requires PHP CURL extension loaded.
##### 3) Unique Id (uuid) generator
This dependency is used to generates [RFC 4122-compliant UUID](http://www.ietf.org/rfc/rfc4122.txt) to avoid request duplication. They must implement the UglyGremlin\Layer\Uuid\UuidGeneratorInterface.
There are two built in apapters:
* RamseyUuidGenerator: Uses _ramsey/uuid_ library to generate the ids. Recommended for projects with PHP >=5.4.0
* RhumsaaUuidGenerator: Uses _rhumsaa/uuid_ library to generate the ids. Recommended for projects with PHP <5.4.0

### <a name="factory"></a>Build the client trough the factory
You can use the provided factory _UglyGremlin\Layer\Api\ClientFactory_ to ease the construction of the layer client. 
```php
/**
 * This method will create a layer API client
 *
 * @param string|ClientInterface        $httpClient      The HTTP client
 * @param string|UuidGeneratorInterface $uuidGenerator   The UUID generator 
 * @param string                        $appKey          The application identifier
 * @param string                        $appToken        The application token
 * @param string                        $endpoint        The API endpoint
 *
 * @return Client
 *
 * @throws InvalidArgumentException  When one requested built-in classes does not exist
 * @throws RuntimeException          When there is a missing library or extension required to build the client
 */
public static function getClient($httpClient, $uuidGenerator, $appKey, $appToken, $endpoint = 'https://api.layer.com');
```
If you are going to use one of the provided adapater you can pass the identifier string for the HTTP client and the UUID generator.
The valid identifiers for the built in HTTP clients are:
* guzzle: When using _guzzlehttp/guzzle_
* guzzle-http: When using _guzzle/guzzle_
* curl: When using raw CURL requests

The valid identifiers for the built in UUID generators are
* ramsey: When using _ramsey/uuid_
* rhumsaa: When using _rhumsaa/uuid_
Examples:
```php
// Builds a client with the GuzzleAdapter and the RamseyUuidGenerator. Recommended for project with PHP >=5.4
$client = ClientFactory::getClient('guzzle-http', 'ramsey', 'appId', 'appToken');

// Builds a client with the GuzzleAdapter and the RhumsaaUuidGenerator. Recommended for project with PHP <5.4
$client = ClientFactory::getClient('guzzle-http', 'ramsey', 'appId', 'appToken', 'http://test.endpoint');
```
The factory has two more methods to configure the execution timeout and the connection timeout for the HTTP client. Both methods expect the value to be in seconds, but you can pass float to indicate fractions. Call them befor building the client.
```php
// Set the execution timeout to 2 seconds ()
Factory::setTimeout(2);
// Set the conection timeout to 0.5 seconds (500 milliseconds)
Factory::setConnectionTimeout(0.5);
// Build the client
$client = ClientFactory::getClient('guzzle-http', 'ramsey', 'appId', 'appToken');
```
### Using the client
The client provides methods that maps those on the layer API documentation.
The reponse will always be an object of the class _UglyGremlin\Layer\Api\Response_ with these methods:
* getstat
### Implemented Layer API methods
#### Get user conversations
See https://developer.layer.com/docs/platform/conversations#retrieve-a-conversation
$layer->
