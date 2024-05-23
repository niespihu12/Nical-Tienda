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
}

$total = 0;





// session_destroy();
//print_r($_SESSION);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

        <div class="tabla-contenedor">
            <table class="tabla-principal">
                <thead class="tcabeza">
                    <tr class="cabeza-color">
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
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
                                <td><?php echo MONEDA . number_format($precio_desc); ?> </td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal); ?></div>
                                </td>
                                <td>
                                    <a class="eliminar" href="#" id="eliminar" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="<?php echo $_id; ?>">Eliminar</a>
                                </td>
                            </tr>

                    <?php }
                    } ?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">
                            <p class="h3" id="total"><?php echo MONEDA . number_format($total); ?></p>

                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
        <?php if ($lista_carrito != null) { ?>
        <div class="ubi-boton" style="margin-top: 2rem;">
            <div>
                
                <a class="boton-pagos" href="pago.php">Realizar pago</a>
            </div>

        </div>
        <?php } ?>



    </main>
    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el producto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer" style="margin-top: 30rem;">
      <div class="footer__logo"></div>
      <div class="footer__informacion">
        <h4>Informacion</h4>
        <a href="" style="text-decoration: none;"><p>Entrega</p></a>
        <a href="" style="text-decoration: none;"><p>Garantia</p></a>
      </div>
      <div class="footer__informacion">
        <h4>Ayuda</h4>
        <a href="" style="text-decoration: none;"><p>Contactanos</p></a>
        <a href="" style="text-decoration: none;"><p>Encuentra una tienda</p></a>
        <a href="" style="text-decoration: none;"><p>Terminos de compra</p></a>
      </div>
      <div class="footer__informacion">
        <h4>Siguenos</h4>
        <a href="" style="text-decoration: none;">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="icon icon-tabler icon-tabler-brand-facebook"
            width="44"
            height="44"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="#ffffff"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"
            />
          </svg>
        </a>
        <a href="" style="text-decoration: none;"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            class="icon icon-tabler icon-tabler-brand-instagram"
            width="44"
            height="44"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="#ffffff"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"
            />
            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            <path d="M16.5 7.5l0 .01" />
          </svg>
        </a>
      </div>
    </footer>

    <script>
        let eliminaModal = document.getElementById('eliminaModal');
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let id = button.getAttribute('data-bs-id');
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })

        function actualizaCantidad(cantidad, id) {
            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'agregar');
            formData.append('id', id);
            formData.append('cantidad', cantidad);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let divsubtotal = document.getElementById('subtotal_' + id);
                        divsubtotal.innerHTML = data.sub;

                        let total = 0.00;
                        let list = document.getElementsByName('subtotal[]');

                        for (let i = 0; i < list.length; i++) {
                            // Reemplaza caracteres no numéricos, como comas
                            let valor = list[i].innerHTML.replace(/[^\d.-]/g, '');
                            total += parseFloat(valor);
                        }

                        // Formatea el total con el formato de moneda deseado
                        total = new Intl.NumberFormat('es-CO', {

                        }).format(total);
                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                    }
                });
        }

        function eliminar() {
            let botonElimina = document.getElementById("btn-elimina");
            let id = botonElimina.value;

            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response =>  response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload();
                    }
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>