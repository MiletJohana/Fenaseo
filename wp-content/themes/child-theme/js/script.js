const steps_progress = document.querySelectorAll(".progress-step .fas");
const escalas = document.querySelectorAll("#container-escalas input");
const progress = document.getElementById('progress');
let currentStep = 1;
let cant = 0;
let porcentaje = 0;

function precioProducto(cantidad, producto){
  refresh();
  let progresoAcumulado = 0; 
  let porcentaje = 0;
  let volEscalaSiguiente = 0;
  let volEscalaActual = 0;
  let precio = 0;
  console.log(escalas);

  for (let index = 0; index < escalas.length-1; index++) {
    if(cantidad > 0){
      volEscalaActual = escalas[index].dataset.vol_min;
      precio = escalas[index].dataset.precio;
      volEscalaSiguiente = escalas[index + 1].dataset.vol_min;

      if (cantidad >= parseInt(volEscalaSiguiente)) {
        // Si la cantidad seleccionada supera esta escala, llenamos completamente esta sección
        steps_progress[index+1].classList.add('active');
        progresoAcumulado += 30;
        console.log(1);
      } else if (cantidad >= parseInt(volEscalaActual)) {
        // Si la cantidad está dentro de este rango, calculamos el porcentaje parcial
        porcentaje =
          ((cantidad - volEscalaActual) / (volEscalaSiguiente - volEscalaActual)) * 100;
        porcentaje = 30*(porcentaje/100);
        progresoAcumulado = progresoAcumulado + porcentaje;
       
        break;
      }
    } else {
      porcentaje = 0;
    }
  }

  if(precio != 0){
    document.getElementById('precio_x_escala').innerHTML = '$ ' + formatearNumero(precio);
    document.getElementById('price_default').classList.add('precio-anterior');
  } else {
    document.getElementById('precio_x_escala').innerHTML = '';
    document.getElementById('price_default').classList.remove('precio-anterior');
  }

  console.log(porcentaje)
  console.log(progresoAcumulado)
  progress.style.width = `${Math.min(progresoAcumulado, 100)}%`;
}

function formatearNumero(numero){
  return new Intl.NumberFormat("es-CL").format(numero);
}

function refresh() {
  steps_progress.forEach((step, index) => {
    if(index != 0){
      step.classList.remove('active');
    }
  });
}

