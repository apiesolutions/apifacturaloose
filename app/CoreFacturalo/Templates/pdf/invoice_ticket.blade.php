@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
@endphp
<html>
<head>
    <title>{{ $document_number }}</title>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>

@if($company->logo)
    <div class="text-center company_logo_box pt-5">
        <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo_ticket contain">
    </div>
@else
    <div class="text-center company_logo_box pt-5">
        <img src="{{ asset('logo/logo.jpg') }}" class="company_logo_ticket contain">
    </div>
@endif
<table class="full-width">
    <tr>
        <td class="text-center"><h4>{{ $company->name }}</h4></td>
    </tr>
    <tr>
        <td class="text-center"><h5>{{ 'RUC '.$company->number }}</h5></td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->address !== '-')? $establishment->address : '' }}</td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->email !== '-')? $establishment->email : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pb-3">{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pt-3 border-top"><h4>{{ $document->document_type->description }}</h4></td>
    </tr>
    <tr>
        <td class="text-center pb-3 border-bottom"><h3>{{ $document_number }}</h3></td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td width="45%" class="pt-3"><p class="desc">Fecha de emisión:</p></td>
        <td width="" class="pt-3"><p class="desc">{{ $document->date_of_issue->format('Y-m-d') }}</p></td>
    </tr>

    @isset($invoice->date_of_due)
    <tr>
        <td><p class="desc">Fecha de vencimiento:</p></td>
        <td><p class="desc">{{ $invoice->date_of_due->format('Y-m-d') }}</p></td>
    </tr>
    @endisset

    <tr>
        <td class="align-top"><p class="desc">Cliente:</p></td>
        <td><p class="desc">{{ $customer->name }}</p></td>
    </tr>
    <tr>
        <td><p class="desc">{{ $customer->identity_document_type->description }}:</p></td>
        <td><p class="desc">{{ $customer->number }}</p></td>
    </tr>
    @if ($customer->address !== '')
        <tr>
            <td class="align-top"><p class="desc">Dirección:</p></td>
            <td><p class="desc">{{ $customer->address }}</p></td>
        </tr>
    @endif
    @if ($document->purchase_order)
        <tr>
            <td><p class="desc">Orden de Compra:</p></td>
            <td><p class="desc">{{ $document->purchase_order }}</p></td>
        </tr>
    @endif
    @if ($document->guides)
        @foreach($document->guides as $guide)
            <tr>
                <td><p class="desc">{{ $guide->document_type_id }}</p></td>
                <td><p class="desc">{{ $guide->number }}</p></td>
            </tr>
        @endforeach
    @endif
</table>
<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom desc-9 text-left">CANT.</th>
        <th class="border-top-bottom desc-9 text-left">UNIDAD</th>
        <th class="border-top-bottom desc-9 text-left">DESCRIPCIÓN</th>
        <th class="border-top-bottom desc-9 text-left">P.UNIT</th>
        <th class="border-top-bottom desc-9 text-left">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center desc-9 align-top">{{ $row->quantity }}</td>
            <td class="text-center desc-9 align-top">{{ $row->item->unit_type_id }}</td>
            <td class="text-left desc-9 align-top">
                {!! $row->item->description !!}
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><small>{{ $dtos->factor * 100 }}% {{$dtos->description }}</small>
                    @endforeach
                @endif
            </td>
            <td class="text-right desc-9 align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right desc-9 align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach
        @if($document->total_exportation > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_exportation, 2) }}</td>
            </tr>
        @endif
        @if($document->total_free > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_free, 2) }}</td>
            </tr>
        @endif
        @if($document->total_unaffected > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_unaffected, 2) }}</td>
            </tr>
        @endif
        @if($document->total_exonerated > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_exonerated, 2) }}</td>
            </tr>
        @endif
        @if($document->total_taxed > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_taxed, 2) }}</td>
            </tr>
        @endif
        @if($document->total_discount > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">DESCUENTO TOTAL: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_discount, 2) }}</td>
            </tr>
        @endif
        @if($document->total_plastic_bag_taxes > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">ICBPER: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_plastic_bag_taxes, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="4" class="text-right font-bold desc">IGV: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total_igv, 2) }}</td>
        </tr>

        @if($document->perception)
        <tr>
            <td colspan="4" class="text-right font-bold desc">IMPORTE TOTAL: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-right font-bold desc">PERCEPCIÓN: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->perception->amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format(($document->total + $document->perception->amount), 2) }}</td>
        </tr>
        @else
        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
        </tr>
        @endif 
    </tbody>
</table>
<table class="full-width">
    <tr>
        @foreach($document->legends as $row)
            <td class="desc pt-3">Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></td>
        @endforeach
    </tr>
    <tr>
        <td class="desc pt-3">
            <strong>Información adicional</strong>
            @foreach($document->additional_information as $information)
                <p class="desc">{{ $information }}</p>
            @endforeach
        </td>
    </tr>
    <tr>
        <td class="text-center pt-3"><img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></td>
    </tr>
    <tr>
        <td class="text-center desc">Código Hash: {{ $document->hash }}</td>
    </tr>
    <tr>
        <td class="text-center desc pt-5">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
    </tr>
</table>
</body>
</html>