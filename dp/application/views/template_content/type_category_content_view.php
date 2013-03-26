<div class="machine_parts_categ">
    <ul>
        <?php foreach($categories as $category): ?>
        <li>
            <?php echo anchor('car_type/'.$type_id.'/category/'.$category->categ_id, $category->categ_name); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
