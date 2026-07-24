<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gate Pass - {{ $gatepass->gatepass_number }}</title>
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
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #718096;
            font-size: 14px;
        }
        .meta-table, .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 6px 10px;
            vertical-align: top;
            width: 50%;
        }
        .meta-label {
            font-weight: bold;
            color: #4a5568;
            width: 150px;
            display: inline-block;
        }
        .meta-value {
            color: #2d3748;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #edf2f7;
            padding: 6px 10px;
            margin-top: 20px;
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
        .remarks-box {
            border: 1px dashed #cbd5e0;
            padding: 10px;
            background-color: #f8fafc;
            margin-top: 15px;
            border-radius: 4px;
        }
        .remarks-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #4a5568;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            font-size: 11px;
            color: #718096;
        }
        .signatures-table {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
        }
        .signatures-table td {
            text-align: center;
            width: 33.33%;
            vertical-align: bottom;
        }
        .signature-line {
            width: 80%;
            margin: 0 auto 5px auto;
            border-bottom: 1px solid #a0aec0;
        }
        .signature-label {
            font-size: 11px;
            color: #4a5568;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gate Pass Document</h1>
            <p>{{ strtoupper($gatepass->gatepass_type) }} GATE PASS ({{ strtoupper($gatepass->movement_type) }})</p>
        </div>

        <table class="meta-table">
            <tr>
                <td>
                    <span class="meta-label">Gate Pass No:</span>
                    <span class="meta-value" style="font-weight: bold;">{{ $gatepass->gatepass_number }}</span>
                </td>
                <td>
                    <span class="meta-label">Date & Time:</span>
                    <span class="meta-value">{{ $gatepass->gatepass_date ? $gatepass->gatepass_date->format('Y-m-d H:i:s') : 'N/A' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Branch:</span>
                    <span class="meta-value">{{ $gatepass->branch->name ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="meta-label">Status:</span>
                    <span class="meta-value" style="text-transform: capitalize;">{{ $gatepass->status }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Party & Movement Information</div>
        <table class="meta-table">
            <tr>
                <td>
                    @if($gatepass->dealer)
                        <span class="meta-label">Dealer:</span>
                        <span class="meta-value">{{ $gatepass->dealer->name }}</span>
                    @elseif($gatepass->customer)
                        <span class="meta-label">Customer:</span>
                        <span class="meta-value">{{ $gatepass->customer->name }}</span>
                    @else
                        <span class="meta-label">Party Name:</span>
                        <span class="meta-value">N/A</span>
                    @endif
                </td>
                <td>
                    @if($gatepass->sale)
                        <span class="meta-label">Sale Invoice No:</span>
                        <span class="meta-value">{{ $gatepass->sale->invoice_number }}</span>
                    @elseif($gatepass->purchase)
                        <span class="meta-label">Purchase UUID:</span>
                        <span class="meta-value">{{ substr($gatepass->purchase->purchase_id, 0, 8) }}...</span>
                    @else
                        <span class="meta-label">Reference ID:</span>
                        <span class="meta-value">N/A</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Transporter:</span>
                    <span class="meta-value">{{ $gatepass->transporter->name ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="meta-label">Vehicle Number:</span>
                    <span class="meta-value">{{ $gatepass->vehicle->name ?? 'N/A' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="meta-label">Driver Name:</span>
                    <span class="meta-value">{{ $gatepass->driver_name ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="meta-label">Driver Number:</span>
                    <span class="meta-value">{{ $gatepass->driver_number ?? 'N/A' }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Item Details</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Stock Item</th>
                    <th style="width: 15%;">Lot Number</th>
                    <th style="width: 20%; text-align: right;">Quantity</th>
                    <th style="width: 25%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gatepass->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->stock->stock_name ?? 'N/A' }}</td>
                        <td>{{ $detail->lot_number ?? 'N/A' }}</td>
                        <td style="text-align: right;">
                            <div>{{ $detail->unit_value }} {{ $detail->unit->unit ?? 'Units' }}</div>
                            @if($detail->alternate_unit_value !== null)
                                <div style="font-size: 10px; color: #718096;">
                                    {{ $detail->alternate_unit_value }} {{ $detail->alternateUnit->alter_unit ?? 'Alt Units' }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $detail->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #a0aec0;">No items found in this gatepass.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($gatepass->remarks)
            <div class="remarks-box">
                <div class="remarks-title">Remarks / Instructions:</div>
                <div>{{ $gatepass->remarks }}</div>
            </div>
        @endif

        <table class="signatures-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Prepared By ({{ $gatepass->user->name ?? 'Staff' }})</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Driver Signature</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signatory</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div style="float: left;">Generated on: {{ date('Y-m-d H:i:s') }}</div>
            <div style="float: right;">Page 1 of 1</div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>
