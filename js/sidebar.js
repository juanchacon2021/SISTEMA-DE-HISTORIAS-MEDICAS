function dropDown1() {
    document.querySelector('#submenu').classList.toggle('hidden')
    document.querySelector('#arrow1').classList.toggle('rotate-0')
  }
  dropDown1()

  function dropDown2() {
    document.querySelector('#submenu2').classList.toggle('hidden')
    document.querySelector('#arrow2').classList.toggle('rotate-0')
  }
  dropDown2()

function Openbar() {
    document.querySelector('.sidebar').classList.toggle('left-[-300px]')
}

window.addEventListener("scroll", function () {
  const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  const scrollToTopBtn = document.getElementById("scroll-to-top");
  if (scrollTop > 1) {
    scrollToTopBtn.classList.remove("d-none");
  } else {
    scrollToTopBtn.classList.add("d-none");
  }
});

$(document).ready(function() {
  $(".btn").hover(function() {
    $(this).addClass("btn-hover");
  }, function() {
    $(this).removeClass("btn-hover");
  });
});

const submitForm = document.getElementById('submitForm');
submitForm.addEventListener('submit', (event) => {
  event.preventDefault();

  setTimeout(() => {
    const registroAnimacion = document.getElementById('registro-animacion');
    registroAnimacion.classList.add('show');
  }, 1000);

  submitForm.classList.add('d-none');
  const mensajeExito = document.getElementById('mensajeExito');
  mensajeExito.classList.remove('d-none');
});
