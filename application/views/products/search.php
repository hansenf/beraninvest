<div class="search-results-container row">
    <?php
    echo "<h2>Search results: <span style='font-weight: normal; font-size: 0.8em'>";
            $numberOfResults = count($products);
            if($numberOfResults == 0){ echo "No items found"; }
            elseif($numberOfResults == 1){ echo "$numberOfResults item found"; }
            else{ echo "$numberOfResults items found"; }
            echo "</span></h2>";
            $query = $this->input->get('query');
            if($query){ echo "<p><em>Searched for: ".htmlspecialchars($query)."</em></p>"; }
            echo "<hr>";
    ?>

    <?php foreach($products as $product): ?>
        <div class="search-result row">
            <div class="col-6 col-offset-1">
                <?php
                if($product['discount']){
                    echo "<span class='product-price light strike'>\${$product['price']}</span>";
                    echo "<span class='product-price'>\${$product['saleprice']}</span>";
                    echo "<span class='product-price product-price-sale'>{$product['discount']}% OFF</span>";
                }
                else{
                    echo "<span class='product-price'>\${$product['price']}</span>";
                }
                echo "<p class='product-title'>{$product['product_anchor']}</p>";
                echo "<p>Category: {$product['category_anchor']}</p>";
                ?>
            </div>

            <div class="search-product-image col-2 col-offset-2">
                <img class="img-responsive" src="<?php echo $product['product_image']; ?>" alt="image">
            </div>

        </div>
        <hr>

    <?php endforeach; ?>
</div>
