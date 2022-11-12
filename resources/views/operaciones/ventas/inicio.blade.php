@extends('layouts.master')

@section('titulo', 'Ventas')

@section('contenido')
    <div class="row p-md-5">
        <div class="d-flex mb-4">
            <a href="{{ route('operacion', 12) }}" class="btn btn-lg btn-primary">
                <i class="fa-solid fa-circle-plus me-3"></i>
                Nueva venta consumidor final
            </a>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Listado de ventas</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-lg table-striped table-hover" id="paginacion">
                                <thead class="bg-rojo">
                                    <tr>
                                        <th class="text-white">Cliente</th>
                                        <th class="text-white">Total ($)</th>
                                        <th class="text-white">Total + IVA ($)</th>
                                        <th class="text-white">Fecha y hora</th>
                                        <th class="text-white">Comentarios</th>
                                        <th class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->elcliente->nombre }}</td>
                                            <td>${{ number_format($venta->total, 2) }} </td>
                                            <td>${{ number_format($venta->total_iva, 2) }}</td>
                                            <td>{{ date_format(date_create($venta->fecha_hora), "d/m/Y - H:i:s") }}</td>
                                            <td>{{ $venta->comentarios == '' ? '-' : $venta->comentarios }}</td>
                                            <td>
                                                <a class="btn btn-success" href="{{ route('ventas.detalle', $venta->id_venta) }}">
                                                    <i class="fa-solid fa-circle-info me-1"></i>
                                                    Detalles
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

