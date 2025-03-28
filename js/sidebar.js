function dropDown1() {
  const submenu = document.querySelector('#submenu');
  const arrow1 = document.querySelector('#arrow1');
  if (submenu && arrow1) {
    submenu.classList.toggle('hidden');
    arrow1.classList.toggle('rotate-0');
  }
}

function dropDown2() {
  const submenu2 = document.querySelector('#submenu2');
  const arrow2 = document.querySelector('#arrow2');
  if (submenu2 && arrow2) {
    submenu2.classList.toggle('hidden');
    arrow2.classList.toggle('rotate-0');
  }
}

function Openbar() {
  const sidebar = document.querySelector('.sidebar');
  if (sidebar) {
    sidebar.classList.toggle('left-[-300px]');
  }
}

window.addEventListener("scroll", function () {
  const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  const scrollToTopBtn = document.getElementById("scroll-to-top");
  if (scrollToTopBtn) {
    if (scrollTop > 1) {
      scrollToTopBtn.classList.remove("d-none");
    } else {
      scrollToTopBtn.classList.add("d-none");
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((button) => {
    button.addEventListener("mouseenter", () => {
      button.classList.add("btn-hover");
    });
    button.addEventListener("mouseleave", () => {
      button.classList.remove("btn-hover");
    });
  });

  const submitForm = document.getElementById('submitForm');
  if (submitForm) {
    submitForm.addEventListener('submit', (event) => {
      event.preventDefault();

      setTimeout(() => {
        const registroAnimacion = document.getElementById('registro-animacion');
        if (registroAnimacion) {
          registroAnimacion.classList.add('show');
        }
      }, 1000);

      submitForm.classList.add('d-none');
      const mensajeExito = document.getElementById('mensajeExito');
      if (mensajeExito) {
        mensajeExito.classList.remove('d-none');
      }
    });
  }
});
