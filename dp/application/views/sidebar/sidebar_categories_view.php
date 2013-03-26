<div class="title_sidebar"><strong>Select a category:</strong></div>

<div class="categories_sidebar">
    <?php $categories = sidebar_categories(1); ?>
    <ul>
        <?php foreach($categories as $category): ?>
        <li>
            <?php echo anchor('category/'.$category->categ_id, $category->categ_name); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div><br />
