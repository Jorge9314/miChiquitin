var lista = []
var precio = 0.0
$('#agregar').click(function(){
	var cantidad = $('[name="cantidad"]').val();
	var producto = $('[name="id_producto"]').val();
	var i = 0;
	var pertenece = 0;
	while (i < lista.length){
		if(lista[i][0] == producto){
			pertenece = 1;
			break;
		}
 		i = i+1;
	}
	if (pertenece == 0) {
		if(cantidad != 0){

			$.getJSON('/Facturacion/compra/' + cantidad + '/' + producto, function(result){
				console.log("Alerta!!", result);
				if (result== false) {
					alert("no es un articulo");
				}
				else{

					var arr = Object.values(result);
					lista.push(arr);
					console.log(lista);
					var idCantidad = result.id_producto.toString();
					idCantidad = idCantidad.concat("cantidad");
					var idTotal = result.id_producto.toString();
					idTotal = idTotal.concat("total");
					var idPendiente = result.id_producto.toString();
					idPendiente = idPendiente.concat("pendiente");
					$('#body').append('<tr id = '+ result.id_producto +'><td>'+result.id_producto+'</td><td>' + result.descripcion + '</td><td id= '+ idCantidad +'>' + result.cantidad + '</td><td>' + result.unitario + '</td><td id = '+ idTotal +'>' + result.total + '</td><td id = '+ idPendiente +'>' + result.pendiente + '</td><td><input type="button" class= "btn btn-primary" onclick="eliminarFila('+ result.id_producto +');" value="-"></td></tr>');
					precio = parseInt(precio + parseFloat(result.total));
					descuentos = 0;
					var plan = $('[name="plan_pago"]').val();
					if(plan == "Efectivo"){
						descuentos = parseInt(precio - (precio * 0.05) + (precio * 0.19));
					}
					if(plan == "Credito"){
						descuentos = parseInt(precio + (precio * 0.05) + (precio * 0.19));
					}
					$('[name="numero"]').val(descuentos);
					document.getElementById("numero").innerHTML = descuentos.toString();
				}
			})
		}

	}
	else{

		if (cantidad != 0){

			var idCantidad = lista[i][0].toString();
			idCantidad = idCantidad.concat("cantidad");
			lista[i][2] = cantidad;
			document.getElementById(idCantidad).innerHTML = cantidad;
			var idTotal = lista[i][0].toString();
			idTotal = idTotal.concat("total");
			precio = parseInt(precio - lista[i][4]);
			lista[i][4] = cantidad * lista[i][3];
			precio = parseInt(precio + lista[i][4]);
			document.getElementById(idTotal).innerHTML = lista[i][4];
			descuentos = 0;
			var plan = $('[name="plan_pago"]').val();
			if(plan == "Efectivo"){
				descuentos = parseInt(precio - (precio * 0.05) + (precio * 0.19));
			}
			if(plan == "Credito"){
				descuentos = parseInt(precio + (precio * 0.05) + (precio * 0.19));
			}
			$('[name="numero"]').val(descuentos);
			document.getElementById("numero").innerHTML = descuentos.toString();
			pendiente = lista[i][6] - cantidad;
			if (lista[i][6] < 0) {
        		pendiente = -cantidad;
      		}
      		else{

        		pendiente = lista[i][6] - cantidad;
      		}
			if (pendiente > 0) {
				pendiente = 0;
			}
			var idPendiente = lista[i][0].toString();
			idPendiente = idPendiente.concat("pendiente");
			document.getElementById(idPendiente).innerHTML = pendiente;
	 		console.log(lista);	
		}
	}
})

$('#generar').click(function(){
	$('[name="lista[]"]').val(lista);
	$('[name="total"]').val(precio);
	$('#generarFactura').submit();
})


function eliminarFila(index) {
	var i = 0
		while (i < lista.length){
			if(lista[i][0] == index){
				precio = parseInt(precio - lista[i][4]);
				document.getElementById("numero").innerHTML = precio.toString();
				lista.splice(i, 1);
				break
			}
	 		i = i+1;
		}
		$('#' + index).remove();
	}