<?php

namespace Backend;

class ApiResponse
{
    /**
     * @var array
     */
    private $nodes;

    /**
     * @var string
     */
    private $error;

    /**
     * @param array $nodes
     * @param string $error
     */
    public function __construct(array $nodes = [], string $error = "")
    {
        $this->nodes = $nodes;
        $this->error = $error;
    }

    /**
     * @param array $nodes
     */
    public function setNodes(array $nodes): void
    {
        $this->nodes = $nodes;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * Convert the response in a JSON payload.
     * @return string
     */
    public function generateJson(): string
    {
        $response = [];

        if (!empty($this->nodes)) {
            $response['nodes'] = $this->nodes;
        }

        if (!empty($this->error)) {
            $response['error'] = $this->error;
        }

        //JSON_NUMERIC_CHECK is to encode numeric strings as numbers
        return json_encode($response, JSON_NUMERIC_CHECK);
    }
}