<button  onclick="document.getElementById('id01').style.display='block'">Đăng nhập</button>
<div id="id01" class="modal">


    <!-- Modal Content -->
    <form class="" action="/action_page.php">
        <div class="login-container mt-5">
            <span onclick="document.getElementById('id01').style.display='none'" class="close"
                title="Hủy đăng nhập">&times;</span>
            <div class="text-center mb-4">
                <img src="img/Lg-login.png" alt="Avatar" class="avatar rounded-circle">
                <h2>Đăng Nhập</h2>
            </div>

            <div class="mb-3">
                <label for="uname" class="d-flex justify-content-start"><b>Tên Đăng Nhập</b></label>
                <input type="text" class="form-control" id="uname" placeholder="Nhập tên đăng nhập" name="uname"
                    required>
            </div>

            <div class="mb-3">
                <label for="psw" class="d-flex justify-content-start"><b>Mật Khẩu</b></label>
                <input type="password" class="form-control" id="psw" placeholder="Nhập mật khẩu" name="psw" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <label>
                    <input type="checkbox" checked="checked" name="remember"> Ghi nhớ </br>

                </label>
                <span class="align-items-right"> <a href="#">Quên mật khẩu?</a></span>
            </div>
            <div class="mb-2">

                <button type="submit" class="btn btn-primary w-100 p-1">Đăng Nhập</button>

            </div>
            <div class="d-flex align-items-center">
                <span class="me-2">Bạn chưa có tài khoản?</span>
                <a href="#" class="btn btn-link p-1 text-primary text-decoration-none">Đăng ký một tài khoản</a>
            </div>

        </div>
    </form>
</div>
<style>
            .login-container {
                max-width: 400px;
                margin: auto;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background-color: #fff;
            }

            .avatar {
                width: 100px;
                height: 100px;
                margin-bottom: 20px;
            }

            .cancelbtn {
                background-color: #f44336;
                /* Red */
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
            }
        </style>



<script>
    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>