<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: sans-serif; padding: 20px; color: #18181b; }
        .header { color: #f97316; font-size: 20px; font-weight: bold; }
        .details { margin-top: 15px; border-left: 4px solid #f97316; padding-left: 15px; }
        .footer { margin-top: 20px; font-size: 12px; color: #71717a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Halo!</div>
        <p>Ada update status untuk Order <strong>#{{ $order->id }}</strong></p>
        
        <div class="details">
            <p><strong>Status Baru:</strong> {{ strtoupper($notification->status) }}</p>
            <p><strong>Pesan:</strong> "{{ $notification->content }}"</p>
            <p><strong>Oleh:</strong> {{ $notification->user->name }}</p>
        </div>

        <p>Silakan cek aplikasi SIRUSIR untuk melihat detail rute pengiriman pasir.</p>
        
        <div class="footer">
            Dikirim otomatis oleh Sistem SIRUSIR - {{ now()->format('H:i:s') }}
        </div>
    </div>
</body>
</html>