
<div class="brands_sidebar">
    <?php
        $brands = sidebar_brands();
        if($brands != NULL):
    ?>
    <ul>
    <?php foreach($brands as $brand):?>
        <li id="brand_<?php echo $brand->cbrand_id; ?>">
            <?php echo anchor('brand/'.$brand->cbrand_id, $brand->brand_name); ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php else: ?>
       <p>We are sorry! No brands to display yet.</p>
    <?php endif; ?>
</div><br /><br />
