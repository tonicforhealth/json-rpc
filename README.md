JSON-RPC
========

Handles [JSON-RPC](http://www.jsonrpc.org/specification) requests. 
 
[![Build Status](https://scrutinizer-ci.com/g/tonicforhealth/json-rpc/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/json-rpc/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonicforhealth/json-rpc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/json-rpc/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f8096981-b240-4d8e-87a7-061921a7cb28/mini.png)](https://insight.sensiolabs.com/projects/f8096981-b240-4d8e-87a7-061921a7cb28)

Installation
------------
   
### Require dependencies via composer: 

```
$ composer require tonicforhealth/model-transformer
```

Usage
----- 

Design API handler: 

```php
<?php

namespace TonicForHealth\SurveyDesigner\Api;

use TonicForHealth\SurveyDesigner\Api\Request\SurveyGetRequest;
use TonicForHealth\SurveyDesigner\Api\Response\SurveyGetResponse;
use TonicForHealth\SurveyDesigner\Entity\Repository\SurveyRepository;

use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Annotation as RPCSec;
use Tonic\Component\ApiLayer\JsonRpc\Annotation as RPC;
use Tonic\Component\ApiLayer\ModelTransformer\ModelTransformer;

class SurveyApi
{
    /**
     * @var ModelTransformer
     */
    private $modelTransformer;

    /**
     * @var SurveyRepository
     */
    private $surveyRepository;

    /**
     * Constructor.
     *
     * @param ModelTransformer $modelTransformer
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(ModelTransformer $modelTransformer, SurveyRepository $surveyRepository)
    {
        $this->modelTransformer = $modelTransformer;
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * Get survey by specified identifier.
     *
     * @RPC\Method(name = "survey.get")
     *
     * @param SurveyGetRequest $surveyGetRequest
     *
     * @return SurveyGetResponse
     */
    public function get(SurveyGetRequest $surveyGetRequest)
    {
        $survey = $this->surveyRepository->find($surveyGetRequest->surveyId);

        return $this->modelTransformer->transform($survey, SurveyGetRequest::class);
    }
        
    // ...
}
```

With request: 

```php
<?php

namespace TonicForHealth\SurveyDesigner\Api\Request;

class SurveyGetRequest
{
    /**
     * Survey id.
     *
     * @var int
     */
    public $surveyId;
}
```

And response: 

```php
<?php

namespace TonicForHealth\SurveyDesigner\Api\Response;

class SurveyGetResponse
{
    /**
     * Survey name.
     *
     * @var string
     */
    public $name;
}
```

[Model transformer library](https://github.com/tonicforhealth/model-transformer) can be used for transforming domain object to response objects.

Create loader and register methods: 
 
```php
$loader = new \Tonic\Component\ApiLayer\JsonRpc\Method\Loader\MutableAnnotationLoader(new \Doctrine\Common\Annotations\AnnotationReader());
$loader->add(new \TonicForHealth\SurveyDesigner\Api\SurveyApi($modelTransformer, new \TonicForHealth\SurveyDesigner\Entity\Repository\SurveyRepository()));
```

Create server:  

```php
$server = (new \Tonic\Component\ApiLayer\JsonRpc\ServerFactory())->create(
        $loader,
        new \Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapper(
            new \Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\Normalizer()
        ),
        new \Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvoker()
    );
```

Handle request:
 
```php
$response = $server->handle('{"jsonrpc": "2.0", "method": "calc.subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}');
// $response = '{"jsonrpc": "2.0", "result": 19, "id": 3}'; 
```

Specifications
--------------

All actual documentation is runnable library specifications at `/spec` directory. 

And to ensure library is not broken, run (under library directory):

```
bin/phpspec run
```

Ideas & Improvements
--------------------

- Resolve FQCN and FQCN of collections in request and response;
- Resolve FQCN aliases;
- Metadata of methods can be more abstract in reused in different layers;
- Solve problem with validation messages;
- Error codes convention;
- Remove arrays from documentation generator;
- Add batch possibilities;
- Add notification methods (without id);
- Write simple API client;
- Write full documentation;
- Add model transformer aware interface; 
- Add error handling for normalizers;
- Normalize should check denormalization errors;
- Handle collection types in type resolver;
- Parse use block to work with FQCN in normalizers detect type method;
- Add exceptions to documentation.
