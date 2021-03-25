<div ng-app="checkoutApp" ng-controller="checkoutCtrl" class="row">
    <h1 class="text-center">Checkout</h1>
    <div class="row">
        <div class="col-8 col-offset-2">
            <form name="form" class="customer-form" action="/customer/checkout" method="post">
                <?php if(isset($error) && $error): ?>
                    <div class="form-errors">
                        <ul>
                            <li>An error occurred. Ensure your card CVC and expiration date are correct.</li>
                            <?php if(isset($error_msg)){ echo "<li>$error_msg</li>"; }?>
                        </ul>
                    </div>
                <?php endif; ?>
                <h2>Delivery</h2>
                <?php foreach($oldAddresses as $oldAddress): ?>
                    <?php
                    $id = $oldAddress['address_id'];
                    $addr = $oldAddress['address'];
                    $city = $oldAddress['city'];
                    $postcode = $oldAddress['postcode'];
                    $tag = implode(', ', array($addr, $city, $postcode)); ?>
                    <p>
                        <input
                            ng-click="setOldAddress()"
                            ng-model="address_id"
                            type="radio"
                            name="address_id"
                            value="<?php echo $id; ?>"
                            ng-value="<?php echo $id; ?>"
                            required><span><?php echo $tag; ?></span>
                    </p>
                <?php endforeach; ?>
                <input
                    ng-click="setNewOldAddress()"
                    ng-model="address_id"
                    type="radio"
                    value="0"
                    name="address_id"
                    ng-value="0"
                    required><span>New address</span>

                <input
                    ng-model="address_type"
                    ng-value="address_id == 0 ? 'new' : 'old'"
                    type='hidden'
                    name='address_type'>

                <?php require_once "deliveryform.php" ?>


                <h2>Billing</h2>
                <?php if($oldCards): ?>
                    <?php foreach($oldCards as $oldCard):
                        $id = $oldCard['card_id'];
                        $name = $oldCard['card_name'];
                        $number = "ends in ".substr($oldCard['card_number'], 12, 4);
                        //        $expiry = $card['card_expiry'];
                        $tag = implode(', ', array($name, $number));?>
                        <p>
                            <input
                                ng-click="resetCard()"
                                ng-model="card_id"
                                type="radio"
                                name="card_id"
                                value="<?php echo $id; ?>"
                                ng-value="<?php echo $id; ?>"
                                required><span><?php echo $tag; ?></span>
                        </p>
                    <?php endforeach; ?>
                    <p ng-show="card_id != '0'">
                        <input
                            ng-model="card_cvc_previous"
                            type="text"
                            id="card_cvc_previous"
                            name="card_cvc_previous"
                            placeholder="3-digit CVC code"
                            pattern="^\d\d\d$"
                            ng-required="card_id != '0'"
                            autocomplete="off">
                            <span class="error" ng-show="form.card_cvc_previous.$dirty && form.card_cvc_previous.$invalid">
                            <span ng-show="form.card_cvc_previous.$error.pattern">Must be 3 digits</span>
                            <span ng-show="form.card_cvc_previous.$error.required">Required</span>
                            </span>
                    </p>
                    <?php endif; ?>
                    <input
                        ng-model="card_id"
                        type="radio"
                        value="0"
                        name="card_id"
                        ng-value="0"
                        required><span>New card</span>

                    <input
                        ng-model="card_type"
                        ng-value="card_id == 0 ? 'new' : 'old'"
                        type='hidden'
                        name='card_type'>

                    <?php require_once "billingform.php" ?>

                    <p>
                        <button
                            type="submit"
                            id="submit_btn"
                            class="shop-btn"
                            ng-disabled="!checkForm()">Place Order
                        </button>
                    </p>
            </form>
        </div>
    </div>
    <script>
        var checkoutApp = angular.module("checkoutApp", []);
        checkoutApp.controller("checkoutCtrl", function($scope){

            $scope.delivery_address = "<?php echo set_value('delivery_address', '', TRUE); ?>";
            $scope.delivery_city = "<?php echo set_value('delivery_city', '', TRUE); ?>";
            $scope.delivery_postcode = "<?php echo set_value('delivery_postcode', '', TRUE); ?>";
            $scope.delivery_state = "<?php echo set_value('delivery_state', '', TRUE); ?>";
            $scope.card_name = "<?php echo set_value('card_name', '', TRUE); ?>";
            $scope.billing_address = "<?php echo set_value('billing_address', '', TRUE); ?>";
            $scope.billing_city = "<?php echo set_value('billing_city', '', TRUE); ?>";
            $scope.billing_state = "<?php echo set_value('billing_state', '', TRUE); ?>";
            $scope.billing_postcode = "<?php echo set_value('billing_postcode', '', TRUE); ?>";
            $scope.checkForm = function(){
                return document.form.checkValidity();
            };
        });
    </script>
</div>