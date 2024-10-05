<?php

/**
 * This class represents the database model.
 * It is used to fetch the products from the database.
 * It also allows to set filters for the query. (category, id)
 * 
 * @package App\Model
 */

namespace App\Model;

use PDO;
use PDOException;
use Exception;

include __DIR__ . '/../../config.php';

class Database
{
    public $pdo;
    public $filters = [];
    public $baseQuery = "
        SELECT 
            p.id, p.name, p.in_stock AS inStock, p.description, 
            c.name AS category, 
            b.name AS brand, 
            g.image AS gallery_image, 
            pr.price AS price, 
            cu.label AS currency_label,
            cu.symbol AS currency_symbol,
            a.attribute_item_id AS attribute_id,
            a.value AS attribute_value,
            a.display_value AS attribute_display_value,
            aSet.name AS attribute_set_name,
            aSet.type AS attribute_set_type,
            aSet.attribute_set_id
        FROM 
            products p
        JOIN 
            categories c ON p.category_id = c.id
        JOIN 
            brands b ON p.brand_id = b.id
        LEFT JOIN 
            galleries g ON p.pid = g.product_id
        LEFT JOIN 
            prices pr ON p.pid = pr.product_id
        LEFT JOIN 
            currencies cu ON pr.currency_id = cu.id
            LEFT JOIN
            product_attributes pa ON p.pid = pa.product_id
            LEFT JOIN
            attribute_items a ON pa.attribute_item_id = a.id
            LEFT JOIN
            attribute_sets aSet ON a.attribute_set_id = aSet.id
            ";
    public static $instance = null;

    public function __construct()
    {
        error_log("Creating database instance...");
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection error.");
        }
    }

    // Trying singleton pattern
    // ! however it is being initialized each time a request is received
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Sets a filter for the query.
     *
     * @param string $key The filter key
     * @param mixed $value The filter value
     * @return $this The current instance for method chaining
     */
    public function setFilter($key, $value)
    {
        $this->filters[$key] = $value;
        // * Enable method chaining
        return $this;
    }

    /**
     * Builds the SQL query based on the set filters.
     *
     * @return array An array containing the SQL query and the parameters
     */
    public function buildQuery()
    {
        $conditions = [];
        $params = [];

        // * Check if category filter is set
        if (!empty($this->filters['category'])) {
            $conditions[] = "c.name = :category";
            $params[':category'] = $this->filters['category'];
        }

        // // * Check if brand filter is set
        // if (!empty($this->filters['brand'])) {
        //     $conditions[] = "b.name = :brand";
        //     $params[':brand'] = $this->filters['brand'];
        // }

        // * Check if id filter is set
        if (!empty($this->filters['id'])) {
            $conditions[] = "p.id = :id";
            $params[':id'] = $this->filters['id'];
        }

        // * prepare the query
        $query = $this->baseQuery;
        if ($conditions) {
            //* WHERE c.name = :category AND b.name = :brand AND p.pid = :id
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        return [$query, $params];
    }

    public function query()
    {
        error_log("Building query...");
        list($sqlQuery, $params) = $this->buildQuery();
        $stmt = $this->pdo->prepare($sqlQuery);
        // error_log("stmt: " . print_r($stmt, true));
        error_log("Querying database...");
        $stmt->execute($params);
        error_log("Query executed successfully!");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
