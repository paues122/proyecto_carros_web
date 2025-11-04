<?php

include_once("models/sistemam.php");
$app = new Sistema();
$app->isAuth();

include 'includes/header.php'; 
?>

<div id="autoAgenciaCarousel" class="carousel slide w-100" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/img1.png" class="d-block w-100" alt="AutoAgencia">
      <div class="carousel-caption">
        <h1>Bienvenido a <span>AutoAgencia</span></h1>
        <p>Tu agencia multimarca favorita. Encuentra el auto perfecto con financiamiento fácil y servicio premium.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="images/img2.png" class="d-block w-100" alt="Imagen 2 de AutoAgencia">
      <div class="carousel-caption">
        <h1>Bienvenido a <span>AutoAgencia</span></h1>
        <p>Tu agencia multimarca favorita.</p>
      </div>
    </div>
    </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#autoAgenciaCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#autoAgenciaCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<section class="services-container container mt-4">
  <div class="row justify-content-center g-4">

    <div class="col-lg-4 col-md-6">
      <article class="service-card card center h-100 p-3">
        <div style="font-size:2rem; margin-bottom:.5rem;">🚗</div>
        <h3>Variedad Multimarca</h3>
        <p>Más de 100 autos y 17 marcas diferentes en Celaya de las mejores en catálogo.</p>
      </article>
    </div>

    <div class="col-lg-4 col-md-6">
      <article class="service-card card center h-100 p-3">
        <div style="font-size:2rem; margin-bottom:.5rem;">👤</div>
        <h3>Atención Personalizada</h3>
        <p>Para clientes y empleados, siempre al servicio. Se hacen cotizaciones a tu medida.</p>
      </article>
    </div>

    <div class="col-lg-4 col-md-6">
      <article class="service-card card center h-100 p-3">
        <div style="font-size:2rem; margin-bottom:.5rem;">💰</div>
        <h3>Financiamiento Rápido</h3>
        <p>Cotiza y aprueba tu crédito en minutos. Solo con INE y comprobante de domicilio.</p>
      </article>
    </div>

  </div>
</section>

<section class="about-section">
  <h2>¿Quiénes somos?</h2>
  <p>Somos Agencia GAM, tu agencia multimarca favorita dedicada a ofrecerte la mejor experiencia de compra de autos.</p>
</section>

<?php include 'includes/footer.php'; ?>
