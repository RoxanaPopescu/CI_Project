<div class="breadc_links">
    <?php foreach($breadcrumbs as $i => $breadc): ?>

       <?php if($i % 2 == 0): ?>

           <?php if(count($breadcrumbs) % 2 != 0): ?>
                 <?php if($i == count($breadcrumbs)-1): ?>
                    <?php echo $breadc; ?>
                 <?php else: ?>
                    <?php echo anchor($breadcrumbs[$i+1], $breadc); ?>
                    <?php echo '>>';?>
                 <?php endif; ?>

           <?php else: ?>

                 <?php if($i == count($breadcrumbs)-2): ?>
                    <?php echo $breadc; ?>
                 <?php else: ?>
                    <?php echo anchor($breadcrumbs[$i+1], $breadc); ?>
                    <?php echo '>>';?>
                 <?php endif; ?>

            <?php endif; ?>

       <?php endif; ?>

    <?php endforeach; ?>
</div>
