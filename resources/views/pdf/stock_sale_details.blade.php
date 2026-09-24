<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Sale Report - {{ $data->stock_code ?? $data->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #718096;
            font-size: 13px;
        }
        .meta-table, .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .meta-table td {
            padding: 6px 10px;
            vertical-align: top;
            width: 50%;
        }
        .meta-label {
            font-weight: bold;
            color: #4a5568;
            width: 140px;
            display: inline-block;
        }
        .meta-value {
            color: #2d3748;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            background-color: #edf2f7;
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            color: #2b6cb0;
            text-transform: uppercase;
            border-left: 3px solid #3182ce;
        }
        .details-table th {
            background-color: #2d3748;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .details-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .details-table tr:nth-child(even) td {
            background-color: #f7fafc;
        }
        .summary-box {
            margin-top: 15px;
            float: right;
            width: 48%;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px;
            font-size: 12px;
        }
        .summary-table tr.total-row td {
            border-top: 2px solid #2d3748;
            font-weight: bold;
            font-size: 13px;
            color: #1a202c;
        }
        .signatures-table {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
            clear: both;
        }
        .signatures-table td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
        }
        .signature-line {
            width: 70%;
            margin: 0 auto 5px auto;
            border-bottom: 1px solid #a0aec0;
        }
        .signature-label {
            font-size: 11px;
            color: #4a5568;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            font-size: 11px;
            color: #718096;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Stock Sale Report / Outward Slip</h1>
            <p>Branch: {{ $data->branch['name'] ?? 'Main' }}</p>
        </div>

        <table class="meta-table">
            <tr>
                <td>
                    <span class="meta-label">Stock Code:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $data->stock_code ?? $data->id }}</span>
                </td>
                <td>
                    <span class="meta-label">Report Date:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ date('d-m-Y') }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Stock Name:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $data->stock_name }}</span>
                </td>
                <td>
                    <span class="meta-label">Brand Name:</span>
                    <span class="meta-value">{{ $data->brand_name }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Lot Number:</span>
                    <span class="meta-value">{{ $data->lot_number }}</span>
                </td>
                <td>
                    <span class="meta-label">Branch:</span>
                    <span class="meta-value">{{ $data->branch['name'] ?? 'N/A' }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Dealer Sales Summary</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 32%;">Dealer Name</th>
                    <th style="width: 25%;">Business / Contact</th>
                    <th style="width: 17%; text-align: right;">Quantity (Bags)</th>
                    <th style="width: 18%; text-align: right;">Alternate Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data->dealers ?? [] as $index => $dealer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: bold;">{{ $dealer['dealer_name'] }}</td>
                        <td>
                            <div>{{ $dealer['business_name'] ?? 'N/A' }}</div>
                            <div style="font-size: 10px; color: #718096;">{{ $dealer['contact_number'] ?? '' }}</div>
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            {{ number_format($dealer['bags'], 2) }} {{ $dealer['unit_type'] ?? $data->unit_type }}
                        </td>
                        <td style="text-align: right;">
                            {{ number_format($dealer['alternate_unit_value'], 2) }} {{ $dealer['alternate_unit_type'] ?? $data->alternate_unit_type }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #a0aec0;">No sales recorded for this stock.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(!empty($data->sales))
        <div class="section-title">Itemized Outward Transactions</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 6%;">#</th>
                    <th style="width: 16%;">Invoice No</th>
                    <th style="width: 14%;">Date</th>
                    <th style="width: 24%;">Dealer</th>
                    <th style="width: 18%;">Vehicle / Driver</th>
                    <th style="width: 22%; text-align: right;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->sales as $index => $sale)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: bold;">{{ $sale['invoice_number'] ?? 'N/A' }}</td>
                        <td>{{ $sale['sale_date'] ?? 'N/A' }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $sale['dealer_name'] }}</div>
                            @if(!empty($sale['contact_number']))
                                <div style="font-size: 10px; color: #718096;">{{ $sale['contact_number'] }}</div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $sale['vehicle_number'] ?? 'N/A' }}</div>
                            @if(!empty($sale['driver_name']))
                                <div style="font-size: 10px; color: #718096;">{{ $sale['driver_name'] }}</div>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="font-weight: bold;">{{ number_format($sale['bag'], 2) }} {{ $sale['unit_type'] }}</div>
                            @if($sale['alternate_unit_value'] !== null)
                                <div style="font-size: 10px; color: #718096;">
                                    {{ number_format($sale['alternate_unit_value'], 2) }} {{ $sale['alternate_unit_type'] }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td>Total Sold Bags:</td>
                    <td style="text-align: right; font-weight: bold; color: #2b6cb0;">
                        {{ number_format($data->total_sold_bags, 2) }} {{ $data->unit_type }}
                        @if($data->total_sold_alternate_unit_value > 0)
                            <span style="font-size: 10px; color: #718096;">({{ number_format($data->total_sold_alternate_unit_value, 2) }} {{ $data->alternate_unit_type }})</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Remaining Stock:</td>
                    <td style="text-align: right; font-weight: bold; color: #276749;">
                        {{ number_format($data->remaining_bags, 2) }} {{ $data->unit_type }}
                        @if($data->remaining_alternate_unit_value > 0)
                            <span style="font-size: 10px; color: #718096;">({{ number_format($data->remaining_alternate_unit_value, 2) }} {{ $data->alternate_unit_type }})</span>
                        @endif
                    </td>
                </tr>
                <tr class="total-row">
                    <td>Total Stock (Initial):</td>
                    <td style="text-align: right;">
                        {{ number_format($data->initial_bags, 2) }} {{ $data->unit_type }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="signatures-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Prepared By</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signatory</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div style="float: left;">Generated on: {{ date('d-m-Y H:i:s') }}</div>
            <div style="float: right;">Page 1 of 1</div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>
