import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

window.exportReportPDF = function(reportData) {
    const doc = new jsPDF();
    
    // Title
    doc.setFontSize(18);
    doc.text('Laporan Reservasi Hotel', 14, 22);
    
    doc.setFontSize(11);
    const dateStr = new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    doc.text(`Tanggal Cetak: ${dateStr}`, 14, 30);

    // Table
    const tableColumn = ["Kode", "Tamu", "Kamar", "Check-In", "Check-Out", "Status", "Tagihan"];
    const tableRows = [];

    reportData.forEach(item => {
        const rowData = [
            item.booking_code,
            item.guest_name,
            item.room_number,
            item.check_in,
            item.check_out,
            item.status,
            'Rp ' + parseInt(item.total_price).toLocaleString('id-ID')
        ];
        tableRows.push(rowData);
    });

    autoTable(doc, {
        head: [tableColumn],
        body: tableRows,
        startY: 40,
        theme: 'striped',
        styles: { fontSize: 9 },
        headStyles: { fillColor: [15, 23, 42] }
    });

    // Force Base64 Data URI download to bypass Webview Blob proxies
    const pdfDataUri = doc.output('datauristring');
    
    const link = document.createElement("a");
    link.href = pdfDataUri;
    link.download = `Laporan_Reservasi_${new Date().toISOString().split('T')[0]}.pdf`;
    document.body.appendChild(link);
    link.click();
    
    setTimeout(() => {
        document.body.removeChild(link);
    }, 100);
};

window.exportReportExcel = function(reportData) {
    const worksheet = XLSX.utils.json_to_sheet(reportData.map(item => ({
        'Kode Booking': item.booking_code,
        'Nama Tamu': item.guest_name,
        'Kamar': item.room_number,
        'Check-In': item.check_in,
        'Check-Out': item.check_out,
        'Status': item.status,
        'Total Tagihan': parseInt(item.total_price)
    })));

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Laporan Reservasi");
    
    // Output base64 and create a data URI
    const wboutBase64 = XLSX.write(workbook, { bookType: 'xlsx', type: 'base64' });
    const excelDataUri = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + wboutBase64;
    
    const link = document.createElement("a");
    link.href = excelDataUri;
    link.download = `Laporan_Reservasi_${new Date().toISOString().split('T')[0]}.xlsx`;
    document.body.appendChild(link);
    link.click();
    
    setTimeout(() => {
        document.body.removeChild(link);
    }, 100);
};
