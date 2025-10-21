<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký | 4MEN Shop</title>
    <!-- Google Fonts: Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* CSS Reset and Basic Setup */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #d1c6b8; /* Màu nền be giống trang login */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; 
        }

        /* Wrapper chính cho toàn bộ trang */
        .register-page-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Hiệu ứng đom đóm */
        .fireflies {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .firefly {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #fff7b5;
            border-radius: 50%;
            box-shadow: 0 0 8px 3px #fff7b5, 0 0 14px 5px #ffc107;
            animation: flicker linear infinite;
            opacity: 0;
        }
        
        @keyframes flicker {
            0%, 100% {
                opacity: 0;
                transform: scale(0.8) translate(0, 0);
            }
            10%, 90% {
                 opacity: 1;
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1) translate(15px, -15px);
            }
        }
        
        /* Nền với các icon bay lơ lửng */
        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Style cho từng icon bay */
        .flying-item {
            position: absolute;
            color: rgba(176, 97, 75, 0.25);
            bottom: -150px;
            animation: fly 20s linear infinite;
            user-select: none;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.4),
                         0 0 25px rgba(176, 97, 75, 0.5),
                         0 0 45px rgba(176, 97, 75, 0.3);
        }

        /* Thiết lập ngẫu nhiên cho các icon */
        .flying-item:nth-child(1) { left: 10%; animation-duration: 25s; font-size: 80px; }
        .flying-item:nth-child(2) { left: 20%; animation-duration: 17s; animation-delay: 2s; font-size: 120px; }
        .flying-item:nth-child(3) { left: 30%; animation-duration: 32s; animation-delay: 4s; font-size: 60px; }
        .flying-item:nth-child(4) { left: 40%; animation-duration: 20s; animation-delay: 1s; font-size: 90px; }
        .flying-item:nth-child(5) { left: 50%; animation-duration: 24s; animation-delay: 5s; font-size: 70px; }
        .flying-item:nth-child(6) { left: 60%; animation-duration: 30s; animation-delay: 3s; font-size: 110px; }
        .flying-item:nth-child(7) { left: 70%; animation-duration: 18s; animation-delay: 6s; font-size: 50px; }
        .flying-item:nth-child(8) { left: 80%; animation-duration: 28s; animation-delay: 2s; font-size: 100px; }
        .flying-item:nth-child(9) { left: 90%; animation-duration: 21s; animation-delay: 4s; font-size: 85px; }
        .flying-item:nth-child(10){ left: 5%;  animation-duration: 26s; animation-delay: 7s; font-size: 95px; }

        @keyframes fly {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-120vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Container cho form đăng ký */
        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px; /* Tăng chiều rộng một chút */
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(176, 97, 75, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-align: center;
            transition: transform 0.2s ease-out;
        }

        .register-form h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #b0614b; 
            font-weight: 800;
        }

        .register-form > p {
            color: #a88f82; 
            margin-bottom: 30px;
            font-weight: 600;
        }

        .input-group {
            text-align: left;
            margin-bottom: 18px; /* Giảm margin bottom một chút */
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #9c7f73; 
            font-size: 0.9rem;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .input-group input:focus {
            outline: none;
            border-color: #b0614b;
            box-shadow: 0 0 0 4px rgba(176, 97, 75, 0.2);
        }
        
        .register-btn {
            width: 100%;
            padding: 16px;
            margin-top: 10px;
            background: linear-gradient(45deg, #b0614b, #8c4d3c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(140, 77, 60, 0.4);
            letter-spacing: 0.5px;
        }

        .register-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(140, 77, 60, 0.5);
        }

        .login-link {
            margin-top: 25px;
            font-size: 0.95rem;
            color: #a88f82;
        }

        .login-link a {
            color: #b0614b;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-page-wrapper">
        <div class="fireflies"></div>

        <div class="animated-bg">
            <i class="fa-solid fa-shirt flying-item"></i>
            <i class="fa-solid fa-vest-patches flying-item"></i>
            <i class="fa-solid fa-user-tie flying-item"></i>
            <i class="fa-solid fa-mitten flying-item"></i>
            <i class="fa-solid fa-hat-cowboy flying-item"></i>
            <i class="fa-solid fa-glasses flying-item"></i>
            <i class="fa-solid fa-socks flying-item"></i>
            <i class="fa-solid fa-shoe-prints flying-item"></i>
            <i class="fa-brands fa-redhat flying-item"></i>
            <i class="fa-solid fa-person-dress flying-item"></i>
        </div>

        <div class="register-container">
            <form action="#" method="POST" class="register-form">
                <h2>Đăng Ký</h2>
                <p>Tạo tài khoản mới của bạn tại 4MEN!</p>
                <div class="input-group">
                    <label for="fullname">Họ và Tên</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
                </div>
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Tạo mật khẩu" required>
                </div>
                 <div class="input-group">
                    <label for="confirm-password">Xác nhận Mật khẩu</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Nhập lại mật khẩu" required>
                </div>
                <button type="submit" class="register-btn">Tạo Tài Khoản</button>
                <div class="login-link">
                    <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const wrapper = document.querySelector('.register-page-wrapper');
        const registerContainer = document.querySelector('.register-container');
        const flyingItems = document.querySelectorAll('.flying-item');

        wrapper.addEventListener('mousemove', (e) => {
            let xAxis = (window.innerWidth / 2 - e.pageX) / (window.innerWidth / 2);
            let yAxis = (window.innerHeight / 2 - e.pageY) / (window.innerHeight / 2);

            registerContainer.style.transform = `perspective(1000px) rotateY(${xAxis * 5}deg) rotateX(${-yAxis * 5}deg)`;

            flyingItems.forEach((item, index) => {
                const speed = (index + 1) * 0.5;
                const x = -xAxis * speed * 5;
                const y = -yAxis * speed * 5;
                item.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });

        wrapper.addEventListener('mouseleave', () => {
            registerContainer.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
            flyingItems.forEach(item => {
                item.style.transform = `translateX(0px) translateY(0px)`;
            });
        });

        const firefliesContainer = document.querySelector('.fireflies');
        const fireflyCount = 30;

        for (let i = 0; i < fireflyCount; i++) {
            const firefly = document.createElement('div');
            firefly.classList.add('firefly');

            firefly.style.top = `${Math.random() * 100}%`;
            firefly.style.left = `${Math.random() * 100}%`;
            firefly.style.animationDuration = `${Math.random() * 5 + 4}s`;
            firefly.style.animationDelay = `${Math.random() * 6}s`;

            firefliesContainer.appendChild(firefly);
        }
    </script>
</body>

</html>
