<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Details - {{ $data->stock_code ?? $data->id }}</title>
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
            width: 45%;
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
            font-size: 14px;
            color: #1a202c;
        }
        .signatures-table {
            width: 100%;
            margin-top: 70px;
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
            <h1>Stock Purchase / Inward Slip</h1>
            <p>Branch: {{ $data->branch['name'] ?? 'Main' }}</p>
        </div>

        <table class="meta-table">
            <tr>
                <td>
                    <span class="meta-label">Stock Code:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $data->stock_code ?? $data->id }}</span>
                </td>
                <td>
                    <span class="meta-label">Buy Date:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $data->buy_date }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Branch:</span>
                    <span class="meta-value">{{ $data->branch['name'] ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="meta-label">Lot Number:</span>
                    <span class="meta-value">{{ $data->lot_number }}</span>
                </td>
            </tr>
        </table>

        {{-- <div class="section-title">Party / Customer Details</div>
        <table class="meta-table">
            <tr>
                <td>
                    <span class="meta-label">Customer / Dealer:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $data->customer_name }}</span>
                    @if(!empty($data->dealer['business_name']) && $data->dealer['business_name'] !== $data->customer_name)
                        <br><span class="meta-label">Business:</span>
                        <span class="meta-value">{{ $data->dealer['business_name'] }}</span>
                    @endif
                </td>
                <td>
                    <span class="meta-label">Contact Number:</span>
                    <span class="meta-value">{{ $data->dealer['contact_number'] ?? 'N/A' }}</span>
                    @if(!empty($data->dealer['address']))
                        <br><span class="meta-label">Address:</span>
                        <span class="meta-value">{{ $data->dealer['address'] }}</span>
                    @endif
                </td>
            </tr>
            @if(!empty($data->vehicle_number) || !empty($data->driver_number))
            <tr>
                <td>
                    <span class="meta-label">Vehicle Number:</span>
                    <span class="meta-value">{{ $data->vehicle_number ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="meta-label">Driver Number:</span>
                    <span class="meta-value">{{ $data->driver_number ?? 'N/A' }}</span>
                </td>
            </tr>
            @endif
        </table> --}}

        <div class="section-title">Stock & Rate Particulars</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 27%;">Stock Name</th>
                    <th style="width: 25%;">Brand Name</th>
                    <th style="width: 15%; text-align: right;">Bag (Qty)</th>
                    <th style="width: 12%; text-align: right;">Rate</th>
                    <th style="width: 13%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td style="font-weight: bold;">{{ $data->stock_name }}</td>
                    <td>{{ $data->brand_name }}</td>
                    <td style="text-align: right;">
                        <strong>{{ number_format($data->bag, 2) }}</strong> {{ $data->unit_type }}
                        @if($data->alternate_unit_value !== null)
                            <div style="font-size: 10px; color: #718096;">
                                ({{ number_format($data->alternate_unit_value, 2) }} {{ $data->alternate_unit_type }})
                            </div>
                        @endif
                    </td>
                    <td style="text-align: right;">{{ number_format($data->rate, 2) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($data->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td>Total Bags:</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($data->bag, 2) }} {{ $data->unit_type }}</td>
                </tr>
                <tr>
                    <td>Rate per Bag:</td>
                    <td style="text-align: right;">{{ number_format($data->rate, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Amount:</td>
                    <td style="text-align: right;">{{ number_format($data->total_amount, 2) }}</td>
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
