<?php

namespace Exercise\GigyaBundle\Rest;

use Exercise\GigyaBundle\GigyaRequestor;

class IdentityStorage
{
    const METHOD_SEARCH = 'ids.search';

    /**
     * @var \Exercise\GigyaBundle\GigyaRequestor
     */
    protected $requestor;

    public function __construct(GigyaRequestor $requestor)
    {
        $this->requestor = $requestor;
    }

    /**
     * @param  string $query
     * @return GSResponse
     */
    public function search($query)
    {
        return $this->requestor->sendRequest(self::METHOD_SEARCH, array(
            'query' => $query
        ));
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @param  integer $limit
     * @return GSArray|GSObject
     */
    public function findAccountsByField($field, $value, $limit = null)
    {
        $results = $this->search(sprintf('SELECT * FROM accounts WHERE %s = %s LIMIT %u', $field, $value, $limit))->getArray('results');
        if ($limit == 1 && $results->length() > 0) {
            return $results->getObject(0);
        }

        return $results;
    }
}
