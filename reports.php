DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Performance Reports - Safari Tours</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; }
        .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .filter-box { display: flex; gap: 15px; align-items: center; margin-bottom: 25px; background: #eef2f5; padding: 15px; border-radius: 5px; }
        label { font-weight: bold; }
        input[type="date"], button { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; border: none; cursor: pointer; }
        .report-summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .card { background: #f1f3f5; padding: 15px; border-radius: 6px; text-align: center; }
        .card h3 { margin: 0; font-size: 14px; color: #666; }
        .card p { margin: 5px 0 0; font-size: 20px; font-weight: bold; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #5b656b; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Safari Tours Ltd - Business Performance Report</h2>
        
        <div class="filter-box">
            <label>Start Date:</label>
            <input type="date" value="2026-10-01">
            <label>End Date:</label>
            <input type="date" value="2026-10-31">
            <button>Generate Report</button>
            <button style="background-color: #007bff;">Print Report</button>
        </div>

        <div class="report-summary">
            <div class="card">
                <h3>Total Revenue</h3>
                <p>KSh 166,780.00</p>
            </div>
            <div class="card">
                <h3>Total Collected</h3>
                <p>KSh 78,025.00</p>
            </div>
            <div class="card">
                <h3>Outstanding Balance</h3>
                <p>KSh 88,755.00</p>
            </div>
        </div>

        <h3>Revenue & Payment Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-00003</td>
                    <td>Nicole Okata</td>
                    <td>KSh 76,780.00</td>
                    <td>KSh 76,780.00</td>
                    <td>Paid</td>
                </tr>
                <tr>
                    <td>INV-00002</td>
                    <td>John Doe</td>
                    <td>KSh 37,000.00</td>
                    <td>KSh 1,245.00</td>
                    <td>Partial</td>
                </tr>
                <tr>
                    <td>INV-00001</td>
                    <td>Annah Dorine</td>
                    <td>KSh 53,000.00</td>
                    <td>KSh 0.00</td>
                    <td>Pending</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>==