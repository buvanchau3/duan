<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link rel="stylesheet" href="">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    /* padding: 20px; */
}

.title {
    text-align: center;
    margin-bottom: 30px;
    font-size: 2rem;
}

.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-content {
    padding: 15px;
    text-align: center;
}

.card-title {
    margin: 10px 0;
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    margin: 0;
    font-size: 0.95rem;
    color: #555;
}

.footer {
    padding: 15px;
    text-align: center;
    background-color: none !important;
}

.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #0056b3;
}
.card a{
    text-decoration: none;
    color: #333;
}
.card a:hover{
    text-decoration: none;
    color: #333
}

    </style>
</head>

<body>
<div class="container">
        <h2 class="title">Danh Mục Sản Phẩm</h2>
        <div class="card-container">

            <div class="card">
                <a href="#">
                    <div class="card-content">
                    <h5 class="card-title">Đồ Nam</h5>
                    <img src="../image/do-nam-1.png" alt="Đồ Nam" class="card-img" style="height:300px; padding: 0">
        
                </div>
                </a>
            </div>

            <div class="card">
                <a href="#">
                    <div class="card-content">
                    <h5 class="card-title">Đồ nữ</h5>
                    <img src="../image/do-nu-1.png" alt="Đồ nữ" class="card-img" style="height:300px; padding: 0">

                </div>
                </a>
            </div>

            
            <div class="card">
                <a href="#">
                    <div class="card-content">
                    <h5 class="card-title">Bé Trai</h5>
                    <img src="../image/bé-trai-1.png" alt="Bé Trai" class="card-img" style="height:300px; padding: 0">

                </div>
                </a>
            </div>

            <div class="card">
                <a href="#">
                    <div class="card-content">
                    <h5 class="card-title">Bé Gái</h5>
                    <img src="../image/bé-gái-2.png" alt="Bé Gái" class="card-img" style="height:300px; padding: 0">
        
                </div>
                </a>
            </div>

        </div>
    </div>

    <script src="scripts.js"></script>
</body>

</html>
