function buscarInfoEmpleado(id_tbl_empleados_hraes){
    let nombreResult = document.getElementById("nombreResult");
    let numEmpleadoResult = document.getElementById("numEmpleadoResult");
    let curpResult = document.getElementById("curpResult");
    let rfcResult = document.getElementById("rfcResult");
    
    $.post('../../../../App/Controllers/Central/EmpleadoC/InformacionC.php', {
        id_tbl_empleados_hraes:id_tbl_empleados_hraes
    },
        function (data) {
            let jsonData = JSON.parse(data);
            let nombre = jsonData.nombre;
            let curp = jsonData.curp;
            let rfc = jsonData.rfc;
            let noEmpleado = jsonData.noEmpleado;

            nombreResult.textContent = nombre;
            numEmpleadoResult.textContent = noEmpleado;
            curpResult.textContent = curp;
            rfcResult.textContent = rfc;
        }
    );
}

function iniciarEscolaridad(){
    buscarEstudio();//ok
    buscarCedula();//ok
    buscarEspecialidad();//ok
}

function iniciarMediosContacto(){
    //mensajetextLar('El usuario debe verificar que todos los campos estén completos, incluyendo el domiclio (código postal fiscal) y la forma de pago (cuenta clabe).');
    buscarNumTelefonico();//ok
    buscarCorreo(); //ok
    buscarDependiente();//ok
    buscarEmergencia();//ok
}

function iniciarMovimiento(){
    buscarMovimiento();//ok
   // buscarJefe();
    iniciarAdicional();
}

function iniciarPersonalBancario(){
    buscarFormaPago(); // ok
    buscarJefe(); //ok
    iniciarDomicilio(); //ok
    buscarCapacidadesDif();//ok
}

function iniciarProgramas(){
    buscarJuguete(); // pendiente
    //iniciarCampos();
}

function iniciarIncidencias(){
    buscarRetardo();//ok
}

function iniciarPercepciones(){
    buscarPercepcion();//ok
    buscarQuinquenio();
}

function convertirAMayusculas(event, inputId) {
    let inputElement = document.getElementById(inputId);
    let texto = event.target.value;
    let textoEnMayusculas = texto.toUpperCase();
    inputElement.value = textoEnMayusculas;
  }

  function validarNumero(input) {
    input.value = input.value.replace(/[^\d]/g, '');
  }

  function mensajetextLar(text){
    Swal.fire({
        title: "USUARIO",
        text: text,
        icon: "warning"
      });
}