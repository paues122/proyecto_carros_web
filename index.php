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
      <div class="carousel-caption d-none d-md-block">
        <h1 class="display-4 fw-bold">Bienvenido a <span class="text-primary">AutoAgencia</span></h1>
        <p class="lead">Tu agencia multimarca favorita. Encuentra el auto perfecto con financiamiento fácil y servicio premium.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="images/img2.png" class="d-block w-100" alt="Imagen 2 de AutoAgencia">
      <div class="carousel-caption d-none d-md-block">
        <h1 class="display-4 fw-bold">Bienvenido a <span class="text-primary">AutoAgencia</span></h1>
        <p class="lead">Tu agencia multimarca favorita.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-ride="prev" data-bs-target="#autoAgenciaCarousel">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-ride="next" data-bs-target="#autoAgenciaCarousel">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>

<section class="services-container container my-5">
  <div class="row justify-content-center g-4">
    <div class="col-lg-4 col-md-6">
      <article class="service-card card shadow-sm h-100 p-4 text-center border-0">
        <h2 class="h5 fw-bold">Variedad Multimarca</h2>
        <p class="text-muted small">Más de 100 autos y 17 marcas diferentes en Celaya de las mejores en catálogo.</p>
      </article>
    </div>
    <div class="col-lg-4 col-md-6">
      <article class="service-card card shadow-sm h-100 p-4 text-center border-0">
        <h2 class="h5 fw-bold">Atención Personalizada</h2>
        <p class="text-muted small">Para clientes y empleados, siempre al servicio. Se hacen cotizaciones a tu medida.</p>
      </article>
    </div>
    <div class="col-lg-4 col-md-6">
      <article class="service-card card shadow-sm h-100 p-4 text-center border-0">
        <h2 class="h5 fw-bold">Financiamiento Rápido</h2>
        <p class="text-muted small">Cotiza y aprueba tu crédito en minutos. Solo con INE y comprobante de domicilio.</p>
      </article>
    </div>
  </div>
</section>

<section class="container my-5">
  <div class="row g-5">
    
  
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-0">
          <h3 class="card-title h5 fw-bold p-3 mb-0 bg-primary text-white">Nuestra Ubicación</h3>
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3736.2659735434504!2d-100.82085082460478!3d20.536295680992698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842cba943545fcd7%3A0x2bdea6d8bac6a00e!2sAutoAgencia!5e0!3m2!1ses!2smx!4v1757306595968!5m2!1ses!2smx" 
            width="100%" 
            height="350" 
            style="border:0; border-radius: 0 0 0.375rem 0.375rem;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
          <div class="p-3">
            <p class="mb-1"><strong>Dirección:</strong> Tecnológico Nacional de México, Celaya</p>
            <p class="mb-0">
              <a href="https://maps.app.goo.gl/kGP5RVteUhywSdhZ7" target="_blank" class="btn btn-outline-primary btn-sm">
                Abrir en Google Maps
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>

   
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h3 class="h5 fw-bold text-primary mb-4">Preguntas Frecuentes</h3>
          <div class="accordion accordion-flush" id="faqAccordion">

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                  ¿Qué documentos necesito para financiar un auto?
                </button>
              </h2>
              <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Solo necesitas tu <strong>INE</strong> y un <strong>comprobante de domicilio</strong> (no mayor a 3 meses).
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                  ¿En cuánto tiempo se aprueba el crédito?
                </button>
              </h2>
              <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  En <strong>15 a 30 minutos</strong> tienes preaprobación. ¡Rápido y sin complicaciones!
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                  ¿Puedo apartar un auto con enganche?
                </button>
              </h2>
              <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  ¡Sí! Con un enganche mínimo apartamos tu auto por <strong>48 horas</strong>.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                  ¿Tienen autos seminuevos garantizados?
                </button>
              </h2>
              <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Todos nuestros autos pasan por una <strong>inspección de 120 puntos</strong> y tienen <strong>garantía de 3 meses</strong>.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<section class="about-section bg-light py-5">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <h2 class="fw-bold text-primary mb-4">¿Quiénes somos?</h2>
        <p class="lead text-muted">
          Somos <strong>Agencia GAM</strong>, tu agencia multimarca favorita en Celaya. 
          Con más de 10 años de experiencia, ofrecemos autos de calidad, 
          financiamiento inmediato y la mejor atención personalizada.
        </p>
        
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>