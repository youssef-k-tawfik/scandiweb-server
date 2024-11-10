<?php

/**
 * This class represents a category.
 * It is used to fetch the categories from the database.
 * 
 * @package App\Model
 */

namespace App\Model;

use PDO;

include __DIR__ . '/../../config.php';

class Category
{
    public $name;

    public function __construct(string $name)
    {
        error_log("Creating a new Category");
        $this->name = $name;
    }

    public static function getCategories()
    {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT name FROM categories');

        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = new Category($row['name']);
        }

        return $categories;
    }
}
