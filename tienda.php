<?php
require 'config/database.php';
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

$order_by = "precio ASC"; // Valor por defecto

if (isset($_GET['order'])) {
  $order = $_GET['order'];
  if ($order == "price_desc") {
    $order_by = "precio DESC";
  } else if ($order == "price_asc") {
    $order_by = "precio ASC";
  }
}

$category_filter = "";
if (isset($_GET['category']) && in_array($_GET['category'], ['1', '2', '3'])) {
  $category_filter = "AND id_categoria = " . $_GET['category'];
}

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 $category_filter ORDER BY $order_by");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Tienda</title>

</head>

<body>
  <header class="header">
    <div class="contenedor contenido-header">
      <a href="index.php"><img src="./img/logo.jpg" alt="logo nical"></a>
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



    <h1 class="centrar-texto">Tienda</h1>

    <div class="organizar-productos">
      <div class="contenido Tienda">
        <aside class="filtros">
          <h3>Categorías: </h3>
          <div class="categorias-estandar">
            <a href="tienda.php?category=all" <?php if (!isset($_GET['category']) || (isset($_GET['category']) && $_GET['category'] == 'all')) echo 'style="background-color: #ccc;"'; ?>>Todas</a>
            <a href="tienda.php?category=1" <?php if (isset($_GET['category']) && $_GET['category'] == '1') echo 'style="background-color: #ccc;"'; ?>>Camisas</a>
            <a href="tienda.php?category=2" <?php if (isset($_GET['category']) && $_GET['category'] == '2') echo 'style="background-color: #ccc;"'; ?>>Gorras</a>
            <a href="tienda.php?category=3" <?php if (isset($_GET['category']) && $_GET['category'] == '3') echo 'style="background-color: #ccc;"'; ?>>Accesorios</a>
          </div>
        </aside>
      </div>
      <div>
        <div class="seleccionar">
          <form method="GET" action="tienda.php">
            <label for="order">Ordenar por precio:</label>
            <select name="order" id="order" onchange="this.form.submit()">
              <option value="price_asc" <?php if (isset($_GET['order']) && $_GET['order'] == 'price_asc') echo 'selected'; ?>>Menor a mayor</option>
              <option value="price_desc" <?php if (isset($_GET['order']) && $_GET['order'] == 'price_desc') echo 'selected'; ?>>Mayor a menor</option>
            </select>
          </form>
        </div>

        <div class="grid">
          <?php foreach ($resultado as $row) { ?>
            <div class="producto">
              <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">
                <?php
                $id = $row['id'];
                $imagen = "img/productos/" . $id . "/principal.jpg";
                if (!file_exists($imagen)) {
                  $imagen = "img/no-photo.jpg";
                }
                ?>
                <div class="producto-imagen">
                  <img src="<?php echo $imagen; ?>" alt="imagen producto" width="200" height="250">
                </div>
                <div class="producto-informacion">
                  <p class="producto__nombre"><?php echo $row['nombre'] ?></p>
                  <p class="producto__precio"><?php echo MONEDA . number_format($row['precio']); ?></p>
                </div>
              </a>
            </div>
          <?php } ?>
        </div>


      </div>

    </div>






  </main>

  <footer class="footer" style="margin-top: 10rem;">
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
          <path stroke="none" d="M0 0h24h24H0z" fill="none" />
          <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
          <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
          <path d="M16.5 7.5l0 .01" />
        </svg>
      </a>
    </div>
  </footer>
</body>

</html>