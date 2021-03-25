<?php if($basket): ?>
<div id="basket-container" class="row">
    <h2 class="text-center">Your Basket</h2>
    <hr>
    <?php foreach($basket as $item): ?>
    <div class="search-result row" id="item_<?php echo $item['product_id'];?>">
        <div class="col-6 col-offset-2">
            <?php
                if($item['discount']){
                    echo "<span class='product-price light strike'>\${$item['price']}</span>";
                    echo "<span class='product-price'>\${$item['saleprice']}</span>";
                    echo "<span class='product-price product-price-sale'>{$item['discount']}% OFF</span>";
                }
                else{
                    echo "<span class='product-price'>\${$item['price']}</span>";
                }
                echo "<p class='product-title'>{$item['product_anchor']}</p>";
                echo "<p>Category: {$item['category_anchor']}</p>";
                echo "<p class='product-info'>Quantity:
                        <input type='number' min='0' class='product_amount' name='product_quantity' id='{$item['product_id']}' value='{$item['quantity']}'>
                      </p>";
            ?>
        </div>
        <div class="search-product-image col-2">
            <img class="img-responsive" src="<?php echo $item['product_image']; ?>" alt="image">
        </div>
    </div>
    <hr>

    <?php endforeach;?>
    <p class="text-center"><span class="bold">Total: </span> <?php echo sprintf("$%.2f", $total); ?></p>
    <div class="row">
        <div class="col-6">
            <button class='shop-btn' id='update_btn'>Update</button>
        </div>
        <div class="col-6">
            <button class='shop-btn' id='checkout_btn'><a href="/customer/checkout">Checkout</a></button>
        </div>
    </div>

    <script type="text/javascript">
        var initialBasket = [];
        $(document).ready(function(){
            setInitialBasket();
            $('button#update_btn').click(updateBasket);
        });

        //GLOBAL
        var xmlhttp;
        function requestUpdate(basket){
            xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST", "/ajax_query/basketupdate", true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.onreadystatechange = function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    var response = JSON.parse(xmlhttp.responseText);
                    if(response.status == "bad"){
                        notify("Basket not updated");
                        updateBasketDisplay(initialBasket);
                    }
                    else if(response.status == "updated"){
                        notify("Updated");
                        setInitialBasket();
                        updateBasketDisplay(response.rows);
                    }
                }
//                else if(xmlhttp.readyState != 2 && xmlhttp.readyState != 3){
                else{
                    notify("Could not update");
//                    updateBasketDisplay(initialBasket);
                }
            };

            var postData = "action=update";
            for(var i = 0; i < basket.length; i++ ){
                postData += "&product_id[]="+basket[i].id+"&quantity[]="+basket[i].quantity;
            }
            xmlhttp.send(postData);
        }


        function updateBasketDisplay(products){
            var allZero = true;
            for(var i = 0; i < products.length; i++){
                if(products[i].quantity === 0){
                    var itemId = "div#item_"+products[i].id;
                    var $item = $(itemId);
                    console.log(itemId + "" + products[i].quantity);
                    console.log($item);
                    $item.next('hr').remove();
                    $item.fadeOut(function(){
                        $(this).remove();
                    });
                }
                else{
                    var amountID = "input#"+products[i].id;
                    $(amountID).val(products[i].quantity);
                    allZero = false;
                }
            }
            //SHOW EMPTY BASKET
            if(allZero){
                $('div#basket-container').fadeOut(function(){
                    $(this).remove();
                });
                var $emptyBasketDiv = $('<div class="form-info bold text-center"></div>');
                $emptyBasketDiv.append($('<p>Your basket is empty</p>'));
                $('div#main-content').append($emptyBasketDiv);
            }
        }

        function getBasket(){
            var basketItems = document.getElementsByName('product_quantity');
            var products = [];
            for(var i = 0; i < basketItems.length; i++){
                var product = {id:basketItems[i].id, quantity:basketItems[i].value};
                products.push(product);
            }
            return products;
        }

        function updateBasket(){
            var products = getBasket();
            var nochange = true;
            for(var i = 0; i < products.length; i++){
                if(products[i].quantity != initialBasket[i].quantity){
                    nochange = false;
                }
            }

            if(nochange){
                notify("No changes made");
            }
            else{
                requestUpdate(products);
            }
        }

        function setInitialBasket(){
            initialBasket = getBasket();
        }

        function restoreInitialBasket(){
            var basketItems = document.getElementsByName('product_quantity');
            for(var i = 0; i < basketItems.length; i++){
                basketItems[i].value = initialBasket[i].quantity;
            }
        }
    </script>
</div>
<?php else: ?>
    <div class="form-info bold text-center"><p>Your basket is empty. SPEND SPEND SPEND!!!</p></div>
<?php endif; ?>


