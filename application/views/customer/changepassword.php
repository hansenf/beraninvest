<div ng-app="formApp" ng-controller="formCtrl" novalidate class="row layout-center">

    <div class="row">
        <div class="col-4 col-offset-2">
            <a href="/customer/changedetails"><button class="action-btn">Change Your Details</button></a>
        </div>
        <div class="col-4">
            <a href="/customer/changepassword"><button class="action-btn">Change Your Password</button></a>
        </div>
    </div>

    <div class="row">
        <div class="col-8 col-offset-2">
            <h2>Reset Your Password</h2>
            <form method="post" action="/customer/changepassword" class="customer-form" name="form">

            <?php if(isset($error) && $error): ?>
                <div class="form-errors">
                    <p>Password was not changed. Your details may be incorrect.</p>
                </div>
            <?php endif; ?>

            <?php if(isset($changed) && $changed): ?>
                <div class="form-info">
                    <p>Your password has been changed!</p>
                </div>
            <?php endif; ?>

            <?php if(isset($nochange) && $nochange): ?>
                <div class="form-info">
                    <p>No changes were made</p>
                </div>
            <?php endif; ?>
                <p>
                    <label for="email">Email: </label>
                        <span class="error" ng-show="form.email.$dirty && form.email.$invalid">
                        <span ng-show="form.email.$error.required">Email is required</span>
                        <span ng-show="form.email.$error.pattern">Example: you@example.com</span>
                        </span>
                    <input
                        ng-model="email"
                        type="email"
                        name="email"
                        id="email"
                        required
                        autocomplete="off"
                        pattern="^[\w]+(\.[\w]+)*@[\w\d]+(\.[\w\d]+)*(\.[\w]{2,3})$">
                </p>

                <p>
                    <label for="password">New Password: </label>
                        <span class="error" ng-show="form.password.$dirty && form.password.$invalid">
                        <span ng-show="form.password.$error.required">Password is required</span>
                        <span ng-show="form.password.$error.pattern">a-zA-Z0-9, 5 characters minimum</span>
                        </span>
                    <input
                        ng-model="password"
                        type="password"
                        name="password"
                        id="password"
                        pattern="^[\w\d]{5,}$"
                        required
                        ng-change="passwordConfirmChange()">
                </p>

                <p>
                    <label for="confirm">Confirmation Password: </label>
                        <span class="error" ng-show="(form.confirm.$dirty && form.confirm.$invalid) || !passwordMatch">
                        <span ng-show="form.confirm.$error.required && passwordMatch">Password confirmation is required.</span>
                        <span ng-show="!passwordMatch">Must match password</span>
                        </span>
                    <input
                        ng-model="confirm"
                        type="password"
                        name="confirm"
                        id="confirm"
                        pattern="^[\w\d]{5,}$"
                        required
                        ng-change="passwordConfirmChange()">
                </p>

                <p>
                    <span class="bold">Security Question:</span> <?php echo $security_question; ?>
                </p>

                <p>
                    <label for="answer">Answer phrase:</label>
                        <span class="error" ng-show="form.answer.$dirty && form.answer.$invalid">
                        <span ng-show="form.answer.$error.required">Answer is required</span>
                        <span ng-show="form.answer.$error.pattern">One alphanumeric phrase/word only</span>
                        </span>
                    <input
                        ng-model="answer"
                        type="text"
                        name="answer"
                        id="answer"
                        pattern="^[\w\d]+$"
                        required
                        autocomplete="off">
                </p>
                <p>
                    <button
                        type="submit"
                        id="submit_btn"
                        class="shop-btn"
                        value="Sign-Up"
                        ng-disabled="form.$invalid || !passwordMatch">Change</button>
                </p>

                <script>
                    var formApp = angular.module("formApp", []);
                    formApp.controller("formCtrl", function($scope){
                        $scope.email = "<?php echo set_value('email'); ?>";
                        $scope.security_question = "<?php echo $security_question; ?>";

                        $scope.passwordConfirmChange = function(){
                            $scope.passwordMatch = ($scope.password === $scope.confirm);
                        };
                    });
                </script>
            </form>
        </div>
    </div>
</div>

