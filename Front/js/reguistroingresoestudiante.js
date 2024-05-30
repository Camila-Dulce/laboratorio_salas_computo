const urlIngresosEst = "http://127.0.0.1:8000/api/ingreso";
let contactos = [];

function consultarIngresosEst() {
  fetch(urlIngresosEst)
    .then((res) => res.json())
    .then((body) => {
        ingresosEst= body.data;
        console.log(ingresosEst);
      cargarTablaIngresosEst();
    });
}

function cargarTablaIngresosEst() {
  const tbody = document
    .getElementById("contactosTable")
    .getElementsByTagName("tbody")[0];
  tbody.innerHTML = IngresosEst
    .map((item) => {
      let html = "<tr>";
      html += "   <td>" + item.codigoEstudiante + "</td>";
      html += "   <td>" + item.nombreEstudiante + "</td>";
      html += "   <td>" + item.idPrograma+ "</td>";
      html += "   <td>" + item.fechaIngreso + "</td>";
      html += "   <td>" + item.horaIngreso + "</td>";
      html += "   <td>" + item.idResponsable + "</td>";
      html += "   <td>" + item.idSala + "</td>";
      html += "   <td>";
      html += "       <button>Modificar</button>";
      html +=
        '       <button onClick="eliminarContacto(' +
        item.id +
        ')">Eliminar</button>';
      html += "   </td>";
      html += "</tr>";
      return html;
    })
    .join("");
}

consultarIngresosEst();

function registrarIngresosEst() {
  const form = document.forms["ingresosEstForm"];
  const ingresosEst = {
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
    body: JSON.stringify(ingresosEst),
  })
    .then((resp) => resp.json())
    .then((body) => {
      const newingresosEst = body.data;
      IngresosEst.push(newingresosEst);
      cargarTablaIngresosEst();
      //consultarContactos();
    });
}

function eliminarIngresosEst(id) {
  fetch(urlIngresosEst + "/" + id, { method: "delete" })
  .then(resp=>resp.json())
  .then(body=>{
    const msg = body.data;
    alert(msg);
    consultarIngresosEst();
  });
}

document.getElementById("IngresosEstForm").addEventListener("submit", (e) => {
  e.preventDefault();
  registrarIngresosEst();
});
