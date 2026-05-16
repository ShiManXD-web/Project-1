<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambodia Electricity Cost Calculator</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #000;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .calculator-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            max-width: 480px;
            width: 100%;
        }

        h2 {
            color: #0056b3;
            margin-top: 0;
            margin-bottom: 5px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #216bff;
            font-size: 0.9em;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #4a5568;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background-color: #004085;
        }

        .results {
            margin-top: 25px;
            padding: 20px;
            background-color: #f7fafc;
            border-top: 4px solid #0056b3;
            border-radius: 6px;
        }

        .results h3 {
            margin-top: 0;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .results table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .results td {
            padding: 8px 0;
            color: #4a5568;
        }

        .results td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .total-row td {
            font-size: 1.2em;
            color: #d63031;
            font-weight: bold !important;
            border-top: 2px dashed #cbd5e0;
            padding-top: 12px;
        }

        .currency-note {
            font-size: 0.85em;
            color: #718096;
            text-align: center;
            margin-top: 5px;
        }

        .error {
            margin-top: 15px;
            padding: 12px;
            background: #ffe5e5;
            color: #c53030;
            border-radius: 6px;
            font-weight: 600;
        }
    </style>
</head>

<body>

<a href="portfoliowebsite.html"><button class="btn"> Back</button></a>
<div class="calculator-card">

    <h2>អគ្គិសនី Cambodia</h2>
    <div class="subtitle">Electricity Cost Calculator (Cambodia)</div>

    <?php

    // Default values
    $old_kwh = '';
    $new_kwh = '';

    $consumption = 0;

    $rate_type = 'official';
    $custom_rate = 1000;

    $usd_exchange_rate = 4050;

    $error = '';

    if (isset($_POST['calculate'])) {

        $old_kwh = filter_input(INPUT_POST, 'old_kwh', FILTER_VALIDATE_FLOAT);
        $new_kwh = filter_input(INPUT_POST, 'new_kwh', FILTER_VALIDATE_FLOAT);

        $rate_type = $_POST['rate_type'];

        $custom_rate = filter_input(INPUT_POST, 'custom_rate', FILTER_VALIDATE_FLOAT);

        if ($old_kwh === false || $new_kwh === false) {

            $error = "Please enter valid meter readings.";

        } elseif ($new_kwh < $old_kwh) {

            $error = "New meter reading must be greater than old meter reading.";

        } else {

            $consumption = $new_kwh - $old_kwh;
        }
    }
    ?>

    <form action="" method="POST">

        <div class="form-group">
            <label for="old_kwh">គីឡូចាស់(KWh):</label>
            <input
                type="number"
                id="old_kwh"
                name="old_kwh"
                step="0.01"
                min="0"
                required
                value="<?php echo htmlspecialchars($old_kwh); ?>"
                placeholder="Previous reading">
        </div>

        <div class="form-group">
            <label for="new_kwh">គីឡូថ្មី (kWh):</label>
            <input
                type="number"
                id="new_kwh"
                name="new_kwh"
                step="0.01"
                min="0"
                required
                value="<?php echo htmlspecialchars($new_kwh); ?>"
                placeholder="Current reading">
        </div>

        <div class="form-group">
            <label for="rate_type">Tariff Structure:</label>

            <select id="rate_type" name="rate_type" onchange="toggleCustomRate(this.value)">

                <option value="official"
                    <?php echo $rate_type == 'official' ? 'selected' : ''; ?>>
                    Official EDC Residential (Tiered)
                </option>

                <option value="custom"
                    <?php echo $rate_type == 'custom' ? 'selected' : ''; ?>>
                    Landlord / Condo Rate (Flat Fee)
                </option>

            </select>
        </div>

        <div
            class="form-group"
            id="custom_rate_group"
            style="display: <?php echo $rate_type == 'custom' ? 'block' : 'none'; ?>;">

            <label for="custom_rate">Flat Rate per kWh (KHR):</label>

            <input
                type="number"
                id="custom_rate"
                name="custom_rate"
                step="1"
                min="0"
                value="<?php echo htmlspecialchars($custom_rate); ?>">
        </div>

        <button type="submit" name="calculate">
            គណនាទឹកប្រាក់
        </button>

    </form>

    <?php if (!empty($error)): ?>គណនាទឹកប្រាក់

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php endif; ?>

    <?php

    if (
        isset($_POST['calculate']) &&
        empty($error)
    ) {

        $total_khr = 0;

        $calculation_method = "";

        if ($rate_type == 'official') {

            // Tier 1
            $remaining = $consumption;

            $t1_qty = min($remaining, 10);
            $total_khr += $t1_qty * 380;
            $remaining -= $t1_qty;

            // Tier 2
            if ($remaining > 0) {

                $t2_qty = min($remaining, 40);

                $total_khr += $t2_qty * 480;

                $remaining -= $t2_qty;
            }

            // Tier 3
            if ($remaining > 0) {

                $t3_qty = min($remaining, 150);

                $total_khr += $t3_qty * 610;

                $remaining -= $t3_qty;
            }

            // Tier 4
            if ($remaining > 0) {

                $total_khr += $remaining * 730;
            }

            $calculation_method = "Official EDC Tiered System";

        } else {

            $total_khr = $consumption * $custom_rate;

            $calculation_method =
                "Flat Rate (" .
                number_format($custom_rate) .
                " KHR/kWh)";
        }

        // USD conversion
        $total_usd = $total_khr / $usd_exchange_rate;
    ?>

        <div class="results">

            <h3>Bill Breakdown</h3>

            <table>

                <tr>
                    <td>គីឡូចាស់:</td>
                    <td><?php echo number_format($old_kwh, 2); ?> kWh</td>
                </tr>

                <tr>
                    <td>គីឡូថ្មី:</td>
                    <td><?php echo number_format($new_kwh, 2); ?> kWh</td>
                </tr>

                <tr>
                    <td>Total Usage:</td>
                    <td><?php echo number_format($consumption, 2); ?> kWh</td>
                </tr>

                <tr>
                    <td>Rate Basis:</td>
                    <td><?php echo $calculation_method; ?></td>
                </tr>

                <tr class="total-row">
                    <td>Total (KHR):</td>
                    <td><?php echo number_format($total_khr, 0); ?> ៛</td>
                </tr>

                <tr class="total-row" style="color:#2b6cb0; border-top:none; padding-top:0;">
                    <td>Total (USD):</td>
                    <td>$<?php echo number_format($total_usd, 2); ?></td>
                </tr>

            </table>

            <div class="currency-note">
                *Exchange rate calculated at
                1 USD = <?php echo $usd_exchange_rate; ?> KHR
            </div>

        </div>

    <?php } ?>

</div>

<script>

function toggleCustomRate(val) {

    var group = document.getElementById('custom_rate_group');

    if (val === 'custom') {

        group.style.display = 'block';

    } else {

        group.style.display = 'none';
    }
}

</script>

</body>
</html>