<?php

class ShoppingCartService
{
    private const CART_KEY = "shopping_cart";

    // Lấy giỏ hàng
    public static function getCart(): array
    {
        if (!isset($_SESSION[self::CART_KEY])) {
            $_SESSION[self::CART_KEY] = [];
        }

        return $_SESSION[self::CART_KEY];
    }

    // Lấy 1 sản phẩm trong giỏ
    public static function getCartItem(int $productID): ?array
    {
        $cart = self::getCart();

        return $cart[$productID] ?? null;
    }

    // Thêm sản phẩm vào giỏ
    public static function addItem(array $item): void
    {
        $cart = self::getCart();
        $id = $item['ProductID'];

        if (!isset($cart[$id])) {
            $cart[$id] = $item;
        } else {
            $cart[$id]['Quantity'] += $item['Quantity'];
            $cart[$id]['SalePrice'] = $item['SalePrice'];
        }

        $_SESSION[self::CART_KEY] = $cart;
    }

    // Cập nhật sản phẩm
    public static function updateItem(int $productID, int $quantity, float $salePrice): void
    {
        $cart = self::getCart();

        if (isset($cart[$productID])) {
            $cart[$productID]['Quantity'] = $quantity;
            $cart[$productID]['SalePrice'] = $salePrice;

            $_SESSION[self::CART_KEY] = $cart;
        }
    }

    // Xóa 1 sản phẩm
    public static function removeItem(int $productID): void
    {
        $cart = self::getCart();

        if (isset($cart[$productID])) {
            unset($cart[$productID]);
            $_SESSION[self::CART_KEY] = $cart;
        }
    }

    // Xóa toàn bộ giỏ hàng
    public static function clearCart(): void
    {
        $_SESSION[self::CART_KEY] = [];
    }
}