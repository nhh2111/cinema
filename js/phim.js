// Hiển thị trailer
function showTrailer(youtubeLink, title) {
    document.getElementById('trailerModal').style.display = 'block';
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('trailerFrame').src = youtubeLink;
  }

  // Đóng trailer khi bấm ngoài modal
  function closeTrailer(event) {
    if (event.target.id === 'trailerModal') {
      document.getElementById('trailerModal').style.display = 'none';
      document.getElementById('trailerFrame').src = '';
    }
  }
  // Ngăn sự kiện click trong nội dung modal
  function stopPropagation(event) {
    event.stopPropagation();
  }

  // Đóng modal đặt vé khi bấm ngoài modal
  function closeDatVe(event) {
    if (event.target.id === 'datVeModal') {
      document.getElementById('datVeModal').style.display = 'none';
    }
  }

  // Chuyển đổi danh sách phim
  function switchCategory(category) {
    document.getElementById('sapChieuList').style.display = 'none';
    document.getElementById('dangChieuList').style.display = 'none';
    document.getElementById('dacBietList').style.display = 'none';
    // Hiển thị danh sách phim tương ứng
    const element = document.getElementById(category);
    if (element) {
      element.style.display = 'flex'; 
    }
  }