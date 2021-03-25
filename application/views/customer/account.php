<?php
function displayOrderItems($items){
    $length = count($items);
    $modulus = $length%3;
    $limit = intval($length/3);

    foreach($items as $item){
    echo "<div class='row'>";
        echo '<div class="order-product col-3">';
        echo "<p><span class='bold'>Item:</span>{$item['product_name']}</p>";
        echo "<p><span class='bold'>Quantity:</span>{$item['quantity']}</p>";
        echo "<p><span class='bold'>Total:</span>$".sprintf("%0.2f", $item['price']*$item['quantity'])."</p>";
        echo "</div>";
    }
    echo "</div>";
}
?>
<div ng-app="accountApp" ng-controller="accountCtrl">
    <div class="row">
        <div class="col-4">
            <button class="action-btn" ng-click="showDiv('orders')">View Your Orders</button>
        </div>
        <div class="col-4">
            <a href="/customer/changedetails"><button class="action-btn">Change Your Details</button></a>
        </div>
        <div class="col-4">
            <a href="/customer/changepassword"><button class="action-btn">Change Your Password</button></a>
        </div>
    </div>

    <div class="account-section hidden text-center" id="orders">
        <h2>You Order History</h2>
        <hr>
        <?php if(!$orders):?>
        <div class="form-info">
            <p>You have no order history. Spend MONEEEYYYYY!</p>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order Time</th>
                    <th>Order Total</th>
                    <th>Payment Card</th>
                    <th>Delivery Address</th>
                    <th>Details</th>
                </tr>
            </thead>
            <?php foreach($orders as $order):
                $id = "order_{$order['order_id']}";
                ?>
                    <tr>
                        <td><?php echo $order['order_time']; ?></td>
                        <td> $<?php echo sprintf("%0.2f", $order['order_total']); ?></td>
                        <td>***<?php echo substr($order['card_number'], 12, 4); ?></td>
                        <td><?php echo "{$order['address']}, {$order['city']}, {$order['postcode']}"; ?></td>
                        <td><button onclick="showItems(this, '<?php echo $id; ?>')" class="shop-btn">Show Items</button></td>
                    </tr>
                    <tr class="hidden" id="<?php echo $id; ?>">
                        <td colspan="5"><?php displayOrderItems($order['items']); ?></td>
                    </tr>
            <?php endforeach;?>
        </table>
            <script>
                function showItems(btn, id){
                    if(btn.innerHTML == "Show Items"){
                        btn.innerHTML = "Hide Items";
                    }
                    else{
                        btn.innerHTML = "Show Items";
                    }
                    $('tr#'+id).slideToggle();
                }
            </script>
        <?php endif; ?>
    </div>

    <script>
        function scrollToOrders(){
            scrollTo(0, $('#orders').position().top);
        }
    </script>
    <script>
        var accountApp = angular.module("accountApp", []);
        accountApp.controller("accountCtrl", function($scope){
            $scope.showDiv = function(divId){
                var $div = $("div#"+divId);
                if($div.css("display") == "none"){
                    $("div.account-section").hide();
                    $div.slideDown().queue(function(){
                        scrollToOrders();
                    });
                }
            };
        });
    </script>

</div>
