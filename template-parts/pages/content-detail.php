<div class="page-detail">
    <div class="container">
    	<?php
	    	$title = "";
	    	if($post)
	    	{
		    	$title = $post -> post_title;
                $image = get_the_post_thumbnail($post, 'post-full');
		    }
    	?>
    	<h1 class="title detail-title"><?php echo $title;?></h1>
        <?php if($image) {?>
            <p class="featured-img">
                <?php echo $image;?>
            </p>
        <?php }?>
        <?php
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
        ?>
    </div>
</div>