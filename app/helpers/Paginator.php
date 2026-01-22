<?php
class Paginator {
    public static function paginate($db, $sql, $params=[], $page=1, $limit=10) {
        // Contar total
        $sqlCount = "SELECT COUNT(*) as c FROM (" . $sql . ") as conteo";
        $stmt = $db->prepare($sqlCount);
        $stmt->execute($params);
        $total = $stmt->fetch()['c'];
        $pages = ceil($total / $limit);
        
        // Obtener datos
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT $limit OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => $pages,
            'current' => $page
        ];
    }
}
