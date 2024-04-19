<?php

class QRCodeGenerator {
    protected $numQRCodes;
    protected $qrCodeData = [];

    public function __construct($numQRCodes = 5) {
        $this->numQRCodes = $numQRCodes;
        $this->generateQRCodeData();
    }

    private function generateQRCodeData() {
        for ($i = 1; $i <= $this->numQRCodes; $i++) {
            // Generál adatot minden QR kódhoz
            $this->qrCodeData[] = 'QR code data ' . $i;
        }
    }

    public function getQRCodeData() {
        return $this->qrCodeData;
    }

    public function generateQRCodeBase64($data) {
        return 'data:image/png;base64,' . base64_encode(
            file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($data))
        );
    }
}

?>