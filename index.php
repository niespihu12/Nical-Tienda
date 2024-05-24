<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 ORDER BY id DESC LIMIT 4");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Document</title>
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
  <section class="hero">
    <div class="contenido-hero">
      <h2>NICAL <span>STORE</span></h2>

      <div class="ubicacion">
        <p>
          Viste con el rugido de la elegancia: camisas <br />que llevan el
          motor de las marcas más prestigiosas
        </p>
      </div>
    </div>
  </section>
  <div class="categorias">
    <div class="categorias__gorras">
      <a href="tienda.php?category=2"> Gorras </a>
    </div>
    <div class="categorias__camisas">
      <a href="tienda.php?category=1"> Camisas </a>
    </div>
    <div class="categorias__accesorios">
      <a href="tienda.php?category=3"> Accesorios </a>
    </div>
  </div>

  <div class="tendencias">
    <h2 class="centrar-texto">TENDENCIAS</h2>
    <div class="division">
      <?php
      foreach ($resultado as $row) { ?>
        <div class="Tendencias">
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

  <footer class="footer">
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

</body>

</html>
