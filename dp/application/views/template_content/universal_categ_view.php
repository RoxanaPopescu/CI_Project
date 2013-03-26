<div class="univ_categ">
    <?php foreach($univ_categ as $categ): ?>
    <li>
        <?php echo anchor('universal/'.$categ->categ_id, $categ->categ_name); ?>
    </li>
    <?php endforeach; ?>
</div>
