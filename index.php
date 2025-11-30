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

<section class="container mb-5">
    <div class="card shadow border-0" style="background: #f8f9fa;">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt text-warning"></i> COTIZA TU SEGURO AUTO</h5>
            <span class="badge bg-warning text-dark">Servicio Python</span>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h5 class="card-title text-primary fw-bold mb-3">Protege tu inversión</h5>
                    <p class="text-muted mb-4">Elige entre nuestras 4 aseguradoras premium y selecciona tu plan de pago ideal.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Aseguradora</label>
                            <select id="selAseguradora" class="form-select">
                                <option value="">Selecciona...</option>
                                <option value="qualitas">Qualitas ($18,500)</option>
                                <option value="abba">Abba ($13,700)</option>
                                <option value="sura">Sura ($11,900)</option>
                                <option value="general">General de Seguros ($15,000)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Plan de Pago</label>
                            <select id="selPago" class="form-select">
                                <option value="contado">De Contado (Anual)</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="mensual">Mensual (En financiamiento)</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button onclick="calcularSeguro()" class="btn btn-primary w-100 fw-bold">
                                <i class="fas fa-calculator"></i> CALCULAR PRECIO
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mt-4 mt-lg-0 border-start">
                    <div id="resultadoSeguro" class="text-center p-3" style="display: none;">
                        <span class="text-muted small text-uppercase">Tu cotización:</span>
                        <h4 class="fw-bold mt-2" id="resNombre">...</h4>
                        <h2 class="display-4 fw-bold text-success my-0" id="resMonto">$0</h2>
                        <span class="badge bg-success mb-3" id="resPlazo">...</span>
                        <hr>
                        <small class="text-muted">Costo total anual: <strong id="resTotal">$0</strong></small>
                    </div>
                    
                    <div id="introSeguro" class="text-center py-4 text-muted opacity-50">
                        <i class="fas fa-file-contract fa-5x mb-3"></i>
                        <p class="mb-0 fw-bold">Selecciona opciones para cotizar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function calcularSeguro() {
        const aseguradora = document.getElementById('selAseguradora').value;
        const pago = document.getElementById('selPago').value;
        const divIntro = document.getElementById('introSeguro');
        const divRes = document.getElementById('resultadoSeguro');

        if (!aseguradora) {
            alert("Por favor selecciona una aseguradora.");
            return;
        }

        divIntro.innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Consultando Python...</p>';

        // LLAMADA AL SERVICIO (Puerto 5000)
        fetch(`http://127.0.0.1:5000/cotizar_seguro?aseguradora=${aseguradora}&tipo_pago=${pago}`)
            .then(response => response.json())
            .then(data => {
                divIntro.style.display = 'none';
                if (data.status === 'success') {
                    divRes.style.display = 'block';
                    document.getElementById('resNombre').innerText = data.aseguradora;
                    document.getElementById('resMonto').innerText = `$${data.monto}`;
                    document.getElementById('resPlazo').innerText = data.plazo;
                    document.getElementById('resTotal').innerText = `$${data.total_anual}`;
                } else {
                    alert(data.mensaje);
                    divIntro.style.display = 'block';
                    divIntro.innerHTML = '<i class="fas fa-exclamation-triangle fa-3x text-danger"></i><p>Error en consulta</p>';
                }
            })
            .catch(error => {
                console.error(error);
                divIntro.innerHTML = '<p class="text-danger">Error: No se pudo conectar a Python. ¿Está corriendo el script?</p>';
            });
    }
</script>

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