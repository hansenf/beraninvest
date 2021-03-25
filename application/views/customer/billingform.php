<p>
    <label for="card_name">Name (as appears on card): </label>
    <span class="error" ng-show="card_id == '0' && form.card_name.$dirty && form.card_name.$invalid">
    <span ng-show="form.card_name.$error.pattern">Alphabets only</span>
    <span ng-show="form.card_name.$error.minlength">2 characters min.</span>
    <span ng-show="form.card_name.$error.required">Required</span>
    </span>
    <input
        ng-model="card_name"
        ng-minlength="2"
        type="text"
        name="card_name"
        id="card_name"
        pattern="^([a-zA-Z]+(?:\.)?(?:(?:'| )[a-zA-Z]+(?:\.)?)*)$"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>


<p>
    <label for="card_number">Card Number: </label>
    <span class="error" ng-show="card_id == '0' && form.card_number.$dirty && form.card_number.$invalid">
    <span ng-show="form.card_number.$error.pattern">Must be 16 digits</span>
    <span ng-show="form.card_number.$error.required">Required</span>
    </span>
    <input
        ng-model="card_number"
        type="text"
        name="card_number"
        id="card_number"
        pattern="^\d{16}$"
        placeholder="16-digit number"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>


<p>
    <label>Card Expiration:</label>
    <span class="error" ng-show="card_id == '0' && form.card_expiry_month.$dirty && form.card_expiry_month.$invalid">
    <span ng-show="form.card_expiry_month.$error.required || form.card_expiry_year.$error.required">Required</span>
    </span>
    <?php
    $year = date('y');
    $month = date('m');
    ?>
    <br>
    <select
        ng-model="card_expiry_month"
        class="expiry-date"
        name="card_expiry_month"
        id="card_expiry_month"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'">
        <option value="">MM</option>
        <?php for($i = 1 ; $i <= 12 ; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
    </select><span>/</span>
    <select
        ng-model="card_expiry_year"
        class="expiry-date"
        name="card_expiry_year"
        id="card_expiry_year"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'">
        <option value="">YY</option>
        <?php for($i = $year ; $i <= $year + 5 ; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>
</p>


<p>
    <label for="card_cvc">CVC:</label>
    <span class="error" ng-show="card_id == '0' && form.card_cvc.$dirty && form.card_cvc.$invalid">
    <span ng-show="form.card_cvc.$error.pattern">Must be 3 digits</span>
    <span ng-show="form.card_cvc.$error.required">Required</span>
    </span>
    <input
        ng-model="card_cvc"
        type="text"
        id="card_cvc"
        name="card_cvc"
        pattern="^\d\d\d$"
        placeholder="3-digit CVC code"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>


<p>
    <label for="billing_address">Billing Address: </label>
    <span class="error" ng-show="card_id == '0' && form.billing_address.$dirty && form.billing_address.$invalid">
    <span ng-show="form.billing_address.$error.pattern">Alphabets only</span>
    <span ng-show="form.billing_address.$error.required">Required</span>
    <span ng-show="form.billing_address.$error.minlength">2 characters min.</span>
    </span>
    <input
        ng-model="billing_address"
        ng-minlength="2"
        type="text"
        name="billing_address"
        id="billing_address"
        pattern="^[\w\d]+([\s\w]+)?\w+$"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>


<p>
    <label for="billing_postcode">Billing Postcode: </label>
    <span class="error" ng-show="card_id == '0' && form.billing_postcode.$dirty && form.billing_postcode.$invalid">
    <span ng-show="form.billing_postcode.$error.pattern">Must be 5-digits</span>
    <span ng-show="form.billing_postcode.$error.required">Required</span>
    </span>
    <input
        ng-model="billing_postcode"
        type="text"
        id="billing_postcode"
        name="billing_postcode"
        pattern="^\d{5}$"
        placeholder="5-digit postcode"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>


<p>
    <label for="billing_city">Billing City: </label>
    <span class="error" ng-show="card_id == '0' && form.billing_city.$dirty && form.billing_city.$invalid">
    <span ng-show="form.billing_city.$error.pattern">Alphabets only</span>
    <span ng-show="form.billing_city.$error.required">Required</span>
    <span ng-show="form.billing_city.$error.minlength">2 characters min.</span>
    </span>
    <input type="text"
           ng-model="billing_city"
           ng-minlength="2"
           name="billing_city"
           id="billing_city"
           pattern="^\w+([\s\w]+)?\w+$"
           ng-required="card_id == '0'"
           ng-disabled="card_id != '0'"
           autocomplete="off">
</p>


<p>
    <label for="billing_state">Billing State: </label>
    <span class="error" ng-show="card_id == '0' && form.billing_state.$dirty && form.billing_state.$invalid">
    <span ng-show="form.billing_state.$error.pattern">Alphabets only</span>
    <span ng-show="form.billing_state.$error.required">Required</span>
    <span ng-show="form.billing_state.$error.minlength">2 characters min.</span>
    </span>
    <input
        type="text"
        ng-model="billing_state"
        ng-minlength="2"
        name="billing_state"
        id="billing_state"
        pattern="^\w+([\s\w]+)?\w+$"
        ng-required="card_id == '0'"
        ng-disabled="card_id != '0'"
        autocomplete="off">
</p>