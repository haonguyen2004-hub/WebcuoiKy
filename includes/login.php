<button class="fa fa-user" onclick="document.getElementById('id01').style.display='block'">Login</button>
<div id="id01" class="modal">
    <span onclick="document.getElementById('id01').style.display='none'" class="close"
        title="Close Modal">&times;</span>

    <!-- Modal Content -->
    <form class="modal-content animate" action="/action_page.php">
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
            background-color: #f44336; /* Red */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
        }
    </style>
        <title>Login</title>
        </head>

        <body>

            <div class="login-container mt-5">
                <div class="text-center mb-4">
                    <img src="img_avatar2.png" alt="Avatar" class="avatar rounded-circle">
                    <h2>Đăng Nhập</h2>
                </div>

                    <div class="mb-3">
                        <label for="uname" class="form-label"><b>Tên Đăng Nhập</b></label>
                        <input type="text" class="form-control" id="uname" placeholder="Nhập tên đăng nhập" name="uname"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="psw" class="form-label"><b>Mật Khẩu</b></label>
                        <input type="password" class="form-control" id="psw" placeholder="Nhập mật khẩu" name="psw"
                            required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                        <label>
                            <input type="checkbox" checked="checked" name="remember"> Ghi nhớ
                        </label>
                    </div>
            </div>

            <div class="container text-center mt-3" style="background-color: #f1f1f1; padding: 15px;">
                <button type="button" onclick="document.getElementById('id01').style.display='none'"
                    class="cancelbtn">Hủy</button>
                <span class="psw">Quên <a href="#">mật khẩu?</a></span>
            </div>
    </form>
</div>

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