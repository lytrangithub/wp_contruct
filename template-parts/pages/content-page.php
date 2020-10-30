<!-- End banner -->
<h1><p><?php echo the_title()?></p><span class="line">&nbsp;</span></h1>
<!-- Default page -->
<div class="default-page">
    <!-- Container -->
    <div class="container">
        <?php
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
        ?>
    </div>  
    <!-- End container -->
</div>
<!-- End default page -->