const urlIngresosEst = "http://127.0.0.1:8000/api/ingresos";
let ingresosEst = [];

function consultarIngresosEst() {
  fetch(urlIngresosEst)
    .then((res) => res.json())
    .then((body) => {
        ingresosEst = body.data;
        console.log(ingresosEst);
        cargarTablaIngresosEst();
    });
}

function cargarTablaIngresosEst() {
  const tbody = document
    .getElementById("contactosTable")
    .getElementsByTagName("tbody")[0];
  tbody.innerHTML = ingresosEst
    .map((item) => {
      let html = "<tr>";
      html += "   <td>" + item.codigoEstudiante + "</td>";
      html += "   <td>" + item.nombreEstudiante + "</td>";
      html += "   <td>" + item.idPrograma + "</td>";
      html += "   <td>" + item.fechaIngreso + "</td>";
      html += "   <td>" + item.horaIngreso + "</td>";
      html += "   <td>" + item.idResponsable + "</td>";
      html += "   <td>" + item.idSala + "</td>";
      html += "   <td>";
      html += `      <button onClick="modificarIngresosEst(${item.id})">Eliminar</button>`;
      html += `      <button onClick="eliminarIngresosEst(${item.id})">Eliminar</button>`;
      html += "   </td>";
      html += "</tr>";
      return html;
    })
    .join("");
}


consultarIngresosEst();

function registrarIngresosEst() {
  const form = document.forms["IngresosEstForm"];
  
  const ingresosEstData = {
    codigoEstudiante: form["codigoEstudiante"].value,
    nombreEstudiante: form["nombreEstudiante"].value,
    idPrograma: form["idPrograma"].value,
    fechaIngreso: form["fechaIngreso"].value,
    horaIngreso: form["horaIngreso"].value,
    idResponsable: form["idResponsable"].value,
    idSala: form["idSala"].value,
  };
  fetch(urlIngresosEst, {
    method: "post",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(ingresosEstData),
  })
    .then((resp) => resp.json())
    .then((body) => {
      const newIngresosEst = body.data;
      ingresosEst.push(newIngresosEst);
      cargarTablaIngresosEst();
    });
}

  function modificarIngresosEst(id) {
    const salida = document.getElementById("horasalida");
    salida.style.display = "block";
    salida.innerHTML = '<form name="salida" id="salida"><div><label>horaIngreso</label><input type="time" name="horaSalida"></div><div><button type="submit">Guardar</button></div></form>';
    const form = document.forms["salida"];
  
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const ingresosEstData = {
        horaSalida: form["horaSalida"].value,
      };
      fetch(urlIngresosEst + "/" + id, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(ingresosEstData),
      })
      .then((resp) => resp.json())
      .then((body) => {
        const newIngresosEst = body.data;
        ingresosEst.push(newIngresosEst);
        cargarTablaIngresosEst();
        salida.style.display = "none"; // Ocultar el formulario después de usarlo
      })
      .catch((error) => {
        console.error('Error al modificar ingreso:', error);
      });
    });
  }


function eliminarIngresosEst(id) {
  fetch(urlIngresosEst + "/" + id, { method: "DELETE" })
    .then((resp) => resp.json())
    .then((body) => {
      const msg = body.data;
      alert(msg);
      consultarIngresosEst();
    });
}

document.getElementById("IngresosEstForm").addEventListener("submit", (e) => {
  e.preventDefault();
  registrarIngresosEst();
});
