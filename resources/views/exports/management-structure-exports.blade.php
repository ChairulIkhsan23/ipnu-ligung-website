<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struktur Kepengurusan</title>
    <style>
        @page { margin: 40px 25px; }
        body { 
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000000;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 11px;
            color: #333333;
        }
        
        /* Info */
        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #cccccc;
        }
        .info-item {
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f5f5f5;
            padding: 6px 8px;
            border: 1px solid #dddddd;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #dddddd;
            font-size: 10px;
        }
        
        /* Status */
        .status {
            font-size: 9px;
            font-weight: bold;
        }
        .status.active {
            color: #006600;
        }
        .status.inactive {
            color: #cc0000;
        }
        
        /* Column Widths */
        .no { width: 35px; text-align: center; }
        .period { width: 100px; text-align: center; }
        .created { width: 80px; text-align: center; }
        .status-col { width: 70px; }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="title">STRUKTUR KEPENGURUSAN</div>
        <div class="subtitle">PAC IPNU IPPNU KECAMATAN LIGUNG</div>
    </div>
    
    <!-- Info -->
    <div class="info">
        <div class="info-item">
            <span class="info-label">Tanggal: </span>{{ now()->format('d/m/Y H:i') }}
        </div>
        <div class="info-item">
            <span class="info-label">Total: </span>{{ $records->count() }} data
        </div>
        <div class="info-item">
            <span class="info-label">Aktif: </span>{{ $records->where('status', true)->count() }}
        </div>
    </div>
    
    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th class="period">Periode</th>
                <th class="status-col">Status</th>
                <th class="created">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $record)
            <tr>
                <td class="no">{{ $index + 1 }}</td>
                <td>{{ $record->name }}</td>
                <td>{{ $record->position }}</td>
                <td class="period">{{ $record->start_year }} - {{ $record->end_year ?: 'Sekarang' }}</td>
                <td>
                    <span class="status {{ $record->status ? 'active' : 'inactive' }}">
                        {{ $record->status ? 'AKTIF' : 'TIDAK AKTIF' }}
                    </span>
                </td>
                <td class="created">{{ $record->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Footer -->
    <div class="footer">
        Dicetak {{ now()->format('d F Y H:i') }}
    </div>
</body>
</html>