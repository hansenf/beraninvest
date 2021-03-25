<div ng-app="formApp" ng-controller="formCtrl" class="row layout-center">
    <div class="col-8 col-offset-2">
        <h2>Login</h2>
        <?php echo form_open('customer/login', array("class"=>"customer-form", "name"=>"form")); ?>

        <?php if($error): ?>
            <div class="form-errors text-center bold">
                <p>Login failed!</p>
            </div>
        <?php endif; ?>
        <p>
            <label for="email">Email: </label>
                <span class="error" ng-show="form.email.$dirty && form.email.$invalid">
                <span ng-show="form.email.$error.required">Email is required</span>
                <span ng-show="form.email.$error.pattern">Please enter a valid email</span>
                </span>
            <input
                ng-model="email"
                type="text"
                name="email"
                autocomplete="off"
                pattern="^[\w]+(\.[\w]+)*@[\w\d]+(\.[\w\d]+)*(\.[\w]{2,3})$"
                required>
        </p>

        <p>
            <label for="password">Password: </label>
                <span class="error" ng-show="form.password.$dirty && form.password.$invalid">
                <span ng-show="form.password.$error.required">Password is required</span>
                <span ng-show="form.password.$error.pattern">Alphanumeric only (5+ characters)</span>
                </span>
            <input
                ng-model="password"
                type="password"
                name="password"
                pattern="^[\w\d]{5,}$"
                required>
        </p>

        <p>
            <button
                type="submit"
                id="submit_btn"
                class="shop-btn"
                value="Sign-Up"
                ng-disabled="form.$invalid">Login
            </button>
        </p>

        <br>
        <p class="bold text-center">OR<br><br><a href="/customer/signup">Signup</a></p>


        <script>
            var formApp = angular.module("formApp", []);
            formApp.controller("formCtrl", function($scope){
                $scope.email = "<?php echo set_value('email', '', true); ?>";
            });
        </script>

        </form>


    </div>
</div>
