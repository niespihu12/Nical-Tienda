<?php

require 'config/database.php';
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la peticion';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_images = 'img/productos/' . $id . '/';

            $rutaImg = $dir_images . 'principal.jpg';

            if (!file_exists($rutaImg)) {
                $rutaImg = 'img/no-photo.jpg';
            }

            $imagenes = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);
                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }
        }
    } else {
        echo 'Error al procesar la peticion';
        exit;
    }
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="http://localhost/nical/css/style.css" type="text/css">
    <title>Document</title>
</head>

<body>
    <header class="header">
        <div class="contenedor contenido-header">

            <a href="index.php"><img src="./img/logo.jpg" alt="logo nical" height="100" width="50"></a>

            <nav class="navegacion-principal">
                <a href="index.php">Inicio</a>
                <a href="tienda.php">Tienda</a>
                <a href="checkout.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17h-11v-14h-2" />
                        <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                    <span id="num_cart" class="contador"><?php echo $num_cart; ?></span>
                </a>

            </nav>
        </div>
    </header>


    <main class="contenedor">


        <p>INICIO > CAMISAS > <?php echo $nombre ?></p>

        <div class="camisas">
            <div class="camisa__imagen">
                <img src="<?php echo $rutaImg; ?>" alt="Imagen del producto">

            </div>


            <div class="camisa__contenido">
                <h2 style="font-size: 30;"><?php echo $nombre ?></h2>
                <?php if ($descuento > 0) { ?>
                    <p style="font-size: 1.6rem;"><del><?php echo MONEDA . number_format($precio); ?></del></p>
                    <h2 style="font-size:2rem">
                        <?php echo MONEDA . number_format($precio_desc); ?>
                        <small style="color: green; "><?php echo $descuento ?>% descuento </small>
                    </h2>
                <?php } else { ?>
                    <h2><?php echo MONEDA . number_format($precio); ?></h2>
                <?php } ?>
                <p class="descripcion"><?php echo $descripcion; ?></p>
                <div class="botones">
                    <Button class="carritito" onclick="comprarAhora(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
                        Comprar ahora
                    </Button>


                    <Button href="carrito.html" class="carritito" style="background-color: #7A7A7A;" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
                        Agregar al Carrito
                    </Button>
                </div>






            </div>
        </div>


    </main>

    <footer class="footer" style="margin-top: 8rem;">
        <div class="footer__logo"></div>
        <div class="footer__informacion">
            <h4>Informacion</h4>
            <a href="">
                <p>Entrega</p>
            </a>
            <a href="">
                <p>Garantia</p>
            </a>
        </div>
        <div class="footer__informacion">
            <h4>Ayuda</h4>
            <a href="">
                <p>Contactanos</p>
            </a>
            <a href="">
                <p>Encuentra una tienda</p>
            </a>
            <a href="">
                <p>Terminos de compra</p>
            </a>
        </div>
        <div class="footer__informacion">
            <h4>Siguenos</h4>
            <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                </svg>
            </a>
            <a href=""><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M16.5 7.5l0 .01" />
                </svg>
            </a>
        </div>
    </footer>






    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php';
            let formData = new FormData();
            formData.append('id', id);
            formData.append('token', token)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart");
                        elemento.innerHTML = data.numero;
                    }
                })
        }

        function comprarAhora(id, token) {
            let url = 'clases/carrito.php';
            let formData = new FormData();
            formData.append('id', id);
            formData.append('token', token);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        window.location.href = 'checkout.php';
                    }
                });
        }
    </script>
</body>

</html>