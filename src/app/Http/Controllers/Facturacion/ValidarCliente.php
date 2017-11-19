<?php
namespace App\Http\Controllers\Facturacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuarios\User;

class ValidarCliente extends Controller
{
	public function validar($id_cliente, $id_vendedor) {
		$cliente = User::find($id_cliente);
		$vendedor = User::find($id_vendedor);
		if ($cliente == null or $vendedor == null) return false;

		if ($vendedor->tipo_rol == "vendedor" and $cliente->tipo_rol != "vendedor") {
			return true;
		} else if ($vendedor->tipo_rol == "root" || $vendedor->tipo_rol == "administrador") { // puede venderle a todos
			return true;
		} else return false;
	}

	public function index() {
		return view('Facturacion.index');
	}

	public function intermediar(Request $request) {
		$id_cliente = $request->id_cliente;
		// dd($id_cliente);
		$id_vendedor = $request->id_vendedor;
		// dd($id_vendedor);
		if($request->metodo == 'Efectivo'){
      $cuotas = 0;
		} else{
      $cuotas = $request->cuotas;
    }
		if (self::validar($id_cliente, $id_vendedor)) {
		// if (true) {
			return view('Facturacion.compra')->with('id_cliente',$id_cliente)
															 ->with('metodo',$request->metodo)
															 ->with('cuotas_credito',$cuotas);
		} else {
			return view('Facturacion.error')->with('error', "Error en la identificación del cliente");
		}
	}

}
