// Funcionalidad del menú hamburguesa
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            menuToggle.innerHTML = navMenu.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
    }
    
    // Cerrar menú al hacer clic en un enlace
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navMenu && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                if (menuToggle) {
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            }
        });
    });
    
    // Formulario de suscripción mejorado
    const formSuscripcion = document.getElementById('formSuscripcion');
    if (formSuscripcion) {
        formSuscripcion.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSubmit = document.getElementById('btnSuscripcion');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const mensajeDiv = document.getElementById('mensajeSuscripcion');
            
            // Obtener valores
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const comentario = document.getElementById('comentario').value.trim();
            
            // Validación básica en el cliente
            if (nombre.length < 2) {
                mostrarMensaje('Por favor ingresa un nombre válido (mínimo 2 caracteres)', 'error');
                return;
            }
            
            if (!isValidEmail(email)) {
                mostrarMensaje('Por favor ingresa un email válido', 'error');
                return;
            }
            
            // Mostrar estado de carga
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            btnSubmit.disabled = true;
            
            try {
                // Crear FormData
                const formData = new FormData();
                formData.append('nombre', nombre);
                formData.append('email', email);
                formData.append('comentario', comentario);
                
                // Enviar petición
                const response = await fetch('config/procesar_suscripcion.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    formSuscripcion.reset();
                } else {
                    mostrarMensaje(data.message, 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión. Por favor, intenta nuevamente.', 'error');
            } finally {
                // Restaurar botón
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
                btnSubmit.disabled = false;
            }
        });
        
        // Función para mostrar mensajes
        function mostrarMensaje(mensaje, tipo) {
            const mensajeDiv = document.getElementById('mensajeSuscripcion');
            
            // Estilos según el tipo
            const estilos = {
                success: {
                    backgroundColor: '#d4edda',
                    color: '#155724',
                    borderColor: '#c3e6cb'
                },
                error: {
                    backgroundColor: '#f8d7da',
                    color: '#721c24',
                    borderColor: '#f5c6cb'
                },
                warning: {
                    backgroundColor: '#fff3cd',
                    color: '#856404',
                    borderColor: '#ffeaa7'
                }
            };
            
            // Aplicar estilos
            Object.assign(mensajeDiv.style, {
                display: 'block',
                padding: '15px',
                borderRadius: '5px',
                marginTop: '15px',
                border: '1px solid',
                ...estilos[tipo] || estilos.info
            });
            
            mensajeDiv.innerHTML = `<i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${mensaje}`;
            
            // Ocultar mensaje después de 5 segundos (excepto errores)
            if (tipo === 'success') {
                setTimeout(() => {
                    mensajeDiv.style.display = 'none';
                }, 5000);
            }
        }
        
        // Función para validar email
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Validación en tiempo real
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    this.style.borderColor = '#dc3545';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                } else {
                    this.style.borderColor = '#ddd';
                    this.style.boxShadow = 'none';
                }
            });
        }
    }
    
    // Scroll suave para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#' || targetId === '#!') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Cambiar clase activa en navegación al hacer scroll
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY + 100;
        const navLinks = document.querySelectorAll('.nav-link');
        
        document.querySelectorAll('section').forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });
    
    // Asegurar que las imágenes tengan fallback
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="%23f0f0f0"/><text x="200" y="150" text-anchor="middle" font-family="Arial" font-size="20" fill="%23999">Imagen no disponible</text></svg>';
        };
    });
});