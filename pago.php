<?php

require 'config/database.php';
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

//print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id = ? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}

$total = 0;

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
    <header class="header" style="padding-top: .6rem; padding-bottom: .5rem;">
        <div class="contenedor contenido-header">

            <a href="index.php"><img src="./img/logo.jpg" alt="logo nical"></a>

            <nav class="navegacion-principal">
                <a href="index.php" style="text-decoration: none;">Inicio</a>
                <a href="tienda.php" style="text-decoration: none;">Tienda</a>
                <a href="checkout.php" style="text-decoration: none;">
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
        <div class="filas">
            <div>
                <h4>Detalles de pago</h4>
                <div id="paypal-button-container" class="estilo-pago"></div>
            </div>

            <div>



                <div class="tabla-contenedor">
                    <table class="tabla-principal">
                        <thead class="tcabeza1">
                            <tr class="cabeza-color1">
                                <th>Producto</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="tcuerpo">
                            <?php if ($lista_carrito == null) {
                                echo "<tr><td colspan='5' class='centrar-texto'>No hay productos en el carrito</td></tr>";
                            } else {

                                $total = 0;
                                foreach ($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                            ?>


                                    <tr>
                                        <td><?php echo $nombre; ?> </td>

                                        <td>
                                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal); ?></div>
                                        </td>

                                    </tr>

                            <?php }
                            } ?>
                            <tr>
                                <td colspan="2">
                                    <p style="text-align: center; margin-left: 20rem;" class="h3" id="total"><?php echo MONEDA . number_format($total); ?></p>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>



    </main>

    <footer class="footer" style="margin-top: 30rem;">
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

    <?php
    $total_dolares = $total * EXCHANGE_RATE;
    ?>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo number_format($total_dolares, 2, '.', ''); ?>,

                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                let URL = 'clases/captura.php';
                actions.order.capture().then(function(detalles) {
                    console.log(detalles);

                    return fetch(URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response) {
                        if (response.ok) {
                            alert('Compra Realizada')
                            window.location.href = "index.php";
                        } else {
                            console.error('Error al enviar los detalles.');
                        }
                    }).catch(function(error) {
                        console.error('Hubo un error en la captura:', error);
                    });
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado")
                console.log(data);
            }
        }).render('#paypal-button-container')
    </script>
</body>

</html>
