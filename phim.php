<?php
// Kiểm tra trạng thái session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include("php/header.php");
include("php/db.php");

// Lấy unique_id của user nếu đã đăng nhập
$unique_id = isset($_SESSION['unique_id']) ? $_SESSION['unique_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý phim</title>
  <meta name="theme-color" content="#fafafa">
  <link rel="stylesheet" href="./css/myst.css?v=2">
  <style>
    .img-hover::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      margin-left: 10px;
      width: 100%;
      height: 100%;
      background: url('Images/playbt.png') center center no-repeat;
      background-size: 50%;
      opacity: 0;
      /* Mặc định ẩn */
      transition: opacity 0.3s ease;
    }
  </style>
</head>

<body>
  <?php
  include("php/db.php");

  $sql = "SELECT * FROM movies";
  $result = mysqli_query($conn, $sql);

  $phimSapChieu = [];
  $phimDangChieu = [];
  $xuatChieuDacBiet = [];

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      if ($row['tinh_trang'] == 1) {
        $phimSapChieu[] = $row;
      } elseif ($row['tinh_trang'] == 2) {
        $phimDangChieu[] = $row;
      } elseif ($row['tinh_trang'] == 3) {
        $xuatChieuDacBiet[] = $row;
      }
    }
  }

  ?>

  <!-- Thanh Chuyển Phim -->
  <div class="chuyenphim">
    <div id="phimSapChieu" class="hphim" onclick="switchCategory('sapChieuList')">
      <p style="font-size: 25px">Phim Sắp Chiếu</p>
    </div>
    <div id="phimDangChieu" class="hphim" onclick="switchCategory('dangChieuList')">
      <p style="font-size: 25px">Phim Đang Chiếu</p>
    </div>
    <div id="xuatChieuDacBiet" class="hphim" onclick="switchCategory('dacBietList')">
      <p style="font-size: 25px">Xuất Chiếu Đặc Biệt</p>
    </div>
  </div>

  <!-- Nội dung danh sách phim -->
  <div>
    <!-- Mục Phim Sắp Chiếu -->
    <div id="sapChieuList" style="display: none">
      <?php foreach ($phimSapChieu as $phim): ?>
        <div class="movie-item">
          <div class="img-hover"
            onclick="showTrailer('<?php echo $phim['youtube_link']; ?>', '<?php echo $phim['name']; ?>')">
            <img src="<?php echo $phim['image']; ?>" style="width: 200px; height: 350px;margin-left:10px">
          </div>
          <h4><a href="<?php echo $phim['php_link']; ?>"><?php echo $phim['name']; ?></a></h4>
          <p>Thể loại: <?php echo $phim['movie_type']; ?></p>
          <p>Thời lượng: <?php echo $phim['duration']; ?> phút</p>
          <button class="datve"
            onclick="<?php echo $unique_id ? "showDatVe('{$phim['name']}', '{$phim['image']}', '{$phim['movie_type']}', '{$phim['duration']}')" : "alert('Bạn cần đăng nhập để đặt vé!')"; ?>">
            Đặt vé
          </button>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Mục Phim Đang Chiếu -->
    <div id="dangChieuList">
      <?php foreach ($phimDangChieu as $phim): ?>
        <div class="movie-item">
          <div class="img-hover"
            onclick="showTrailer('<?php echo $phim['youtube_link']; ?>', '<?php echo $phim['name']; ?>')">
            <img src="<?php echo $phim['image']; ?>" style="width: 200px; height: 350px;margin-left:10px">
          </div>
          <h4><a href="<?php echo $phim['php_link']; ?>"><?php echo $phim['name']; ?></a></h4>
          <p>Thể loại: <?php echo $phim['movie_type']; ?></p>
          <p>Thời lượng: <?php echo $phim['duration']; ?> phút</p>
          <button class="datve"
            onclick="<?php echo $unique_id ? "showDatVe('{$phim['name']}', '{$phim['image']}', '{$phim['movie_type']}', '{$phim['duration']}')" : "alert('Bạn cần đăng nhập để đặt vé!')"; ?>">
            Đặt vé
          </button>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Mục Xuất Chiếu Đặc Biệt -->
    <div id="dacBietList" style="display: none">
      <?php foreach ($xuatChieuDacBiet as $phim): ?>
        <div class="movie-item">
          <div class="img-hover"
            onclick="showTrailer('<?php echo $phim['youtube_link']; ?>', '<?php echo $phim['name']; ?>')">
            <img src="<?php echo $phim['image']; ?>" style="width: 200px; height: 350px;margin-left:10px">
          </div>
          <h4><a href="<?php echo $phim['php_link']; ?>"><?php echo $phim['name']; ?></a></h4>
          <p>Thể loại: <?php echo $phim['movie_type']; ?></p>
          <p>Thời lượng: <?php echo $phim['duration']; ?> phút</p>
          <button class="datve"
            onclick="<?php echo $unique_id ? "showDatVe('{$phim['name']}', '{$phim['image']}', '{$phim['movie_type']}', '{$phim['duration']}')" : "alert('Bạn cần đăng nhập để đặt vé!')"; ?>">
            Đặt vé
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Modal hiển thị video -->
  <div id="trailerModal" style="display:none;" onclick="closeTrailer(event)">
    <div class="modal-content" onclick="stopPropagation(event)">
      <h2 id="modalTitle" style="text-align: center; margin-top: 10px;"></h2>
      <hr>
      <iframe id="trailerFrame" width="850" height="600" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>
    </div>
  </div>

  <!-- Modal Đặt Vé -->
  <div id="datVeModal" style="display:none;" onclick="closeDatVe(event)">
    <div class="modal-content" onclick="stopPropagation(event)">
      <p id="movieName" style="font-weight: bold; margin-bottom: 10px;"></p> <!-- Hiển thị tên phim -->
      <hr>
      <div id="datVeForm" style="padding: 20px;">
        <button type="submit"
          style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none;"><a
            href="datve.php">Xác nhận</a></button>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div>
    <?php include_once 'php/footer.php'; ?>
  </div>

  <script src="js/phim.js"></script>
  <script>
    // Hiển thị modal đặt vé
    function showDatVe(movieName, movieImage, movieType, duration) {
      sessionStorage.setItem('selectedMovie', movieName);
      sessionStorage.setItem('selectedMovieImage', movieImage);
      sessionStorage.setItem('selectedMovieType', movieType);
      sessionStorage.setItem('selectedMovieDuration', duration);

      document.getElementById('datVeModal').style.display = 'block';
      document.getElementById('movieName').innerText = movieName;
    }</script>
</body>

</html>