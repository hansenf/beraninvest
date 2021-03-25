<?php if($product):?>
    <div class="product-container row" ng-app="addApp" ng-controller="addCtrl">
        <div class="col-12 layout-center">
            <div class="row text-center">
                <div class="col-6">
                    <img class="img-responsive" src="<?php echo $product['product_image']; ?>" alt="image">
                </div>
                <div class="col-6">
                    <?php
                    if($product['discount']){
                        echo "<span class='product-price light strike'>\${$product['price']}</span>";
                        echo "<span class='product-price'>\${$product['saleprice']}</span>";
                        echo "<span class='product-price product-price-sale'>{$product['discount']}% OFF</span>";
                    }
                    else{
                        echo "<span class='product-price'>\${$product['price']}</span>";
                    }
                    echo '<br><form name="form"><button ng-click="addToBasket()" ng-disabled="form.$invalid" class="shop-btn space-out" id="buy_btn">Add to Basket</button>';
                    echo "Quantity: <input ng-model='quantity' type='number' min='1' max='10' class='product_amount' id='{$product['product_id']}' value='1' required></form>";
                    echo "<h2 class='product-title'>{$product['product_anchor']}</h2>";
                    echo "<p>Category: {$product['category_anchor']}</p>";
                    echo "<p>Description: {$product['product_description']}</p>";
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($similar): ?>
    <div class="row layout-center">
        <hr>
        <div class="col-8 col-offset-2">
            <div class="row layout-center text-center">
                <h3>Customers also bought...</h3>
                <?php foreach($similar as $index=>$item):
                    $offset = "";
                    if(count($similar) == 2){ $offset = " col-offset-2"; }
                    elseif(count($similar) == 1){ $offset = " col-offset-4"; }
                    ?>
                    <div class="similar-item col-4<?php if($index == 0) {echo $offset;} ?>">
                        <?php echo "<p style='font-size: 0.9em;' class='product-title'>".$item['product_anchor']."</p><br>"; ?>
                        <img class="img-responsive" src="<?php echo $item['product_image']; ?>" alt="image"/>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        var addApp = angular.module("addApp", []);
        addApp.controller("addCtrl", function($scope, $http){
            $scope.quantity = 1;

            //DETAILS CHANGE
            $scope.addToBasket = function(){
                if($scope.form.$valid){
                    var actiondata = { action: "add", quantity: $scope.quantity, product_id:<?php echo $product['product_id']; ?>};
                    actiondata = $.param(actiondata);

                    $http({
                        method: 'POST',
                        url: "/ajax_query/basketadd",
                        data: actiondata,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).success(function (response) {
                        if(response.status == "added"){
                            console.log("added");
                            notify("Added " + $scope.quantity + " item(s) to basket");
                        }
                        else if(response.message == "login"){
                            notify("Please login");
                        }
                        else{
                            notify("Not added");
                        }
                    }).error(function(){ notify("Not added"); });
                }
            };
        });
    </script>
<?php endif; ?>