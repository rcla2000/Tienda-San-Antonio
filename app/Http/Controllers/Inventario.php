<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedore;
use App\Models\CatGiro;
use App\Models\CateProducto;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Bodega;
use App\Models\TInventario;
use Exception;

class Inventario extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addProv(Request $request)
    {   
        $of = isset($request->oferente) ? 1 : 0;
        $sb = isset($request->sumBienes) ? 1: 0;
        $ps = isset($request->presServ) ? 1 : 0;
        $cont = isset($request->contratista) ? 1 : 0;

        $proveedor = new Proveedore();
        $proveedor->razon_social = $request->razonSocial;
        $proveedor->giro_categoria = $request->giro;
        $proveedor->credito_fiscal = $request->creditoFiscal;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = $request->direccion;
        $proveedor->oferente = $of;
        $proveedor->suministrante_bienes = $sb; 
        $proveedor->prestador_servicios = $ps;
        $proveedor->contratista = $cont;
        $proveedor->save();

        return back()->with('mensaje', 'El proveedor '.$request->razonSocial.' ha sido agregado exitosamente.');
    }



    public function detaProv($idProv)
    {
        $proveedor = Proveedore::findOrFail($idProv);
        $giro = CatGiro::all();
        return view('inventario.proveedores.editar',compact('proveedor','giro','idProv'));
    }

    public function editProv(Request $request,$idProv)
    {
        $of = isset($request->oferente) ? 1 : 0;
        $sb = isset($request->sumBienes) ? 1: 0;
        $ps = isset($request->presServ) ? 1 : 0;
        $cont = isset($request->contratista) ? 1 : 0;

        $proveedor = Proveedore::findOrFail($idProv);
        $proveedor->razon_social = $request->razonSocial;
        $proveedor->giro_categoria = $request->giro;
        $proveedor->credito_fiscal = $request->creditoFiscal;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = $request->direccion;
        $proveedor->estado = $request->estado;
        $proveedor->oferente = $of;
        $proveedor->suministrante_bienes = $sb; 
        $proveedor->prestador_servicios = $ps;
        $proveedor->contratista = $cont;
        $proveedor->save();

        return back()->with('mensaje', 'Proveedor ' . $request->razonSocial . ' ha sido actualizado.');
    }


    function addProd(Request $request)
    {
        $producto  = new Producto();
        $producto->producto = $request->producto;
        $producto->precio = $request->precio;
        $producto->descuento = ($request->descuento / 100);
        $producto->proveedor = $request->proveedor;
        $producto->descripcion = $request->descripcion;
        $producto->categoria = $request->categoria;
        $producto->cod_bar = $request->cod_bar;
        $producto->save();

        return back()->with('mensaje', 'Producto '. $request->producto .' agregado éxitosamente');
    }

    public function detaProd($id)
    {
       $producto =  Producto::findOrFail($id);
       $categorias = CateProducto::all();
       $proveedores = Proveedore::all();

       return view('inventario.productos.editar', compact('id','producto','categorias','proveedores'));
    }

    public function modProd(Request $request,$id)
    {
        $producto =  Producto::findOrFail($id);
        $producto->producto = $request->producto;
        $producto->precio = $request->precio;
        $producto->descuento = ($request->descuento / 100);
        $producto->proveedor = $request->proveedor;
        $producto->descripcion = $request->descripcion;
        $producto->categoria = $request->categoria;
        $producto->cod_bar = $request->cod_bar;
        $producto->save();
        return back()->with('mensaje', 'Información de producto '. $request->producto .' actualizada éxitosamente');


    }
    public function addCate(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|min:3'
        ]);
        
        $categoria =  new CateProducto();
        $categoria->categoria =  $request->categoria;
        $categoria->save();
    
        return back()->with('mensaje', 'Categoría agregada éxitosamente');
    }


    public function detaCate($id)
    {
        $categoria = CateProducto::findOrFail($id);
        return view('inventario.categorias-productos.editar',compact('categoria','id'));
    }

    public function modCate(Request $request,$id)
    {
        $categoria =  CateProducto::findOrFail($id);
        $categoria->categoria =  $request->categoria;
        $categoria->save();
    
        return back()->with('mensaje', 'Categoría agregada éxitosamente');
    }

    public function eliminarCategoria($id) {
        $categoria = CateProducto::findOrFail($id);

        try {
            $categoria = CateProducto::destroy($id);
            return back()->with('mensaje', 'Categoría eliminada exitosamente.');
        } catch (Exception $e) {
            return back()->with('mensaje', 'No se pudo eliminar la categoría.');
        }
    }

    public function addCliente(Request $request)
    {
        $cliente = new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;

        if (isset($request->chk_credito)) {
            $cliente->tipo_cliente = 2;
            $cliente->credito_fiscal = $request->credito_fiscal;
        } else {
            $cliente->tipo_cliente = 1;
            $cliente->dui = $request->dui;
        }

        $cliente->save();
        return back()->with('mensaje', 'Cliente agregado éxitosamente');

    }

    public function eliminarCliente($id) {
        $cliente = Cliente::findOrFail($id);

        try {
            $cliente = Cliente::destroy($id);
            return back()->with('mensaje', 'Cliente eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->with('mensaje', 'No se pudo eliminar el cliente.');
        }
    }

    public function detaCliente($id)
    {
        $cliente =  Cliente::findOrFail($id);
        return view('inventario.clientes.editar',compact('cliente','id'));
    }

    public function modCliente(Request $request,$id)
    {
        $cliente =  Cliente::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;

        if (isset($request->chk_credito)) {
            $cliente->tipo_cliente = 2;
            $cliente->credito_fiscal = $request->credito_fiscal;
            $cliente->dui = '';
        } else {
            $cliente->tipo_cliente = 1;
            $cliente->dui = $request->dui;
            $cliente->credito_fiscal = '';
        }
        
        $cliente->estado = $request->estado;
        $cliente->save();

        return back()->with('mensaje', 'Cliente actualizado éxitosamente');
    }


    public function addBodega(Request $request)
    {
        $bodega = new Bodega();

        $bodega->bodega  = $request->nombre;
        $bodega->telefono = $request->telefono;

        $bodega->direccion = $request->direccion;
        $bodega->save();
        
        return back()->with('mensaje', 'Bodega agregada éxitosamente');



    }

    public function detaBodega($id)
    {
        $bodega =  Bodega::findOrFail($id);

        return view('inventario.bodegas.edit-bodega',compact('id','bodega'));
    }

    public function editBodega(Request $request ,$id)
    {
        $bodega =  Bodega::findOrFail($id);

        $bodega->bodega  = $request->nombre;
        $bodega->telefono = $request->telefono;

        $bodega->direccion = $request->direccion;
        $bodega->estado = $request->estado;
        $bodega->save();
        return back()->with('mensaje', 'Bodega actualizada éxitosamente');

        
    }

    public function addInventario(Request $request)
    {
        $idProd = $request->producto;
        $idBod = $request->ubicacion;
        $contar =  TInventario::where('producto',$idProd)->where('ubicacion',$idBod)->count();
        if ( $contar > 0) {
            
            $actualizar =     TInventario::where('producto',$idProd)->where('ubicacion',$idBod)->firstOrFail(); 
             $actualizar->cantidad = $request->cantidad;
             $actualizar->unidad_medida = $request->uMedida;
            $actualizar->save();
            return back()->with('mensaje', 'Actualización de inventario éxitosa');


        } else {

            $inventario  = new TInventario();
            $inventario->producto  = $idProd;
            $inventario->cantidad = $request->cantidad;
            $inventario->unidad_medida = $request->uMedida;
            $inventario->ubicacion= $idBod;
            $inventario->save();
    
            return back()->with('mensaje', 'Producto agregado a inventario éxitosamente');
    
        }
        




      


    }







}
