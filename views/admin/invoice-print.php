<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= htmlspecialchars($invoice['invoiceNumber']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="no-print flex justify-center gap-3 mb-6">
    <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700">Print / Save PDF</button>
    <a href="<?= BASE_URL ?>/admin/payments/send-invoice/<?= $payment['_id'] ?>"
       onclick="return confirm('Send this invoice to <?= htmlspecialchars($client['email'] ?? 'client') ?>?')"
       class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700">
       📧 Send to <?= htmlspecialchars($client['email'] ?? 'Client') ?>
    </a>
    <a href="javascript:history.back()" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">← Back</a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-10 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold">WebCultivate</h1>
                <p class="text-indigo-200 text-sm mt-1">Software Solutions</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-indigo-200 uppercase tracking-wider">Invoice</p>
                <p class="text-xl font-bold mt-1"><?= htmlspecialchars($invoice['invoiceNumber']) ?></p>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="px-8 py-8">
        <!-- Bill To -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Billed To</p>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($client['name'] ?? '') ?></p>
                <?php if (!empty($client['company'])): ?>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($client['company']) ?></p>
                <?php endif; ?>
                <?php if (!empty($client['email'])): ?>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($client['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Invoice Details</p>
                <div class="space-y-1 text-sm">
                    <p><span class="text-gray-400">Date:</span> <span class="text-gray-800 font-medium"><?= $invoice['generatedAt'] ?></span></p>
                    <p><span class="text-gray-400">Due:</span> <span class="text-gray-800 font-medium"><?= $invoice['dueDate'] ?></span></p>
                    <p><span class="text-gray-400">Month:</span> <span class="text-gray-800 font-medium"><?= $invoice['billingMonth'] ?></span></p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="border border-gray-200 rounded-xl overflow-hidden mb-8">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-800">Service Payment</p>
                            <p class="text-xs text-gray-400 mt-0.5">Billing period: <?= $invoice['billingMonth'] ?></p>
                        </td>
                        <td class="px-5 py-4 text-right font-semibold text-gray-800">
                            ₹<?= number_format($invoice['amount'], 2) ?>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 text-sm text-gray-500">Total Project Cost</td>
                        <td class="px-5 py-3 text-right font-semibold text-indigo-600">₹<?= number_format($payment['totalProjectCost'] ?? 0, 2) ?></td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 text-sm text-gray-500">Received Amount</td>
                        <td class="px-5 py-3 text-right font-semibold text-emerald-600">₹<?= number_format($payment['receivedAmount'] ?? 0, 2) ?></td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 text-sm text-gray-500">Remaining Amount</td>
                        <td class="px-5 py-3 text-right font-semibold text-amber-600">₹<?= number_format($payment['remainingAmount'] ?? 0, 2) ?></td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="px-5 py-4 font-semibold text-gray-700 text-sm">Bill Amount</td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-xl font-bold text-indigo-600">₹<?= number_format($invoice['amount'], 2) ?></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Status -->
        <?php
        $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'unpaid' => 'bg-amber-100 text-amber-700 border-amber-200', 'overdue' => 'bg-red-100 text-red-700 border-red-200'];
        $sc = $statusColors[$invoice['status']] ?? 'bg-gray-100 text-gray-600 border-gray-200';
        ?>
        <div class="flex justify-between items-center p-4 rounded-xl border <?= $sc ?>">
            <span class="text-sm font-semibold">Payment Status</span>
            <span class="text-sm font-bold uppercase tracking-wider"><?= $invoice['status'] ?></span>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">Thank you for your business. For queries, contact your account manager.</p>
            <p class="text-xs text-gray-300 mt-1">Generated by CompanyMS on <?= date('d M Y H:i') ?></p>
        </div>
    </div>
</div>
</body>
</html>
