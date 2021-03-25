<p>
    <label for="delivery_address">Address: </label>
    <span class="error" ng-show="address_id == '0' && form.delivery_address.$dirty && form.delivery_address.$invalid">
    <span ng-show="form.delivery_address.$error.pattern">Alphabets only</span>
    <span ng-show="form.delivery_address.$error.required">Required</span>
    </span>
    <input
        ng-model="delivery_address"
        type="text"
        name="delivery_address"
        id="delivery_address"
        pattern="^[\w\d]+([\s\w]+)?\w+$"
        autocomplete="off"
        ng-required="address_id == '0'"
        ng-disabled="address_id != '0'"><br>
</p>


<p>
    <label for="delivery_postcode">Postcode: </label>
    <span class="error" ng-show="address_id == '0' && form.delivery_postcode.$dirty && form.delivery_postcode.$invalid">
    <span ng-show="form.delivery_postcode.$error.pattern">Must be 5-digits</span>
    <span ng-show="form.delivery_postcode.$error.required">Required</span>
    </span>
    <input
        ng-model="delivery_postcode"
        type="text"
        id="delivery_postcode"
        name="delivery_postcode"
        pattern="^\d{5}$"
        placeholder="5-digit postcode"
        ng-required="address_id == '0'"
        ng-disabled="address_id != '0'"
        autocomplete="off"><br>
</p>


<p>
    <label for="delivery_city">City: </label>
    <span class="error" ng-show="address_id == '0' && form.delivery_city.$dirty && form.delivery_city.$invalid">
    <span ng-show="form.delivery_city.$error.pattern">Alphabets only</span>
    <span ng-show="form.delivery_city.$error.required">Required</span>
    <span ng-show="form.delivery_city.$error.minlength">2 characters min.</span>
    </span>
    <input
        ng-model="delivery_city"
        ng-minlength="2"
        type="text"
        name="delivery_city"
        id="delivery_city"
        pattern="^\w+([\s\w]+)?\w+$"
        ng-required="address_id == '0'"
        ng-disabled="address_id != '0'"
        autocomplete="off"><br>
</p>


<p>
    <label for="delivery_state">State: </label>
    <span class="error" ng-show="address_id == '0' && form.delivery_state.$dirty && form.delivery_state.$invalid">
    <span ng-show="form.delivery_state.$error.pattern">Alphabets only</span>
    <span ng-show="form.delivery_state.$error.required">Required</span>
    <span ng-show="form.delivery_state.$error.minlength">2 characters min.</span>
    </span>
    <input
        ng-model="delivery_state"
        ng-minlength="2"
        type="text"
        name="delivery_state"
        id="delivery_state"
        pattern="^\w+([\s\w]+)?\w+$"
        ng-required="address_id == '0'"
        ng-disabled="address_id != '0'"
        autocomplete="off"><br>
</p>


