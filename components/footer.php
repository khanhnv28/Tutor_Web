<!-- <footer class="footer">

   &copy; copyright @ <?= date('Y'); ?> by <span>HUIT</span> | all rights reserved!

</footer> -->


<footer class="footer">
<style>
   body {
    min-height: 100vh; /* Đảm bảo body luôn có chiều cao tối thiểu bằng 100% của chiều cao khung nhìn */
    display: flex;
    flex-direction: column; /* Cấu trúc trang thành các phần tử theo cột */
}

.main-content {
    flex: 1; /* Đẩy footer xuống dưới cùng */
}

.footer {
    position: relative;
    bottom: 0;
    width: 100%;
    background-color: #f8f9fa; 
    padding: 20px 0; 
    color: #333; 
    font-family: Arial, sans-serif;
}
   /* .footer {
      position: fixed;
      bottom: 0;
    background-color: #f8f9fa; 
    padding: 20px 0; 
    color: #333; 
    font-family: Arial, sans-serif;
 } */
 
 .footer-container {
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 20px;
 }
 
 .footer-row {
    display: flex; 
    justify-content: space-between;
    align-items: flex-start; 
 }
 
 .footer-col {
    flex: 1; 
    margin: 0 10px; 
 }
 
 .about-us h4, .follow-us h4 {
    font-size: 20px; 
    font-weight: bold; 
    margin-bottom: 10px; 
    color: #333; 
 }
 
 .about-us p, .follow-us p {
    font-size: 20px; 
    margin-bottom: 8px; 
    color: #666; /* Màu chữ */
 }
 
 .follow-us p a {
    margin-right: 10px; /* Khoảng cách giữa các liên kết mạng xã hội */
    color: #555; /* Màu chữ cho liên kết */
    text-decoration: none; /* Bỏ gạch chân */
    font-size: 24px;
 }
 
 .follow-us p a:hover {
    color: #007bff; /* Màu khi hover vào liên kết */
 }
 
 .footer-separator {
    border: none; /* Bỏ viền cho đường phân cách */
    height: 1px; /* Chiều cao đường phân cách */
    background-color: #ddd; /* Màu cho đường phân cách */
    margin: 20px 0; /* Khoảng cách trên và dưới đường phân cách */
 }
 
 .footer-bottom {
    text-align: center; /* Căn giữa cho bản quyền */
    padding-top: 10px; /* Khoảng cách trên cho bản quyền */
 }
 
 .footer-bottom p {
    font-size: 20px; /* Kích thước chữ cho bản quyền */
    color: #666; /* Màu chữ cho bản quyền */
 }
 
 .footer-bottom span {
    color: #007bff; 
 }
body.dark .footer {
    background-color: #222; /* Màu nền footer khi ở chế độ tối */
    color: #f8f9fa; /* Màu chữ khi ở chế độ tối */
}

body.dark .about-us h4{
   color: #ccc;
}
body.dark .follow-us h4{
   color: #ccc;
}
body.dark .about-us p,
body.dark .follow-us p {
    color: #ccc; /* Màu chữ cho các đoạn văn trong chế độ tối */
}

body.dark .follow-us p a {
    color: #ddd; /* Màu liên kết cho chế độ tối */
}

body.dark .follow-us p a:hover {
    color: #ffcc00; /* Màu khi hover vào liên kết trong chế độ tối */
}

body.dark .footer-bottom p {
    color: #ccc; /* Màu chữ cho bản quyền trong chế độ tối */
}

body.dark .footer-bottom span {
    color: #ffcc00; /* Màu chữ cho phần span trong chế độ tối */
}


</style>
<script src="../js/script.js"></script>
    <div class="footer-container">
        <div class="footer-row">
            <div class="footer-col about-us">
                <h4>Information</h4>
                <p><i class="fas fa-map-marker-alt" ></i> 140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP. Hồ Chí Minh</p>
                <p><i class="fas fa-phone"></i> Telephone: (028) 3816 1673 - (028) 3816 3319</p>
                <p><i class="fas fa-envelope"></i> Email: info@hufi.edu.vn</p>
            </div>

            <div class="footer-col follow-us">
                <h4>Follow Us</h4>
                <p>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </p>
            </div>
        </div>

        <hr class="footer-separator">
        <div class="footer-bottom">
            <p>&copy; copyright @ <?= date('Y'); ?> by <span>HUIT</span> | all rights reserved!</p>
        </div>
    </div>
</footer>



