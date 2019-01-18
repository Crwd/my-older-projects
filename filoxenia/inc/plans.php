<?php include "inc/current_site.php"; ?>
<?php 
// Laden aller Produkkategorien
$product_cats = $stmt->query('SELECT * FROM cms_product_cats');
$product_cat = array();
while($row = $product_cats->fetch_assoc()) {
	$product_cat[] = $row;
}

foreach($product_cat as $cat) {
	$count_items = $stmt->query('SELECT * FROM cms_products WHERE cat_id="' . $cat['ID'] . '"')->num_rows;
	if($count_items) {
?>
<section id="<?php echo $cat['name'];?>" class="container">
    <div class="row title">
        <div class="small-12 column">
            <h6><?php echo $cat['name'];?></h6>
        </div>
    </div>


    <div class="row">
        <div class="large-12 column">

            <p>
                <?php echo $cat['description'];?>
            </p>
            	<?php
            	$product_items = $stmt->query('SELECT * FROM cms_products WHERE cat_id="' . $cat['ID'] . '"');
				$product_item = array();
				while($row = $product_items->fetch_assoc()) {
					$product_item[] = $row;
				}
				
				foreach($product_item as $item) {
					if($item['highlight']) {
						$highlight = "highlight";
					} else {
						$highlight = "";
					}
				?>
                <div class="medium-4 column">
                    <ul class="pricing-table <?php echo $highlight;?>">
                        <li class="title"><?php echo $item['name'];?></li>
                        <li class="price"><?php echo $item['price'];?>€ <span>/ month</span>
                        </li>
                        <?php 
							$item_desc = explode("\n",$item['description']);
							foreach($item_desc as $desc) { 
						?>
                        <li class="bullet-item"><?php echo $desc; ?></li>
                        
                       <?php
							}
                        if($secure_login->is_loggedin()) {
                            echo '<li class="cta-button"><a class="button" href="?site=order&type=' . $item['cat_id'] . '&item=' . $item['ID'] . '">Buy</a>';
                        } else {
                            echo '<li class="cta-button"><a class="button" href="?site=signup">Get Started</a>';
                        }
                        ?>
                        </li>
                    </ul>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>
</section>



<?php }} ?>