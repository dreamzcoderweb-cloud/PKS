<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Report</title>
    <style>
        @page {
            margin: 10mm 8mm 12mm 8mm;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            color: #2d3748;
            margin: 0;
            padding: 0;
            font-size: 10.5px;
            line-height: 1.35;
            background-color: #ffffff;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        
        /* App-inspired Green Header */
        .header-bar {
            background-color: #2e7d32;
            color: #ffffff;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .header-bar table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: capitalize;
            color: #ffffff;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #e8f5e9;
            margin-top: 3px;
        }
        .header-meta {
            text-align: right;
            font-size: 10px;
            color: #e8f5e9;
        }
        
        /* Filter / Meta info bar */
        .filter-bar {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .filter-bar table {
            width: 100%;
            border-collapse: collapse;
        }
        .filter-item {
            display: inline-block;
            margin-right: 15px;
        }
        .filter-label {
            font-weight: bold;
            color: #4a5568;
        }
        .filter-value {
            color: #2e7d32;
            font-weight: 600;
        }

        /* Purchase Data Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .report-table th {
            background-color: #2e7d32;
            color: #ffffff;
            font-weight: bold;
            padding: 7px 5px;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1b5e20;
            text-align: left;
        }
        .report-table td {
            padding: 6px 5px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
            color: #2d3748;
        }
        .report-table tbody tr:nth-child(even) td {
            background-color: #f9fbf9;
        }
        .report-table tbody tr:hover td {
            background-color: #f0fdf4;
        }

        /* Column Alignments */
        .col-center {
            text-align: center;
        }
        .col-right {
            text-align: right;
        }
        .col-left {
            text-align: left;
        }

        /* Bottom Summary Box matching user app */
        .summary-card {
            background-color: #f1f8e9;
            border: 1.5px solid #a5d6a7;
            border-radius: 8px;
            padding: 10px 16px;
            margin-top: 10px;
            margin-bottom: 12px;
        }
        .summary-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-purchases-count {
            font-size: 13px;
            font-weight: bold;
            color: #1b5e20;
        }
        .summary-total-amount {
            font-size: 15px;
            font-weight: bold;
            color: #0288d1;
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 9px;
            color: #718096;
        }
        .footer table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Bar -->
        <div class="header-bar">
            <table>
                <tr>
                    <td style="width: 60%; vertical-align: middle;">
                        <div class="header-title">Purchase Report</div>
                        @if(!empty($data->filters['branch_name']))
                            <div class="header-subtitle">Branch: {{ $data->filters['branch_name'] }}</div>
                        @endif
                    </td>
                    <td style="width: 40%; vertical-align: middle; text-align: right;">
                        <div class="header-meta">
                            Generated: {{ $data->meta['generated_at'] ?? date('d-m-Y H:i') }}<br>
                            @if(!empty($data->meta['generated_by']))
                                User: {{ $data->meta['generated_by'] }}
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Filter / Applied Query Bar -->
        <div class="filter-bar">
            <table>
                <tr>
                    <td>
                        <span class="filter-item">
                            <span class="filter-label">From Date:</span> 
                            <span class="filter-value">{{ !empty($data->filters['from']) ? $data->filters['from'] : 'All' }}</span>
                        </span>
                        <span class="filter-item">
                            <span class="filter-label">To Date:</span> 
                            <span class="filter-value">{{ !empty($data->filters['to']) ? $data->filters['to'] : 'All' }}</span>
                        </span>
                        @if(!empty($data->filters['dealer_name']))
                            <span class="filter-item">
                                <span class="filter-label">Dealer:</span> 
                                <span class="filter-value">{{ $data->filters['dealer_name'] }}</span>
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right; color: #718096; font-size: 9.5px;">
                        Total Records: <strong>{{ count($data->items ?? []) }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main Report Table with the 11 Exact Headers -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 8%;" class="col-center">Date</th>
                    <th style="width: 14%;" class="col-left">Dealer Name</th>
                    <th style="width: 9%;" class="col-left">Vehicle</th>
                    <th style="width: 11%;" class="col-left">Transporter</th>
                    <th style="width: 9%;" class="col-left">Brand</th>
                    <th style="width: 11%;" class="col-left">Stock</th>
                    <th style="width: 7%;" class="col-center">Lot No</th>
                    <th style="width: 8%;" class="col-right">Unit Qty</th>
                    <th style="width: 8%;" class="col-right">Alter Qty</th>
                    <th style="width: 7%;" class="col-right">Rate</th>
                    <th style="width: 8%;" class="col-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data->items ?? [] as $item)
                    <tr>
                        <td class="col-center">{{ $item['date'] }}</td>
                        <td class="col-left" style="font-weight: 600;">{{ $item['dealer_name'] }}</td>
                        <td class="col-left">{{ $item['vehicle'] }}</td>
                        <td class="col-left">{{ $item['transporter'] }}</td>
                        <td class="col-left">{{ $item['brand'] }}</td>
                        <td class="col-left">{{ $item['stock'] }}</td>
                        <td class="col-center">{{ $item['lot_no'] }}</td>
                        <td class="col-right">
                            @if($item['unit_qty'] !== null)
                                {{ number_format($item['unit_qty'], 2) }}
                                @if(!empty($item['unit_name']))
                                    <span style="font-size: 8px; color: #718096;">{{ $item['unit_name'] }}</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="col-right">
                            @if($item['alter_qty'] !== null)
                                {{ number_format($item['alter_qty'], 2) }}
                                @if(!empty($item['alter_unit_name']))
                                    <span style="font-size: 8px; color: #718096;">{{ $item['alter_unit_name'] }}</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="col-right">
                            @if($item['rate'] !== null)
                                {{ number_format($item['rate'], 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="col-right" style="font-weight: bold; color: #1b5e20;">
                            {{ number_format($item['total_amount'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 20px; color: #a0aec0;">
                            No purchase records found matching the specified criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bottom Summary Card matching User App Design -->
        <div class="summary-card">
            <table>
                <tr>
                    <td style="width: 50%; vertical-align: middle;">
                        <span class="summary-purchases-count">Total Purchases: {{ $data->total_purchases ?? 0 }}</span>
                        @if(($data->total_unit_qty ?? 0) > 0)
                            <span style="margin-left: 15px; font-size: 11px; color: #4a5568;">
                                (Unit Qty: <strong>{{ number_format($data->total_unit_qty, 2) }}</strong>)
                            </span>
                        @endif
                    </td>
                    <td style="width: 50%; vertical-align: middle; text-align: right;">
                        <span class="summary-total-amount">&#8377; {{ number_format($data->total_amount ?? 0, 2) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table>
                <tr>
                    <td style="width: 60%; text-align: left;">
                        Purchase Report &bull; Inward Records
                    </td>
                    <td style="width: 40%; text-align: right;">
                        Generated on {{ $data->meta['generated_at'] ?? date('d-m-Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
