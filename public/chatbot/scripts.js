      // Navegação móvel
      document.addEventListener("DOMContentLoaded", function () {
        const hamburger = document.querySelector(".hamburger");
        const navMenu = document.querySelector(".nav-menu");

        hamburger.addEventListener("click", function () {
          hamburger.classList.toggle("active");
          navMenu.classList.toggle("active");
        });

        // Fechar menu ao clicar num link
        document.querySelectorAll(".nav-menu li a").forEach((link) => {
          link.addEventListener("click", () => {
            hamburger.classList.remove("active");
            navMenu.classList.remove("active");
          });
        });
      });

      // Scroll suave para as secções
      document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute("href"));
          if (target) {
            const offsetTop = target.offsetTop - 80; // Compensar altura do header fixo
            window.scrollTo({
              top: offsetTop,
              behavior: "smooth",
            });
          }
        });
      });

      // Formulário de contacto
      document
        .getElementById("contactForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Simular envio de formulário
          const formData = new FormData(this);
          const formObject = {};
          formData.forEach((value, key) => {
            formObject[key] = value;
          });

          // Mostrar mensagem de sucesso
          showNotification(
            "Mensagem enviada com sucesso! Entraremos em contacto brevemente.",
            "success"
          );

          // Limpar formulário
          this.reset();
        });

      // Botões CTA
      document.querySelectorAll(".btn").forEach((button) => {
        button.addEventListener("click", function (e) {
          const buttonText = this.textContent.trim();

          if (buttonText.includes("Conhecer Cursos")) {
            e.preventDefault();
            document.querySelector("#courses").scrollIntoView({
              behavior: "smooth",
              block: "start",
            });
          } else if (
            buttonText.includes("Falar com a Isis") ||
            buttonText.includes("Isis")
          ) {
            e.preventDefault();
            showChatbotInfo();
          }
        });
      });

      // Função para mostrar informações sobre o chatbot
      function showChatbotInfo() {
        showNotification(
          "🤖 A Isis é a assistente virtual do ISTEC Porto. Em breve estará disponível para responder às suas dúvidas sobre cursos, candidaturas e muito mais!",
          "info",
          5000
        );
      }

      // Sistema de notificações
      function showNotification(message, type = "info", duration = 3000) {
        // Remover notificações existentes
        const existingNotifications =
          document.querySelectorAll(".notification");
        existingNotifications.forEach((notification) => notification.remove());

        // Criar nova notificação
        const notification = document.createElement("div");
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;

        // Estilos da notificação
        notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${
          type === "success"
            ? "#10b981"
            : type === "error"
            ? "#ef4444"
            : "#3b82f6"
        };
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    `;

        // Adicionar estilos de animação se não existirem
        if (!document.querySelector("#notification-styles")) {
          const styles = document.createElement("style");
          styles.id = "notification-styles";
          styles.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .notification-content {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 15px;
            }
            .notification-close {
                background: none;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
                padding: 0;
                line-height: 1;
                opacity: 0.8;
            }
            .notification-close:hover {
                opacity: 1;
            }
        `;
          document.head.appendChild(styles);
        }

        document.body.appendChild(notification);

        // Adicionar evento para fechar
        const closeButton = notification.querySelector(".notification-close");
        closeButton.addEventListener("click", () => {
          notification.remove();
        });

        // Auto-remover após o tempo especificado
        setTimeout(() => {
          if (notification.parentNode) {
            notification.style.animation = "slideIn 0.3s ease-out reverse";
            setTimeout(() => notification.remove(), 300);
          }
        }, duration);
      }

      // Animações ao fazer scroll
      function animateOnScroll() {
        const elements = document.querySelectorAll(".course-card, .stat-item");
        const windowHeight = window.innerHeight;

        elements.forEach((element) => {
          const elementTop = element.getBoundingClientRect().top;
          const elementVisible = 150;

          if (elementTop < windowHeight - elementVisible) {
            element.classList.add("animate");
          }
        });
      }

      // Adicionar estilos para animações
      const animationStyles = document.createElement("style");
      animationStyles.textContent = `
    .course-card, .stat-item {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .course-card.animate, .stat-item.animate {
        opacity: 1;
        transform: translateY(0);
    }
`;
      document.head.appendChild(animationStyles);

      // Event listeners para scroll
      window.addEventListener("scroll", animateOnScroll);
      window.addEventListener("load", animateOnScroll);

      // Destacar link ativo na navegação
      function updateActiveNavLink() {
        const sections = document.querySelectorAll("section[id]");
        const navLinks = document.querySelectorAll('.nav-menu a[href^="#"]');

        let currentSection = "";

        sections.forEach((section) => {
          const sectionTop = section.offsetTop - 100;
          const sectionHeight = section.clientHeight;

          if (
            window.scrollY >= sectionTop &&
            window.scrollY < sectionTop + sectionHeight
          ) {
            currentSection = section.getAttribute("id");
          }
        });

        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === `#${currentSection}`) {
            link.classList.add("active");
          }
        });
      }

      // Adicionar estilos para link ativo
      const navStyles = document.createElement("style");
      navStyles.textContent = `
    .nav-menu a.active {
        color: #ffd700 !important;
        position: relative;
    }
    
    .nav-menu a.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 2px;
        background: #ffd700;
    }
`;
      document.head.appendChild(navStyles);

      window.addEventListener("scroll", updateActiveNavLink);
      window.addEventListener("load", updateActiveNavLink);

      // Função para simular clique em "Conhecer Cursos"
      function scrollToCourses() {
        document.querySelector("#courses").scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }

      // Função para preparar integração do chatbot (placeholder)
      function initializeChatbot() {
        console.log("Chatbot será integrado aqui");
        // Esta função será usada para integrar o chatbot do n8n
        // Por agora, apenas mostra uma mensagem informativa
      }

      // Função utilitária para validar email
      function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }

      // Melhorar validação do formulário
      document
        .getElementById("contactForm")
        .addEventListener("input", function (e) {
          const field = e.target;

          if (field.type === "email" && field.value) {
            if (isValidEmail(field.value)) {
              field.style.borderColor = "#10b981";
            } else {
              field.style.borderColor = "#ef4444";
            }
          }

          if (field.required && field.value.trim()) {
            field.style.borderColor = "#10b981";
          }
        });

      // Inicialização quando a página carrega
      document.addEventListener("DOMContentLoaded", function () {
        console.log("Site do ISTEC Porto carregado com sucesso!");
        console.log("Pronto para integração do chatbot n8n");

        // Simular carregamento
        setTimeout(() => {
          animateOnScroll();
          updateActiveNavLink();
        }, 100);
      });