<div ng-app="formApp" ng-controller="formCtrl" novalidate class="row layout-center">
    <div class="row">
        <div class="col-4 col-offset-4">
            <a href="/customer/changepassword"><button class="action-btn">Change Your Password</button></a>
        </div>
    </div>
    <div class="row layout-center">

        <div class="col-8 col-offset-2">
            <h2>Your Details</h2>
            <form class="customer-form" name="form" ng-submit="update()">
                <?php if(isset($error) && $error): ?>
                    <div class="form-errors">
                        <p>Details not changed. Some fields may have been invalid.</p>
                    </div>
                <?php endif; ?>
                <p>
                    <label for="firstName">First Name:</label>
                <span class="error" ng-show="form.firstName.$dirty && form.firstName.$invalid">
                <span ng-show="form.firstName.$error.required">First name is required</span>
                <span ng-show="form.firstName.$error.pattern">Alphabets only</span>
                </span>
                    <input
                        ng-model="customer.firstName"
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
                        ng-model="customer.lastName"
                        type="text"
                        name="lastName"
                        id="lastName"
                        pattern="^\w{2,}$"
                        required
                        autocomplete="off">
                </p>

                <p>
                    <label for="security_question">Security Question: </label>
                <span class="error" ng-show="form.security_question.$dirty && form.security_question.$invalid">
                <span ng-show="form.security_question.$error.required">Security question is required</span>
                <span ng-show="form.security_question.$error.pattern">Example: Where were you born</span>
                </span>
                    <input
                        ng-model="customer.security_question"
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
                <span ng-show="form.answer.$error.pattern">One alphanumeric phrase/word only</span>
                </span>
                    <input
                        ng-model="customer.answer"
                        type="text"
                        name="answer"
                        id="answer"
                        pattern="^[\w\d]+$"
                        required
                        autocomplete="off"
                        ng-change="answerConfirmChange()">
                </p>

                <p>
                    <label for="answer_confirm">Confirm Answer Phrase: </label>
                <span class="error" ng-show="(form.answer_confirm.$dirty && form.answer_confirm.$invalid) || !answerMatch">
                <span ng-show="form.answer_confirm.$error.required && answerMatch">Answer confirmation is required</span>
                <span ng-show="!answerMatch"> Doesn't match answer</span>
                </span>
                    <input
                        ng-model="customer.answer_confirm"
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
                        ng-model="customer.address"
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
                        ng-model="customer.postcode"
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
                        type="text"
                        ng-model="customer.city"
                        name="city"
                        id="city"
                        pattern="^\w+([\s\w]+)?\w+$"
                        required
                        autocomplete="off"><br>
                </p>

                <p>
                    <label for="state">State: </label>
            <span class="error" ng-show="form.state.$dirty && form.state.$invalid">
            <span ng-show="form.state.$error.required">State is required</span>
            <span ng-show="form.state.$error.pattern">Example: California</span>
            </span>
                    <input
                        type="text"
                        ng-model="customer.state"
                        name="state"
                        id="state"
                        pattern="^\w+([\s\w]+)?\w+$"
                        required
                        autocomplete="off"><br>
                </p>

                <p>
                    <button
                        type="submit"
                        id="submit_btn"
                        class="shop-btn"
                        value="Sign-Up"
                        ng-disabled="form.$invalid || !answerMatch || form.$pristine">Update</button>
                </p>
            </form>
        </div>


        <script>
            var formApp = angular.module("formApp", []);
            formApp.controller("formCtrl", function($scope, $http){
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

                //FETCH
                $scope.master = {};
                var fetchdata = $.param({action: "fetch"});
                $http({
                    method: 'POST',
                    url: "/ajax_query/customerdetails",
                    data: fetchdata,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .success(function (response) {
                        $scope.customer = response.rows;
                        $scope.customer.answer_confirm = $scope.customer.answer;
                        angular.copy($scope.customer, $scope.master);
                    })
                    .error(function(){
                        console.log("Error");
                    });

                //DETAILS CHANGE
                $scope.update = function(){
                    if(!angular.equals($scope.master, $scope.customer) && $scope.form.$valid){
                        var updatedata = angular.copy($scope.customer);
                        updatedata = $.param(updatedata);

                        $http({
                            method: 'POST',
                            url: "/customer/changedetails",
                            data: updatedata,
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        }).success(function (response) {
                            if(response.status == "good"){
                                angular.copy($scope.customer, $scope.master);
                                notify("Successfully updated")
                            } else{ notify("Details not changed"); }
                        }).error(function(){ notify("Could not update"); });
                    }
                    else{ notify("No changes were made"); }
                };

                //VALIDATION
                $scope.answerMatch = true;
                $scope.answerConfirmChange = function(){
                    $scope.answerMatch = ($scope.customer.answer === $scope.customer.answer_confirm);
                };
            });
        </script>
    </div>
</div>
