<div class="machine_parts_subcateg">
    <ul>
        <?php foreach($categ_products as $categ_product): ?>
        <li>
            <?php echo anchor('car_type/'.$type_id.'/products/'.$categ_product->pn_id, $categ_product->pn_name); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>