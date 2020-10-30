<?php 
    // lấy dữ liệu slide ra màng hình
    $slide = get_slide_list();
?>

<?php if($slide){ $i = 1; $num = 0; ?>
<div id="slide">
    <div id="demo" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
            <?php foreach($slide as $value){?>
            <li data-target="#demo" data-slide-to="<?php echo $num++; ?>" class="<?php if($num=="1"){echo 'active';}else{echo '';} ?>"></li>
            <?php } ?>
        </ul>
        <!-- The slideshow -->
        <div class="carousel-inner">
            <?php  foreach($slide as $value){ ?>
                <div class="carousel-item <?php if($i=="1"){echo 'active';} ?>">
                    <img src="<?php echo $value -> image_url; ?>" alt="Los Angeles">
                </div>
            <?php  $i++; } ?>
        </div>
        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>

    </div>
</div>
<?php } ?>