<?php

namespace Backend\Database\Repository;

use Backend\Exception\NodeNotFoundException;

class NodeTreeRepository extends AbstractRepository
{
    /**
     * @var string
     */
    public static $tableName = "node_tree";

    /**
     * Retrieves all child nodes of the parent node id.
     * @param int $parentIdNode
     * @param string $language
     * @param string $searchKeyword
     * @param int $pageNum
     * @param int $pageSize
     * @return array
     * @throws NodeNotFoundException
     */
    public function findNodes(
        int $parentIdNode,
        string $language,
        string $searchKeyword = "",
        int $pageNum = 0,
        int $pageSize = 100
    ): array {
        $params = [
            'parentIdNode' => $parentIdNode,
            'languageValue' => $language,
        ];

        $sqlSearchKeyWord = "";
        if (!empty($searchKeyword)) {
            $sqlSearchKeyWord = "AND LOWER(Names.nodeName) LIKE :searchKeyword ";
            $params['searchKeyword'] = "%" . strtolower($searchKeyword) . "%";
        }

        $sqlLimit = "LIMIT " . ($pageNum * $pageSize) . ", " . $pageSize;

        /**
         * The subquery retrieves all the child nodes of the give parent idNode and the main query makes a conditional
         * sum to count the founded child nodes' children
         */
        $sql = "SELECT 
                    Parent.idNode AS node_id,
                    Parent.nodeName AS name,
                    SUM(IF((Child.level = Parent.level + 1
                            AND Child.iLeft > Parent.iLeft
                            AND Child.iRight < Parent.iRight),
                        1,
                        0)) AS children_count
                FROM
                    (SELECT 
                        Child.idNode,
                            Child.iLeft,
                            Child.iRight,
                            Child.level,
                            Names.nodeName
                    FROM
                        " . self::$tableName . " AS Child
                    LEFT JOIN " . NodeTreeNamesRepository::$tableName . " AS Names ON Child.idNode = Names.idNode AND Names.language = :languageValue,
                        " . self::$tableName . " AS Parent
                    WHERE
                        Child.level = Parent.level + 1
                        AND Child.iLeft > Parent.iLeft
                        AND Child.iRight < Parent.iRight
                        AND Parent.idNode = :parentIdNode
                        " . $sqlSearchKeyWord . "
                        ) AS Parent,
                    " . self::$tableName . " AS Child
                GROUP BY Parent.idNode, Parent.nodeName
                " . $sqlLimit;

        $result = $this->db->findAll($sql, $params);

        if (empty($result)) {
            $exist = $this->nodeExists($parentIdNode);
            if (!$exist) {
                throw new NodeNotFoundException("Invalid node id");
            }
        }

        return $result;
    }

    /**
     * Check if the node exists.
     * @param int $nodeId
     * @return bool
     */
    private function nodeExists(int $nodeId): bool
    {
        $sql = "SELECT idNode FROM " . self::$tableName . " WHERE idNode = :idNode";
        $result = $this->db->findOne($sql, ['idNode' => $nodeId]);

        return !empty($result);
    }
}