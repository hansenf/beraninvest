<div ng-app="formApp" ng-controller="formCtrl" novalidate class="row">
    <div class="col-8 col-offset-2">
        <h2>Sign-Up</h2>
        <?php echo form_open('customer/signup', array("class"=>"customer-form", "name"=>"form")); ?>

        <?php if(isset($error) && $error): ?>
            <div class="form-errors">
                <p>Could not register</p>
            </div>
        <?php endif; ?>

        <?php if(isset($emailduplicate)): ?>
            <div class="form-info">
                <p>This email is already in use</p>
            </div>
        <?php endif; ?>

        <p>
            <label for="firstName">First Name:</label>
            <span class="error" ng-show="form.firstName.$dirty && form.firstName.$invalid">
            <span ng-show="form.firstName.$error.required">First name is required</span>
            <span ng-show="form.firstName.$error.pattern">Alphabets only</span>
            </span>
            <input
                ng-model="firstName"
                type="text"
                name="firstName"
                id="firstName"
                pattern="^\w{2,}$"
                required
                autocomplete="off">
        </p>
        <p>
            <label for="lastName">Last Name: </label>
            <span class="error" ng-show="form.lastName.$dirty && form.lastName.$invalid">
            <span ng-show="form.lastName.$error.required">Last name is required</span>
            <span ng-show="form.lastName.$error.pattern">Alphabets only</span>
            </span>
            <input
                ng-model="lastName"
                type="text"
                name="lastName"
                id="lastName"
                pattern="^\w{2,}$"
                required
                autocomplete="off">
        </p>
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
            <label for="password">Password: </label>
            <span class="error" ng-show="form.password.$dirty && form.password.$invalid">
            <span ng-show="form.password.$error.required">Password is required</span>
            <span ng-show="form.password.$error.pattern">a-zA-z0-9, 5 characters minimum</span>
            </span>
            <input
                ng-model="password"
                type="password"
                name="password"
                id="password"
                pattern="^[\w\d]{5,}$"
                required>
        </p>

        <p>
            <label for="confirm">Confirmation Password: </label>
            <span class="error" ng-show="(form.confirm.$dirty && form.confirm.$invalid) || !passwordMatch">
            <span ng-show="form.confirm.$error.required && passwordMatch">Password confirmation is required.</span>
            <span ng-show="!passwordMatch"> Must match password</span>
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
            <label for="security_question">Security Question: </label>
            <span class="error" ng-show="form.security_question.$dirty && form.security_question.$invalid">
            <span ng-show="form.security_question.$error.required">Security question is required</span>
            <span ng-show="form.security_question.$error.pattern">Alphanumeric characters only</span>
            </span>
            <input
                ng-model="security_question"
                type="text"
                name="security_question"
                id="security_question"
                pattern="^\w+([\s\w-]+)?\w+$"
                autocomplete="off"
                required>
        </p>

        <p>
            <label for="answer">Answer phrase:</label>
            <span class="error" ng-show="form.answer.$dirty && form.answer.$invalid">
            <span ng-show="form.answer.$error.required">Answer is required</span>
            <span ng-show="form.answer.$error.pattern">Single word, alphanumeric</span>
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
            <label for="answer_confirm">Confirm Answer: </label>
            <span class="error" ng-show="(form.answer_confirm.$dirty && form.answer_confirm.$invalid) || !answerMatch">
            <span ng-show="form.answer_confirm.$error.required && answerMatch">Answer confirmation is required</span>
            <span ng-show="!answerMatch"> Doesn't match answer</span>
            </span>
            <input
                ng-model="answer_confirm"
                type="text"
                name="answer_confirm"
                id="answer_confirm"
                pattern="^[\w\d]+$"
                required
                ng-change="answerConfirmChange()">
        </p>

        <p>
            <label for="address">Address: </label>
            <span class="error" ng-show="form.address.$dirty && form.address.$invalid">
            <span ng-show="form.address.$error.required">Address is required</span>
            <span ng-show="form.address.$error.pattern">Example: 123 Onetwothree Road</span>
            </span>
            <input
                ng-model="address"
                type="text"
                name="address"
                id="address"
                pattern="^[\w\d]+([\s\w]+)?\w+$"
                required
                autocomplete="off">
        </p>

        <p>
            <label for="postcode">Postcode: </label>
            <span class="error" ng-show="form.postcode.$dirty && form.postcode.$invalid">
            <span ng-show="form.postcode.$error.required">Postcode is required</span>
            <span ng-show="form.postcode.$error.pattern">Must be 5 digits</span>
            </span>
            <input
                ng-model="postcode"
                type="text"
                id="postcode"
                name="postcode"
                pattern="^\d{5}$"
                required
                autocomplete="off"><br>
        </p>

        <p>
            <label for="city">City: </label>
            <span class="error" ng-show="form.city.$dirty && form.city.$invalid">
            <span ng-show="form.city.$error.required">City is required</span>
            <span ng-show="form.city.$error.pattern">Example: San Diego</span>
            </span>
            <input
                ng-model="city"
                name="city"
                id="city"
                type="text"
                pattern="^\w+([\s\w]+)?\w+$"
                required
                autocomplete="off">
        </p>

        <p>
            <label for="state">State: </label>
            <span class="error" ng-show="form.state.$dirty && form.state.$invalid">
            <span ng-show="form.state.$error.required">State is required</span>
            <span ng-show="form.state.$error.pattern">Example: California</span>
            </span>
            <input
                type="text"
                ng-model="state"
                name="state"
                id="state"
                pattern="^\w+([\s\w]+)?\w+$"
                required
                autocomplete="off">
        </p>

        <p>
            <button
                type="submit"
                id="submit_btn"
                class="shop-btn"
                value="Sign-Up"
                ng-disabled="form.$invalid || !passwordMatch || !answerMatch">Register</button>
        </p>

        <script>
            var formApp = angular.module("formApp", []);
            formApp.controller("formCtrl", function($scope){
                $scope.passwordMatch = false;
                $scope.answerMatch = false;
                $scope.firstName = "<?php echo set_value('firstName', '', TRUE); ?>";
                $scope.lastName = "<?php echo set_value('lastName', '', TRUE); ?>";
                $scope.email = "<?php echo set_value('email', '', TRUE); ?>";
                $scope.security_question = "<?php echo set_value('security_question', '', TRUE); ?>";
                $scope.answer = "<?php echo set_value('answer', '', TRUE); ?>";
                $scope.answer_confirm = "<?php echo set_value('answer_confirm', '', TRUE); ?>";
                $scope.address = "<?php echo set_value('address', '', TRUE); ?>";
                $scope.city = "<?php echo set_value('city', '', TRUE); ?>";
                $scope.postcode = "<?php echo set_value('postcode', '', TRUE); ?>";
                $scope.state = "<?php echo set_value('state', '', TRUE); ?>";
                <?php if($_POST): ?>
                $scope.answerMatch = $scope.answer === $scope.answer_confirm;
                <?php endif; ?>

                $scope.passwordConfirmChange = function(){
                    $scope.passwordMatch = ($scope.password === $scope.confirm);
                };
                $scope.answerConfirmChange = function(){
                    $scope.answerMatch = ($scope.answer === $scope.answer_confirm);
                };
            });
        </script>

        </form>
    </div>
</div>