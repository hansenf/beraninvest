<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Pages</title>
    <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
    <script src= "/js/angular.min.js"></script>
    <script type="text/javascript" src="/js/loadSearchCategories.js"></script>
    <link rel="stylesheet" href="/css/global-style.css">
<!--    <link rel="stylesheet" href="/css/main.css">-->
    <link rel="stylesheet" href="/css/main-responsive.css">
</head>
<body>
<div id="nav" class="fill layout-center">
    <div class="row layout-center">
        <div class="col-10">
            <h2><a href="/index.php">My Company</a></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-7">
            <form name="search_form" action="/products/search" method="get" class="text-center">
                <div class="row">
                    <div class="col-6">
                        <select name="category_id" id="categories"></select>
                        <input type="checkbox" id="sale" name="sale"><span style="font-size: 0.8em" class="inline">Sale</span>
                    </div>
                    <div class="col-4">
                        <input type="text" id="query" name="query" placeholder="Search for a product...">
                    </div>
                    <div class="col-2">
                        <input type="submit" value="Search">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-5">
            <div class="row">
                <?php if(isset($_SESSION['user']['customer_id']) && $_SERVER['REQUEST_URI'] != "/customer/logout"): ?>
                    <div class="list-item col-3"><span><a href='/customer/basket'><img id='basket-img' src='/images/basket.ico'></a></span></div>
                    <div class="list-item col-3"><span><a href='/customer/account'>Hi <?php echo $_SESSION['user']['firstName']; ?>!</a></span></div>
                    <div class="list-item col-3"><span><a href='/customer/account'>Account</a></span></div>
                    <div class="list-item col-3"><span><a href='/customer/logout'>Logout</a></span></div>

                <?php else: ?>
                    <div class="list-item col-6"><a href='/customer/signup'>Sign-up</a></div>
                    <div class="list-item col-6"><a href='/customer/login'>Login</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="notification">
    <p id="notification-text"></p>
</div>
<script type="text/javascript" src="/js/notifications.js"></script>
<div class="row">

    <div class="col-2" id="sidebar">
        <div class="row"><div class="italics col-12 text-center">Quick searches:</div></div>
        <div class="row"><div class="col-12 list-item"><span><a href="/products/search?sale=true">Sales</a></span></div></div>
        <div class="row"><div class="col-12 list-item"><span><a href="/products/search?query=men">Men</a></span></div></div>
        <div class="row"><div class="col-12 list-item"><span><a href="/products/search?query=women+ladies+heels+crop+blouse">Women</a></span></div></div>
        <div class="row"><div class="col-12 list-item"><span><a href="/products/search?query=shoes">Shoes</a></span></div></div>
        <div class="row"><div class="col-12 list-item"><span><a href="/products/search?query=bag">Bags</a></span></div></div>
    </div>

    <div class="col-10 col-offset-2">
        <div id="main-content" class="row">
        <hr>

