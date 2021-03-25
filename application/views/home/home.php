<?php if($sales): ?>
<div class="row">
    <div class="col-12">
        <marquee class="sale-band" scrollamount="10">SALE!!! SALE!!! SALE!!!</marquee>
    </div>
</div>
<div class="sales-container row text-center">
<?php $i = 0; foreach($sales as $saleItem):?>
    <?php if($i == 0 && count($sales)%3 == 1): ?>
    <div class="sale-item col-4 col-offset-4">
    <?php elseif($i == 0 && count($sales)%3 == 2): ?>
    <div class="sale-item col-4 col-offset-2">
    <?php else: ?>
    <div class="sale-item col-4">

    <?php endif; ?>
        <p class="product-price">$<?php echo $saleItem['saleprice']; ?></p>
        <p class="product-price product-price-sale"><?php echo $saleItem['discount']; ?>% OFF</p>
        <p class="sale-title"><?php echo $saleItem['product_anchor']; ?></p>
        <div class="row text-center">
            <div class="col-12">
                <img class="img-responsive" src="<?php echo $saleItem['product_image']; ?>" alt="image"/>
            </div>
        </div>
    </div>
<?php $i++; endforeach; ?>
</div>
<hr>
<?php endif; ?>


<div class="main-categories-container row">
    <?php if($women): ?>
    <div class='row text-center'>
        <h2>Women</h2>
    <?php
    $i=0;
    $length = count($women);
    $rows = ceil($length / 3);
    for($b = 0; $b < $rows; $b++):
        if($b == $rows-1){ $left = $length - $b*3;}
        else { $left = 3;}
        for($a = 0; $a < $length && $a < $left; $a++):
            $index = $b*3 + $a;
            $category = $women[$index];
            ?>
            <?php if($a == 0 && $left == 1): ?>
            <div class="main-category col-4 col-offset-4">
            <?php elseif($a == 0 && $left == 2): ?>
            <div class="main-category col-4 col-offset-2">
            <?php else: ?>
            <div class="main-category col-4">
            <?php endif; ?>
                <p><?php echo $category['category_anchor']; ?></p>
                <img class="img-responsive" src="<?php echo $category['category_image']; ?>" alt="image"/>
            </div>
            <?php endfor; ?>
    <?php endfor; ?>
    </div>
    <hr>
    <?php endif; ?>

    <?php if($men): ?>
    <div class='row text-center'>
        <h2>Men</h2>
    <?php
    $i=0;
    $length = count($men);
    $rows = ceil($length / 3);
    for($b = 0; $b < $rows; $b++):
        if($b == $rows-1){ $left = $length - $b*3;}
        else { $left = 3;}
        for($a = 0; $a < $length && $a < $left; $a++):
            $index = $b*3 + $a;
            $category = $men[$index];
            ?>
            <?php if($a == 0 && $left == 1): ?>
            <div class="main-category col-4 col-offset-4">
            <?php elseif($a == 0 && $left == 2): ?>
            <div class="main-category col-4 col-offset-2">
            <?php else: ?>
            <div class="main-category col-4">
            <?php endif; ?>
                <p><?php echo $category['category_anchor']; ?></p>
                <img class="img-responsive" src="<?php echo $category['category_image']; ?>" alt="image"/>
            </div>
        <?php endfor; ?>
    <?php endfor; ?>
    </div>
    <hr>
    <?php endif; ?>
</div>
