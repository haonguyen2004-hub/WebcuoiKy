<?php
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Tất cả danh mục</span>
                    </div>
                    <ul>
                        
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                         //   echo '<a href="sachtheochude.php?id=' . $row['MaCD'] . '" class="list-group-item">' . $row['TenChuDe'] . '</a>';
                            echo '<li><a href="#">'.$row['category_name'].'</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>