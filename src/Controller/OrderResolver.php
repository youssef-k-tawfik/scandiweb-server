<?php

/**
 * This class is responsible for resolving placeOrder mutation.
 * It resolves the order items and its attributes.
 * It uses Transactions to ensure that the order is placed successfully.
 * 
 * @package App\Controller
 */

namespace App\Controller;

use PDO;

include __DIR__ . '/../../config.php';

class OrderResolver
{
    public static function placeOrder($root, $args)
    {
        // error_log("Placing order: " . json_encode($args));

        // * Create a new PDO instance
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $pdo->beginTransaction();

            // * Generate a new order number
            // $orderNumber = date('Ym') . '00' . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $orderNumber = date('Ym') . '00' . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            error_log("Order number: " . $orderNumber);

            //* Insert order number into orders table
            $stmt = $pdo->prepare("INSERT INTO orders (order_number) VALUES (:order_number)");
            $stmt->execute(['order_number' => $orderNumber]);
            $orderId = $pdo->lastInsertId();
            // error_log("Order id: " . $orderId);

            // * Insert order items
            foreach ($args['orderItems'] as $orderItem) {
                // * Get pid from products table
                $stmt = $pdo->prepare("SELECT pid FROM products WHERE id = :product_id");
                $stmt->execute(['product_id' => $orderItem['product_id']]);
                $pid = $stmt->fetchColumn();
                // error_log("Product id: " . $pid);

                // * Insert order item into order_items table
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $pid,
                    'quantity' => $orderItem['quantity'],
                ]);
                $orderItemId = $pdo->lastInsertId();

                // * Insert order item attributes
                foreach ($orderItem['selectedAttributes'] as $attribute) {
                    // * Get attribute set id
                    $stmt = $pdo->prepare("SELECT id FROM attribute_sets WHERE name = :attribute_set_id");
                    $stmt->execute(['attribute_set_id' => $attribute['id']]);
                    $attributeSetId = $stmt->fetchColumn();
                    // error_log("Attribute set id: " . $attributeSetId);

                    // * Get attribute item id
                    $stmt = $pdo->prepare("SELECT id FROM attribute_items WHERE value = :value AND attribute_set_id = :attribute_set_id");
                    $stmt->execute([
                        'value' => $attribute['value'],
                        'attribute_set_id' => $attributeSetId,
                    ]);
                    $attribute_id = $stmt->fetchColumn();
                    // error_log("Attribute item id: " . $attribute_id);

                    // * Insert order item attribute
                    $stmt = $pdo->prepare("INSERT INTO order_item_attributes (order_item_id, attribute_item_id) VALUES (:order_item_id, :attribute_item_id)");
                    $stmt->execute([
                        'order_item_id' => $orderItemId,
                        'attribute_item_id' => $attribute_id,
                    ]);
                }
            }

            $pdo->commit();
            return ['order_number' => $orderNumber];
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log('Failed to place order: ' . $e->getMessage());
            return ['order_number' => '0']; // Return '0' to indicate failure
            // throw new RuntimeException('Failed to place order: ' . $e->getMessage());
        }
    }
}
