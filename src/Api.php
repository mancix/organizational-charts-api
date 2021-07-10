<?php

namespace Backend;

use Backend\Exception\InvalidParamException;
use Backend\Exception\NodeNotFoundException;
use Backend\Utils\Configuration;
use Backend\Database\Db;
use Backend\Database\Repository\NodeTreeRepository;
use Backend\Validator\NodeParamsValidator;

class Api
{
    /**
     * @var array
     */
    private $request;
    /**
     * @var ApiResponse
     */
    private $response;
    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->response = new ApiResponse();
    }

    /**
     * Initialize the entrypoint of the API.
     */
    public function init(): void
    {
        try {
            $this->validateRequest();

            $dbConfig = Configuration::getDatabaseConfig();
            $db = new Db(
                $dbConfig['host'],
                $dbConfig['name'],
                $dbConfig['port'],
                $dbConfig['user'],
                $dbConfig['password']
            );

            $nodeTreeRepo = new NodeTreeRepository($db);

            $result = $nodeTreeRepo->findNodes(
                $this->request['node_id'],
                $this->request['language'],
                $this->request['search_keyword'] ?? '',
                $this->request['page_num'] ?? 0,
                $this->request['page_size'] ?? 100
            );

            $this->response->setNodes($result);

        } catch (InvalidParamException $e) {
            $this->response->setError($e->getMessage());
            $this->statusCode = 400;
        } catch (NodeNotFoundException $e) {
            $this->response->setError($e->getMessage());
            $this->statusCode = 404;
        } catch (\Exception $e) {
            $this->response->setError("Internal server error: " . $e->getMessage());
            $this->statusCode = 500;
        }

        $this->sendResponse();
    }

    /**
     * Check the validity of the parameters, if they are not valid throw an exception.
     * @throws InvalidParamException
     */
    private function validateRequest(): void
    {
        if (!NodeParamsValidator::isValid($this->request)) {
            throw new InvalidParamException(implode(" - ", NodeParamsValidator::getMessages()));
        }
    }

    /**
     * Send a JSON payload with the proper header and HTTP status code.
     */
    private function sendResponse(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->statusCode);
        echo $this->response->generateJson();
    }
}