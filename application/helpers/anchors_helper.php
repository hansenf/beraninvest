<?php
/**
 * Generate product link
 * @param $id
 * @param $innerHTML
 * @return string
 */
function productToAnchor($id, $innerHTML){
    $link = "/products/view/$id";
    return "<a href='$link'>$innerHTML</a>";
}

/**
 * Generate category anchor
 * @param $id
 * @param $innerHTML
 * @return string
 */
function categoryToAnchor($id, $innerHTML){
    $link = "/products/search?category_id=$id";
    return "<a href='$link'>$innerHTML</a>";
}
