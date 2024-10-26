<?php
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>
    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    
                    <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                        
                            echo '<div class="col-lg-3">';
                            echo '<div class="categories__item set-bg" data-setbg="img/categories/'.$row['category_image'].'">';
                            echo'<h5><a href="#">'.$row['category_name'].'</a></h5> </div> </div>';
                        
                        }
                        ?>
                       
                  
                  
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->