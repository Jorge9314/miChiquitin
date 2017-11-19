<?php

namespace App\Http\Controllers\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuarios\User;
use App\Models\Cartera\Deuda;
use App\Models\Facturacion\Factura;
use Carbon\Carbon;

class MetodoDePago extends Controller
{

  public static function compraEfectivo($valorTotal) {
    $totalPagar = $valorTotal - ($valorTotal*0.05);
    return $totalPagar + ($totalPagar * 0.19);
  }

  public static function numeroCuotas($N, $valorTotalCompra) {
    $valorTotalCompra = $valorTotalCompra + ($valorTotalCompra * 0.05) + ($valorTotalCompra * 0.19);
    $valorPagar = $valorTotalCompra * 0.10;
    return  [
			'valorCuota' => ($valorTotalCompra - $valorPagar) / $N,
      'valorCompra'=> $valorTotalCompra - $valorPagar,
			'valorPagar' => $valorPagar
		];
  }

  public static function compraCredito($idCliente, $valorTotalPago, $N) {
    $cliente = User::find($idCliente);
    $valorTotalPago = (int)$valorTotalPago;
    $N = (int)$N;

    //dd($credito_actual);
    $credito_actual = $cliente->credito_actual;
    $credito_maximo = $cliente->credito_maximo;
    //echo "Credito actual : ".$credito_actual;
    //echo "<br>Valor total pago : ".$valorTotalPago;

    if ($credito_actual == $credito_maximo) return [];

    $obj = self::numeroCuotas($N, $valorTotalPago);
        if (($credito_actual + $obj["valorCompra"]) > $credito_maximo) return [];
    else {
            //echo "<br>Valor total a pagar : ".$obj["valorPagar"];
            $nuevo_credito_actual = $credito_actual + $obj["valorCompra"];

            $cliente->credito_actual = $nuevo_credito_actual;
            $cliente->save();

            return $obj;

    }
	}
}
